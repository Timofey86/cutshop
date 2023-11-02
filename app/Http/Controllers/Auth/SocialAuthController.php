<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Domain\Auth\Models\User;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class SocialAuthController extends Controller
{
    public function redirect(string $driver): RedirectResponse
    {
        try {
            return Socialite::driver($driver)
                ->redirect();
        } catch (Throwable $exception) {
            throw new DomainException('Произошла ошибка или драйвер не поддерживается');
        }

    }

    public function callback(string $driver): RedirectResponse
    {
        if ($driver !== 'github') {
            throw new DomainException('Драйвер не поддерживается');
        }
        $githubUser = Socialite::driver($driver)->user();

        $user = User::query()->updateOrCreate([
            $driver . '_id' => $githubUser->getId(),
        ], [
            'name' => $githubUser->getName(),
            'email' => $githubUser->getEmail(),
            'password' => bcrypt(str()->random(20)),
//            'github_token' => $githubUser->token,
//            'github_refresh_token' => $githubUser->refreshToken,
        ]);

        auth()->login($user);

        return redirect()->intended(route('home'));
    }

}
