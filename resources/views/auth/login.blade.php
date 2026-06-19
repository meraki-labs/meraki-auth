@extends('meraki-auth::layouts.auth')

@section('title', 'Log in')

@section('content')
<x-meraki-auth::card title="Log in to your account">
    @if (session('status'))
        <x-meraki-auth::alert type="success">{{ session('status') }}</x-meraki-auth::alert>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <x-meraki-auth::form-field label="Email" for="email" :error="$errors->first('email')">
            <x-meraki-auth::input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus :error="$errors->first('email')" />
        </x-meraki-auth::form-field>

        <x-meraki-auth::form-field label="Password" for="password" :error="$errors->first('password')">
            <x-meraki-auth::input id="password" type="password" name="password" required :error="$errors->first('password')" />
        </x-meraki-auth::form-field>

        <div class="flex items-center justify-between mb-6">
            <label class="flex items-center gap-2 text-sm opacity-60 cursor-pointer">
                <input type="checkbox" name="remember" class="rounded-[var(--ma-radius)] border-[var(--ma-border)] dark:border-[var(--ma-border-dark)]">
                Remember me
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-sm underline underline-offset-4 opacity-60 hover:opacity-100">
                    Forgot password?
                </a>
            @endif
        </div>

        <x-meraki-auth::button type="submit">Log in</x-meraki-auth::button>
    </form>
</x-meraki-auth::card>

@if (Route::has('register'))
    <p class="mt-4 text-center text-sm opacity-60">
        Don't have an account?
        <a href="{{ route('register') }}" class="opacity-100 underline underline-offset-4">Register</a>
    </p>
@endif
@endsection
