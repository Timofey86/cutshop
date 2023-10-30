<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Requests\ResetPasswordFormRequest;
use Database\Factories\UserFactory;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ResetPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return void
     */
    public function it_reset_password_view_success()
    {
        $token = 'valid_token';

        $response = $this->get(action([ResetPasswordController::class, 'page'], $token));

        $response->assertOk()
            ->assertViewHas('token', $token)
            ->assertSee('Восстановление пароля')
            ->assertViewIs('auth.reset-password');

    }

    /**
     * @test
     * @return void
     */
    public function it_reset_password_with_valid_data_success()
    {
        Event::fake();

        $password = bcrypt('123456789');
        $user = UserFactory::new()->create([
            'email' => 'testing@cutcode.ru',
            'password' => $password,
        ]);
        $token = Password::createToken($user);
        $request = ResetPasswordFormRequest::factory()->create([
            'email' => 'testing@cutcode.ru',
            'password' => '0123456789',
            'password_confirmation' => '0123456789',
            'token' => $token
        ]);
        $this->assertDatabaseHas('users', [
            'email' => $request['email']
        ]);

        $response = $this->post(action([ResetPasswordController::class, 'handle'], $request));
        $response->assertValid();
        Event::assertDispatched(PasswordReset::class);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));

    }

    /**
     * @test
     * @return void
     */
    public function it_reset_password_with_invalid_data()
    {

        $password = bcrypt('123456789');
        $user = UserFactory::new()->create([
            'email' => 'testing@cutcode.ru',
            'password' => $password,
        ]);
        $token = Password::createToken($user);

        $request = ResetPasswordFormRequest::factory()->create([
            'email' => 'testing1@cutcode.ru',
            'password' => '0123456789',
            'password_confirmation' => '0123456789',
            'token' => $token
        ]);

        $response = $this->post(action([ResetPasswordController::class, 'handle'], $request));
        $response->assertInvalid();
        $this->assertDatabaseMissing('users', ['email' => $request['email']]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);

    }

}
