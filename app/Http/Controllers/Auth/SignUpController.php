<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignUpFormRequest;
use Domain\Auth\Contracts\RegisterNewUserContract;
use Domain\Auth\DTOs\NewUserDTO;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use function redirect;
use function route;
use function view;

class SignUpController extends Controller
{
    public function page(): Factory|Application|View|RedirectResponse
    {
        return view('auth.sign-up');
    }


    public function handle(SignUpFormRequest $request, RegisterNewUserContract $action): RedirectResponse
    {
        $action(NewUserDTO::fromRequest($request));

        return redirect()
            ->intended(route('home'));
    }

}
