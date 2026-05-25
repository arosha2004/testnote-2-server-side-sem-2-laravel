@props([
    'size' => 'md',
    'showText' => true,
    'href' => null,
])

@php
    $sizes = [
        'xs' => ['img' => 'h-8 w-8', 'text' => 'text-base'],
        'sm' => ['img' => 'h-9 w-9', 'text' => 'text-lg'],
        'md' => ['img' => 'h-10 w-10', 'text' => 'text-lg'],
        'lg' => ['img' => 'h-14 w-14', 'text' => 'text-xl'],
        'xl' => ['img' => 'h-20 w-20', 'text' => 'text-2xl'],
        '2xl' => ['img' => 'h-28 w-28', 'text' => 'text-3xl'],
    ];
    $s = $sizes[$size] ?? $sizes['md'];
    $tag = $href ? 'a' : 'div';
@endphp

<{{ $tag }}
    @if($href) href="{{ $href }}" @endif
    {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5']) }}
>
    <img
        src="{{ asset('images/logo.png') }}"
        alt="NoteHub logo"
        class="{{ $s['img'] }} shrink-0 rounded-full object-cover shadow-sm ring-2 ring-slate-200/80 dark:ring-slate-700 transition-colors"
    >
    @if($showText)
        <span class="{{ $s['text'] }} font-bold tracking-tight text-brand-navy dark:text-white transition-colors">NoteHub</span>
    @endif
</{{ $tag }}>
