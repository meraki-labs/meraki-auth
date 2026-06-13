@props(['label', 'error' => null, 'for' => null])

<div {{ $attributes->merge(['class' => 'mb-4']) }}>
    @if($label)
        <label @if($for) for="{{ $for }}" @endif class="block text-sm font-medium mb-1">{{ $label }}</label>
    @endif
    {{ $slot }}
    @if($error)
        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $error }}</p>
    @endif
</div>
