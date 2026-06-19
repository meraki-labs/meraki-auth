@extends('meraki-auth::admin.layouts.admin')

@section('title', 'Users')

@section('content')
<div class="overflow-x-auto rounded-[var(--ma-radius)] border border-[var(--ma-border)] dark:border-[var(--ma-border-dark)]">
    <table class="w-full text-sm">
        <thead class="bg-[var(--ma-border)] dark:bg-[var(--ma-border-dark)] text-xs uppercase tracking-wider opacity-60">
            <tr>
                <th class="px-4 py-3 text-left font-medium">Name</th>
                <th class="px-4 py-3 text-left font-medium">Email</th>
                <th class="px-4 py-3 text-left font-medium">Verified</th>
                <th class="px-4 py-3 text-left font-medium">Registered</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[var(--ma-border)] dark:divide-[var(--ma-border-dark)]">
            @forelse ($users as $user)
                <tr class="hover:bg-[var(--ma-border)] dark:hover:bg-[var(--ma-border-dark)] transition-colors">
                    <td class="px-4 py-3">{{ $user->name }}</td>
                    <td class="px-4 py-3">{{ $user->email }}</td>
                    <td class="px-4 py-3">
                        @if ($user->email_verified_at)
                            <span class="text-xs text-green-600 dark:text-green-400">Verified</span>
                        @else
                            <span class="text-xs opacity-40">Unverified</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 opacity-60">{{ $user->created_at->format('Y-m-d') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center opacity-40">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($users->hasPages())
    <div class="mt-4">
        {{ $users->links() }}
    </div>
@endif
@endsection
