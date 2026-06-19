@extends('meraki-auth::layouts.auth')

@section('title', 'Verify Email')

@section('content')
<x-meraki-auth::card>
    <h1 class="text-base font-medium mb-2">Verify your email address</h1>
    <p class="text-sm opacity-60 mb-6">
        Thanks for signing up! Before getting started, please verify your email address by clicking the link we just sent to you.
        If you didn't receive the email, we'll send another.
    </p>

    @if (session('status') === 'verification-link-sent')
        <x-meraki-auth::alert type="success">
            A new verification link has been sent to your email address.
        </x-meraki-auth::alert>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <x-meraki-auth::button type="submit">Resend Verification Email</x-meraki-auth::button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-3">
        @csrf
        <x-meraki-auth::button type="submit" variant="secondary">Log out</x-meraki-auth::button>
    </form>
</x-meraki-auth::card>
@endsection
