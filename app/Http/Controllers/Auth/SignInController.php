<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignInFormRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Support\SessionRegenerator;
use function auth;
use function back;
use function redirect;
use function route;
use function view;

class SignInController extends Controller
{
    public function page(): Factory|Application|View|RedirectResponse
    {
//        flash()->info('Test');
//
//        return redirect()->route('home');
        return view('auth.login');
    }


    public function handle(SignInFormRequest $request): RedirectResponse
    {
        if (!auth()->attempt($request->validated())) {
            return back()->withErrors([
                'email' => 'Email не верный',
            ])->onlyInput('email');
        }

        SessionRegenerator::run(fn () => auth()->login(
            auth()->user()
        ));

        return redirect()
            ->intended(route('home'));
    }

    public function logOut(): RedirectResponse
    {
        SessionRegenerator::run(fn () => auth()->logout());

        return redirect()
            ->route('home');
    }

}
