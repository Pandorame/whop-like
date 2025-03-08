<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 flex items-center justify-between">
    <div class="">
            <p class="text-gray-500 dark:text-gray-400 text-sm">Current Balance</p>
            <p class="text-xl mt-1 font-bold dark:text-white">$ {{ number_format(Auth::user()->wallet, 2) }}</p>
    </div>
    <div class="text-green-500">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </div>
</div>
