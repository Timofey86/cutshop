<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SignInController;
use App\Http\Requests\ResetPasswordFormRequest;
use Database\Factories\UserFactory;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ResetPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $token;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = UserFactory::new()->create();
        $this->token = Password::createToken($this->user);
    }

    /**
     * @test
     * @return void
     */
    public function it_page_success()
    {
        $token = 'valid_token';

        $response = $this->get(action([ResetPasswordController::class, 'page'],['token' => $this->token]));

        $response->assertOk()
            ->assertSee('Восстановление пароля')
            ->assertViewIs('auth.reset-password');

    }

    /**
     * @test
     * @return void
     */
    public function it_handle_success()
    {
        $password = '1234567890';
        $password_confirmation = '1234567890';

        Password::shouldReceive('reset')
            ->once()
            ->withSomeOfArgs([
                'email' => $this->user->email,
                'password' => $password,
                'password_confirmation' => $password_confirmation,
                'token' => $this->token
            ])
        ->andReturn(Password::PASSWORD_RESET);

        $response = $this->post(action([ResetPasswordController::class,'handle'],[
            'email' => $this->user->email,
            'password' => $password,
            'password_confirmation' => $password_confirmation,
            'token' => $this->token
        ]));

        $response->assertRedirect(action([SignInController::class,'page']));
//        $password = bcrypt('123456789');
//        $user = UserFactory::new()->create([
//            'email' => 'testing@cutcode.ru',
//            'password' => $password,
//        ]);
//        $token = Password::createToken($user);
//        $request = ResetPasswordFormRequest::factory()->create([
//            'email' => 'testing@cutcode.ru',
//            'password' => '0123456789',
//            'password_confirmation' => '0123456789',
//            'token' => $token
//        ]);
//        $this->assertDatabaseHas('users', [
//            'email' => $request['email']
//        ]);
//
//        $response = $this->post(action([ResetPasswordController::class, 'handle'], $request));
//        $response->assertValid();
//        Event::assertDispatched(PasswordReset::class);
//        $response->assertStatus(302);
//        $response->assertRedirect(route('login'));

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
