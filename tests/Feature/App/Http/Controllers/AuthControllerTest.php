<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Http\Controllers\AuthController;
use App\Http\Requests\ForgotPasswordFormRequest;
use App\Http\Requests\ResetPasswordFormRequest;
use App\Http\Requests\SignUpFormRequest;
use App\Listeners\SendEmailNewUserListener;
use App\Models\User;
use App\Notifications\NewUserNotification;
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
        $this->get(action([AuthController::class, 'index']))
            ->assertOk()
            ->assertSee('Вход в аккаунт')
            ->assertViewIs('auth.index');
    }

    /**
     * @test
     * @return void
     */
    public function it_sign_up_success(): void
    {
        $this->get(action([AuthController::class, 'signUp']))
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
        $this->get(action([AuthController::class, 'forgot']))
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
        $user = User::factory()->create([
            'email' => 'testing@cutcode.ru',
            'password' => bcrypt($password)
        ]);

        $request = SignUpFormRequest::factory()->create([
            'email' => $user->email,
            'password' => $password
        ]);

        $response = $this->post(action([AuthController::class, 'signIn'], $request));

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
        $user = User::factory()->create([
            'email' => 'testing@cutcode.ru',
        ]);

        $this->actingAs($user)
            ->delete(action([AuthController::class,'logOut']));

        $this->assertGuest();
    }

    /**
     * @test
     * @return void
     */
    public function it_forgot_password_with_valid_email_success()
    {
        $user = User::factory()->create([
            'email' => 'testing@cutcode.ru'
        ]);

        $request = ForgotPasswordFormRequest::factory()->create([
            'email' => $user->email
        ]);

        $response = $this->post(action([AuthController::class,'forgotPassword'], $request));

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
        $response = $this->post(action([AuthController::class, 'forgotPassword'], $request));

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

        $response = $this->get(action([AuthController::class, 'reset'],$token) );

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
        $user = User::factory()->create([
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

        $response = $this->post(action([AuthController::class,'resetPassword'], $request));
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
        $user = User::factory()->create([
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

        $response = $this->post(action([AuthController::class,'resetPassword'], $request));
        $this->assertDatabaseMissing('users', ['email' => $request['email']]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);

    }


    /**
     * @test
     * @return void
     */
    public function it_store_success(): void
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
            action([AuthController::class, 'store']),
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
