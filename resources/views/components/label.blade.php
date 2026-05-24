@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700 dark:text-slate-300 transition-colors']) }}>
    {{ $value ?? $slot }}
</label>
