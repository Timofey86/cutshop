@extends('layouts.auth')

@section('title', 'Восстановление пароля')
@section('content')
    <x-forms.auth-forms
        title="Восстановление пароля"
        action="{{ route('password.update') }}"
        method="POST"
    >
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <x-forms.text-input
            name="email"
            type="email"
            :isError="$errors->has('email')"
            placeholder="E-mail"
            value="{{ request('email') }}"
            required="true"
        >
        </x-forms.text-input>

        @error('email')
        <x-forms.error>
            {{ $message }}
        </x-forms.error>
        @enderror

        <x-forms.text-input
            name="password"
            type="password"
            :isError="$errors->has('password')"
            placeholder="Пароль"
            required="true"
        >
        </x-forms.text-input>

        @error('password')
        <x-forms.error>
            {{ $message }}
        </x-forms.error>
        @enderror

        <x-forms.text-input
            name="password_confirmation"
            type="password"
            :isError="$errors->has('password_confirmation')"
            placeholder="Повторите Пароль"
            required="true"
        >
        </x-forms.text-input>

        @error('password_confirmation')
        <x-forms.error>
            {{ $message }}
        </x-forms.error>
        @enderror

        <x-forms.primary-button>
            Обновить пароль
        </x-forms.primary-button>

        <x-slot:socialAuth></x-slot:socialAuth>
        <x-slot:buttons></x-slot:buttons>
    </x-forms.auth-forms>
@endsection
