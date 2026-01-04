<div>
    <p class="text-[10px] text-text-400 uppercase tracking-widest font-black mb-1">
        {{ $label }}
    </p>
    <p class="text-base md:text-lg font-bold {{ ($highlight ?? false) ? 'text-primary-600' : 'text-text-900' }}">
        {{ $value }}
    </p>
</div>