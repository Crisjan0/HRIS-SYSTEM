@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700/50 bg-white/50 dark:bg-gray-900/40 dark:text-gray-300 focus:border-[#0038a8] dark:focus:border-[#0038a8] focus:ring-[#0038a8] dark:focus:ring-[#0038a8] rounded-xl shadow-sm backdrop-blur-md transition-all duration-300']) }}>
