<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} Admin — @yield('title')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    @endif
    @php $t = $themeVars ?? []; @endphp
    <style>
        :root {
            --ma-bg: {{ $t['bg'] ?? '#FDFDFC' }};
            --ma-bg-dark: {{ $t['bg_dark'] ?? '#0a0a0a' }};
            --ma-text: {{ $t['text'] ?? '#1b1b18' }};
            --ma-text-dark: {{ $t['text_dark'] ?? '#EDEDEC' }};
            --ma-primary: {{ $t['primary'] ?? '#6c63ff' }};
            --ma-primary-dark: {{ $t['primary_dark'] ?? '#8b85ff' }};
            --ma-border: {{ $t['border'] ?? '#e3e3e0' }};
            --ma-border-dark: {{ $t['border_dark'] ?? '#3E3E3A' }};
            --ma-radius: {{ $t['radius'] ?? '0.5rem' }};
            --ma-font: {{ $t['font'] ?? '"Instrument Sans", sans-serif' }};
        }
    </style>
</head>
<body class="bg-[var(--ma-bg)] dark:bg-[var(--ma-bg-dark)] text-[var(--ma-text)] dark:text-[var(--ma-text-dark)] min-h-screen" style="font-family: var(--ma-font)">
    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar --}}
        <aside class="w-56 flex-shrink-0 border-r border-[var(--ma-border)] dark:border-[var(--ma-border-dark)] bg-[var(--ma-bg)] dark:bg-[var(--ma-bg-dark)] flex flex-col">
            <div class="px-4 py-5 border-b border-[var(--ma-border)] dark:border-[var(--ma-border-dark)]">
                <a href="/" class="text-sm font-semibold tracking-tight">{{ config('app.name', 'Laravel') }}</a>
                <span class="block text-xs opacity-50 mt-0.5">Admin Panel</span>
            </div>
            <nav class="flex-1 px-2 py-4 space-y-1 text-sm">
                <a href="{{ route('meraki.admin.users.index') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-[var(--ma-radius)] hover:bg-[var(--ma-border)] dark:hover:bg-[var(--ma-border-dark)] transition-colors {{ request()->routeIs('meraki.admin.users.*') ? 'bg-[var(--ma-border)] dark:bg-[var(--ma-border-dark)] font-medium' : '' }}">
                    Users
                </a>
            </nav>
            <div class="px-4 py-4 border-t border-[var(--ma-border)] dark:border-[var(--ma-border-dark)]">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs opacity-60 hover:opacity-100 transition-opacity">Log out</button>
                </form>
            </div>
        </aside>

        {{-- Main content --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="h-14 flex items-center px-6 border-b border-[var(--ma-border)] dark:border-[var(--ma-border-dark)]">
                <h1 class="text-sm font-medium">@yield('title')</h1>
            </header>
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
