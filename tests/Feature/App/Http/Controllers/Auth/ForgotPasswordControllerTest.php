<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\ForgotPasswordFormRequest;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function action;

class ForgotPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    private function testingCredentials(): array
    {
        return [
          'email' => 'testing@cutcode.ru'
        ];
    }

    /**
     * @test
     * @return void
     */
    public function it_page_success(): void
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
    public function it_handle_success()
    {
        $user = UserFactory::new()->create([
            'email' => 'testing@cutcode.ru'
        ]);

        $request = ForgotPasswordFormRequest::factory()->create([
            'email' => $user->email
        ]);

        $response = $this->post(action([ForgotPasswordController::class, 'handle'], $request));

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
        $response->assertInvalid();
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
    }

}
