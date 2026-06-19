@props(['variant' => 'primary', 'loading' => false])

@php
$base = 'w-full px-5 py-2 rounded-[var(--ma-radius)] text-sm font-medium transition-colors';
$variants = [
    'primary'   => 'bg-[var(--ma-primary)] dark:bg-[var(--ma-primary-dark)] text-white hover:opacity-90',
    'secondary' => 'border border-[var(--ma-border)] dark:border-[var(--ma-border-dark)] text-[var(--ma-text)] dark:text-[var(--ma-text-dark)] hover:border-[var(--ma-text)] dark:hover:border-[var(--ma-text-dark)]',
];
$classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

<button {{ $attributes->merge(['class' => $classes]) }} @if($loading) disabled @endif>
    {{ $slot }}
</button>
