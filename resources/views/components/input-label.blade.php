@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-background-700']) }}>
    {{ $value ?? $slot }}
</label>
