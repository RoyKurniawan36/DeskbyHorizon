<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-accent-600 border border-transparent rounded-md font-semibold text-xs text-text-50 uppercase tracking-widest hover:bg-accent-500 active:bg-accent-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
