<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-background-800 border border-transparent rounded-md font-semibold text-xs text-text-50 uppercase tracking-widest hover:bg-background-700 focus:bg-background-700 active:bg-background-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
