<div class="">
    <div class="bg-white dark:bg-gray-800 pt-4 rounded-lg shadow-md px-4 flex items-center justify-between">
       <div class="">
           <p class="text-gray-500 dark:text-gray-400 text-sm">Topup Balance</p>
           <p class="text-xl mt-1 font-bold dark:text-white">$ {{ number_format($admin->wallet, 0) }}</p>
       </div>
       <a href="{{  App\Filament\Resources\MyDepositReosourceResource::getUrl('create') }}" class="">
           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 w-5 h-5 ">
               <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 4.5 15 15m0 0V8.25m0 11.25H8.25" />
             </svg>
           </a>
           <div class="ms-4">
            {{ $this->transferAction }}
           </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 flex items-center justify-between">
       <div class="">
           <p class="text-gray-500 dark:text-gray-400 text-sm">Receive Balance</p>
           <p class="text-xl mt-1 font-bold dark:text-white">$ {{ number_format($admin->receive_wallet, 0) }}</p>
       </div>
      <div class="flex justify-end">
       <div class="me-4">
        {{ $this->withdrawAction }}
       </div>
    
        {{ $this->exchangeAction }}
        <x-filament-actions::modals />
      </div>
    </div>
</div>
