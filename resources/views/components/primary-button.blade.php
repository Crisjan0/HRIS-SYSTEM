<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#0038a8] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#002a7a] focus:bg-[#002a7a] active:bg-[#001d5a] focus:outline-none focus:ring-2 focus:ring-[#0038a8] focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm hover:shadow-md dark:focus:ring-offset-gray-800']) }}>
    {{ $slot }}
</button>
