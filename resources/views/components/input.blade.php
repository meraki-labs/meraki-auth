@props(['type' => 'text', 'name', 'error' => null])

<input
    type="{{ $type }}"
    name="{{ $name }}"
    {{ $attributes->merge([
        'class' => 'w-full px-3 py-2 border ' . ($error ? 'border-red-500' : 'border-[var(--ma-border)] dark:border-[var(--ma-border-dark)]') . ' rounded-[var(--ma-radius)] bg-transparent text-sm focus:outline-none focus:ring-1 focus:ring-[var(--ma-text)] dark:focus:ring-[var(--ma-text-dark)]'
    ]) }}
/>
