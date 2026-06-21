@props(['type' => 'info'])

@php
$colors = [
    'success' => 'text-green-600 dark:text-green-400',
    'error'   => 'text-red-600 dark:text-red-400',
    'warning' => 'text-yellow-600 dark:text-yellow-400',
    'info'    => 'text-blue-600 dark:text-blue-400',
];
$color = $colors[$type] ?? $colors['info'];
@endphp

<div {{ $attributes->merge(['class' => 'mb-4 text-sm ' . $color]) }}>
    {{ $slot }}
</div>
