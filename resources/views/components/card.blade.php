@props(['title' => null])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-[var(--ma-radius)] p-6']) }}>
    @if($title)
        <h1 class="text-base font-medium mb-6">{{ $title }}</h1>
    @endif
    {{ $slot }}
</div>
