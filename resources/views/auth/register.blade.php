@extends('meraki-auth::layouts.auth')

@section('title', 'Register')

@section('content')
<x-meraki-auth::card title="Create an account">
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <x-meraki-auth::form-field label="Name" for="name" :error="$errors->first('name')">
            <x-meraki-auth::input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus :error="$errors->first('name')" />
        </x-meraki-auth::form-field>

        <x-meraki-auth::form-field label="Email" for="email" :error="$errors->first('email')">
            <x-meraki-auth::input id="email" type="email" name="email" value="{{ old('email') }}" required :error="$errors->first('email')" />
        </x-meraki-auth::form-field>

        <x-meraki-auth::form-field label="Password" for="password" :error="$errors->first('password')">
            <x-meraki-auth::input id="password" type="password" name="password" required :error="$errors->first('password')" />
        </x-meraki-auth::form-field>

        <x-meraki-auth::form-field label="Confirm Password" for="password_confirmation" class="mb-6">
            <x-meraki-auth::input id="password_confirmation" type="password" name="password_confirmation" required />
        </x-meraki-auth::form-field>

        <x-meraki-auth::button type="submit">Register</x-meraki-auth::button>
    </form>
</x-meraki-auth::card>

@if (Route::has('login'))
    <p class="mt-4 text-center text-sm opacity-60">
        Already have an account?
        <a href="{{ route('login') }}" class="opacity-100 underline underline-offset-4">Log in</a>
    </p>
@endif
@endsection
