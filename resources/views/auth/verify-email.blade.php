@extends('meraki-auth::layouts.auth')

@section('title', 'Verify Email')

@section('content')
<div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
    <h1 class="text-base font-medium mb-2">Verify your email address</h1>
    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-6">
        Thanks for signing up! Before getting started, please verify your email address by clicking the link we just sent to you.
        If you didn't receive the email, we'll send another.
    </p>

    @if (session('status') === 'verification-link-sent')
        <div class="mb-4 text-sm text-green-600 dark:text-green-400">
            A new verification link has been sent to your email address.
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit"
                class="w-full px-5 py-2 bg-[#1b1b18] dark:bg-[#eeeeec] dark:text-[#1C1C1A] text-white rounded-sm text-sm font-medium hover:bg-black dark:hover:bg-white transition-colors">
            Resend Verification Email
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-3">
        @csrf
        <button type="submit"
                class="w-full px-5 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm text-sm text-[#706f6c] dark:text-[#A1A09A] hover:border-[#1b1b18] dark:hover:border-[#EDEDEC] transition-colors">
            Log out
        </button>
    </form>
</div>
@endsection
