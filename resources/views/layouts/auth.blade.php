<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} — @yield('title')</title>
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
<body class="bg-[var(--ma-bg)] dark:bg-[var(--ma-bg-dark)] text-[var(--ma-text)] dark:text-[var(--ma-text-dark)] min-h-screen flex flex-col items-center justify-center p-6" style="font-family: var(--ma-font)">
    <div class="w-full max-w-sm">
        <div class="text-center mb-8">
            <a href="/" class="text-base font-semibold tracking-tight">{{ config('app.name', 'Laravel') }}</a>
        </div>

        @yield('content')
    </div>
</body>
</html>
