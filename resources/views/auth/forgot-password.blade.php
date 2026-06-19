@extends('meraki-auth::layouts.auth')

@section('title', 'Forgot Password')

@section('content')
<x-meraki-auth::card>
    <h1 class="text-base font-medium mb-2">Forgot your password?</h1>
    <p class="text-sm opacity-60 mb-6">
        Enter your email address and we'll send you a link to reset your password.
    </p>

    @if (session('status'))
        <x-meraki-auth::alert type="success">{{ session('status') }}</x-meraki-auth::alert>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <x-meraki-auth::form-field label="Email" for="email" :error="$errors->first('email')" class="mb-6">
            <x-meraki-auth::input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus :error="$errors->first('email')" />
        </x-meraki-auth::form-field>

        <x-meraki-auth::button type="submit">Send Reset Link</x-meraki-auth::button>
    </form>
</x-meraki-auth::card>

@if (Route::has('login'))
    <p class="mt-4 text-center text-sm opacity-60">
        <a href="{{ route('login') }}" class="opacity-100 underline underline-offset-4">Back to log in</a>
    </p>
@endif
@endsection
