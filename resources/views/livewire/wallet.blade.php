<div>
    @php use App\Helpers\Enums\TransitionType; @endphp
    <div class="max-w-md mx-auto">
        <!-- Main Wallet Card -->
        <div class="bg-gradient-to-r from-slate-700 to-slate-900 rounded-xl shadow-lg overflow-hidden">
            <!-- Card Header -->
            <div class="px-6 py-5">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-medium text-white">My Wallet</h3>
                        <p class="text-slate-300 text-sm">Available Balance</p>
                    </div>
                    <div class="bg-white/10 p-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Balance Display -->
            <div class="px-6 pb-6">
                <div class="flex items-baseline">
                    <span class="text-3xl font-bold text-white">{{ number_format($balance) }}</span>
                    <span class="ml-1 text-slate-300">Kyats</span>
                </div>
            </div>

            <!-- Card Footer with Actions -->
            <div class="bg-slate-800 px-6 py-4">
                <div class="flex justify-between">
                    <a href="{{ route('deposit') }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-slate-600 hover:bg-slate-700 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Deposit
                    </a>

                    <a href="{{ route('withdraw') }}"  class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-slate-600 hover:bg-slate-700 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                        Withdraw
                    </a>

                    <button  wire:click="toggleTransactions" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-slate-600 hover:bg-slate-700 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        History
                    </button>
                </div>
            </div>
        </div>

        <!-- Recent Transactions (Optional) -->
        @if($showTransactions && count($transactions) > 0)
            <div class="mt-4 bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100">
                    <h3 class="text-md font-medium text-slate-700">Recent Transactions</h3>
                </div>
                <ul class="divide-y divide-slate-100">
                    @foreach($transactions as $transaction)
                        <li class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 {{ $transaction->type ===  'cash_in' ? 'bg-green-100' : 'bg-red-100' }} p-2 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 {{ $transaction->type ===  'cash_in' ? 'text-green-600' : 'text-red-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            @if($transaction->type ===  'cash_in')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                            @endif
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-slate-700">{{ ucfirst($transaction->type)  }}</p>
                                        <p class="text-xs text-slate-500">{{ $transaction->created_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium {{ $transaction->type ===  'cash_in' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaction->type ===  'cash_in' ? '+' : '-' }} {{ number_format($transaction->amount) }} Kyats
                                    </p>
                                    <p class="text-xs text-slate-500">{{ $transaction->status }}</p>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div>
</div>
