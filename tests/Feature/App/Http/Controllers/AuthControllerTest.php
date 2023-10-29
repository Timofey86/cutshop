<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SignInController;
use App\Http\Controllers\Auth\SignUpController;
use App\Http\Requests\ForgotPasswordFormRequest;
use App\Http\Requests\ResetPasswordFormRequest;
use App\Http\Requests\SignUpFormRequest;
use App\Listeners\SendEmailNewUserListener;
use App\Notifications\NewUserNotification;
use Database\Factories\UserFactory;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return void
     */
    public function it_login_page_success(): void
    {
        $this->get(action([SignInController::class, 'page']))
            ->assertOk()
            ->assertSee('Вход в аккаунт')
            ->assertViewIs('auth.login');
    }

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
    public function it_forgot_success(): void
    {
        $this->get(action([ForgotPasswordController::class, 'page']))
            ->assertOk()
            ->assertSee('Забыли пароль')
            ->assertViewIs('auth.forgot-password');
    }

    /**
     * @test
     * @return void
     */
    public function it_sign_in_success(): void
    {
        $password = '12345678';
        $user = UserFactory::new()->create([
            'email' => 'testing@cutcode.ru',
            'password' => bcrypt($password)
        ]);

        $request = SignUpFormRequest::factory()->create([
            'email' => $user->email,
            'password' => $password
        ]);

        $response = $this->post(action([SignInController::class, 'handle'], $request));

        $response->assertValid()
            ->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);

    }

    /**
     * @test
     * @return void
     */
    public function it_logout_success(): void
    {
        $user = UserFactory::new()->create([
            'email' => 'testing@cutcode.ru',
        ]);

        $this->actingAs($user)
            ->delete(action([SignInController::class,'logOut']));

        $this->assertGuest();
    }

    /**
     * @test
     * @return void
     */
    public function it_forgot_password_with_valid_email_success()
    {
        $user = UserFactory::new()->create([
            'email' => 'testing@cutcode.ru'
        ]);

        $request = ForgotPasswordFormRequest::factory()->create([
            'email' => $user->email
        ]);

        $response = $this->post(action([ForgotPasswordController::class,'handle'], $request));

        $response
            ->assertValid();
        $this->assertDatabaseHas('users', ['email' => $request['email']]);

        $response->assertStatus(302);
    }

    /**
     * @test
     * @return void
     */
    public function it_password_reset_request_with_invalid_email_success()
    {

        $request = ForgotPasswordFormRequest::factory()->create([
            'email' => 'testing@cutcode.ru'
        ]);
        $response = $this->post(action([ForgotPasswordController::class, 'handle'], $request));

        $this->assertDatabaseMissing('users', ['email' => $request['email']]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
    }


    /**
     * @test
     * @return void
     */
    public function it_reset_password_view_success()
    {
        $token = 'valid_token';

        $response = $this->get(action([ResetPasswordController::class, 'page'],$token) );

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

        $response = $this->post(action([ResetPasswordController::class,'handle'], $request));
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

        $response = $this->post(action([ResetPasswordController::class,'handle'], $request));
        $this->assertDatabaseMissing('users', ['email' => $request['email']]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);

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
}
