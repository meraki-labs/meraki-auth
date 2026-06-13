@extends('meraki-auth::layouts.auth')

@section('title', 'Forgot Password')

@section('content')
<div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
    <h1 class="text-base font-medium mb-2">Forgot your password?</h1>
    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-6">
        Enter your email address and we'll send you a link to reset your password.
    </p>

    @if (session('status'))
        <div class="mb-4 text-sm text-green-600 dark:text-green-400">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-6">
            <label for="email" class="block text-sm font-medium mb-1">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full px-3 py-2 border {{ $errors->has('email') ? 'border-red-500' : 'border-[#e3e3e0] dark:border-[#3E3E3A]' }} rounded-sm bg-transparent text-sm focus:outline-none focus:ring-1 focus:ring-[#1b1b18] dark:focus:ring-[#EDEDEC]">
            @error('email')
                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full px-5 py-2 bg-[#1b1b18] dark:bg-[#eeeeec] dark:text-[#1C1C1A] text-white rounded-sm text-sm font-medium hover:bg-black dark:hover:bg-white transition-colors">
            Send Reset Link
        </button>
    </form>
</div>

@if (Route::has('login'))
    <p class="mt-4 text-center text-sm text-[#706f6c] dark:text-[#A1A09A]">
        <a href="{{ route('login') }}" class="text-[#1b1b18] dark:text-[#EDEDEC] underline underline-offset-4">Back to log in</a>
    </p>
@endif
@endsection
