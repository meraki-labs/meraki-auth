@extends('meraki-auth::layouts.auth')

@section('title', 'Reset Password')

@section('content')
<x-meraki-auth::card title="Set a new password">
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <x-meraki-auth::form-field label="Email" for="email" :error="$errors->first('email')">
            <x-meraki-auth::input id="email" type="email" name="email" value="{{ old('email', $email ?? '') }}" required autofocus :error="$errors->first('email')" />
        </x-meraki-auth::form-field>

        <x-meraki-auth::form-field label="New Password" for="password" :error="$errors->first('password')">
            <x-meraki-auth::input id="password" type="password" name="password" required :error="$errors->first('password')" />
        </x-meraki-auth::form-field>

        <x-meraki-auth::form-field label="Confirm Password" for="password_confirmation" class="mb-6">
            <x-meraki-auth::input id="password_confirmation" type="password" name="password_confirmation" required />
        </x-meraki-auth::form-field>

        <x-meraki-auth::button type="submit">Reset Password</x-meraki-auth::button>
    </form>
</x-meraki-auth::card>
@endsection
