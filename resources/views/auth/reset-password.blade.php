@extends('meraki-auth::layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
    <h1 class="text-base font-medium mb-6">Set a new password</h1>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium mb-1">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $email ?? '') }}" required autofocus
                   class="w-full px-3 py-2 border {{ $errors->has('email') ? 'border-red-500' : 'border-[#e3e3e0] dark:border-[#3E3E3A]' }} rounded-sm bg-transparent text-sm focus:outline-none focus:ring-1 focus:ring-[#1b1b18] dark:focus:ring-[#EDEDEC]">
            @error('email')
                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium mb-1">New Password</label>
            <input id="password" type="password" name="password" required
                   class="w-full px-3 py-2 border {{ $errors->has('password') ? 'border-red-500' : 'border-[#e3e3e0] dark:border-[#3E3E3A]' }} rounded-sm bg-transparent text-sm focus:outline-none focus:ring-1 focus:ring-[#1b1b18] dark:focus:ring-[#EDEDEC]">
            @error('password')
                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium mb-1">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                   class="w-full px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-transparent text-sm focus:outline-none focus:ring-1 focus:ring-[#1b1b18] dark:focus:ring-[#EDEDEC]">
        </div>

        <button type="submit"
                class="w-full px-5 py-2 bg-[#1b1b18] dark:bg-[#eeeeec] dark:text-[#1C1C1A] text-white rounded-sm text-sm font-medium hover:bg-black dark:hover:bg-white transition-colors">
            Reset Password
        </button>
    </form>
</div>
@endsection
