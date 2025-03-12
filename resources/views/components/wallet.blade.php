<div class="">
 <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 flex items-center justify-between">
    <div class="">
        <p class="text-gray-500 dark:text-gray-400 text-sm">Topup Balance</p>
        <p class="text-xl mt-1 font-bold dark:text-white">$ {{ number_format(Auth::user()->wallet, 2) }}</p>
    </div>
    <a href="{{  App\Filament\Resources\MyDepositReosourceResource::getUrl('create') }}" class="">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 w-5 h-5 ">
            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 4.5 15 15m0 0V8.25m0 11.25H8.25" />
          </svg>
        </a>
 </div>
 <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 flex items-center justify-between">
    <div class="">
        <p class="text-gray-500 dark:text-gray-400 text-sm">Receive Balance</p>
        <p class="text-xl mt-1 font-bold dark:text-white">$ {{ number_format(Auth::user()->wallet, 2) }}</p>
    </div>
   <div class="flex gap-4 ">
    <a href="{{  App\Filament\Resources\MyDepositReosourceResource::getUrl('create') }}" class="">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 w-5 h-5 ">
            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 19.5-15-15m0 0v11.25m0-11.25h11.25" />
        </svg>
    </a>
    <a href="{{  App\Filament\Resources\MyDepositReosourceResource::getUrl('create') }}" class="">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 w-5 h-5 ">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
        </svg>
    </a>
 </div>
</div>
