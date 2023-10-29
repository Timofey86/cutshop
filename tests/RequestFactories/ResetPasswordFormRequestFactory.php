<?php

namespace Tests\RequestFactories;

use Worksome\RequestFactories\RequestFactory;

class ResetPasswordFormRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
//            'token' => 'required',
//            'email' => 'required|email',
//            'password' => 'required|min:8|confirmed',
        ];
    }
}
