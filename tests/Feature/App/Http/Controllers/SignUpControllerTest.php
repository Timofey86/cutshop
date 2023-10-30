<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\SignUpController;
use App\Http\Requests\SignUpFormRequest;
use App\Listeners\SendEmailNewUserListener;
use App\Notifications\NewUserNotification;
use Database\Factories\UserFactory;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SignUpControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return void
     */
    public function it_sign_up_success(): void
    {
        $this->get(action([SignUpController::class, 'page']))
            ->assertOk()
            ->assertSee('Регистрация')
            ->assertViewIs('auth.sign-up');
    }

    /**
     * @test
     * @return void
     */
    public function it_sign_up_handle_success(): void
    {
        Notification::fake();
        Event::fake();

        $request = SignUpFormRequest::factory()->create([
            'email' => 'testing@cutcode.ru',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);

        $this->assertDatabaseMissing('users', [
            'email' => $request['email']
        ]);

        $response = $this->post(
            action([SignUpController::class, 'handle']),
            $request
        );
        $response
            ->assertValid();

        $this->assertDatabaseHas('users', [
            'email' => $request['email']
        ]);

        $user = User::query()->where('email', $request['email'])
            ->first();

        Event::assertDispatched(Registered::class);

        Event::assertListening(Registered::class, SendEmailNewUserListener::class);

        $event = new Registered($user);
        $listener = new SendEmailNewUserListener();
        $listener->handle($event);
        Notification::assertSentTo($user, NewUserNotification::class);

        $this->assertAuthenticatedAs($user);

        $response->assertRedirect(route('home'));
    }

    /**
     * @test
     * @return void
     */

    public function it_sign_up_store_with_invalid_data(): void
    {
        $user = UserFactory::new()->create([
            'email' => 'testing@cutcode.ru'
        ]);

        $request = SignUpFormRequest::factory()->create([
            'email' => 'testing@cutcode.ru',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);

        $response = $this->post(
            action([SignUpController::class, 'handle']),
            $request
        );
        $response->assertInvalid();

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
    }

}
