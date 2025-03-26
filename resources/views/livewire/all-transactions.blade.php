<div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <h2 class="text-2xl font-bold text-gray-800">All Transactions</h2>
                    
                    <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                        <!-- Search -->
                        <div class="relative flex-grow sm:flex-grow-0">
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search transactions..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-500 focus:border-transparent">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>

                        <!-- Filter -->
                        <select wire:model.live="filter" class="border border-gray-300 rounded-lg py-2 pl-3 pr-10 text-base focus:outline-none focus:ring-2 focus:ring-slate-500 focus:border-transparent">
                            <option value="">All Types</option>
                            @foreach(\App\Helpers\Enums\TransitionType::cases() as $type)
                                <option value="{{ $type->value }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Sorting Options -->
                <div class="flex flex-wrap gap-4 mb-6">
                    <button wire:click="sortBy('id')" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $sortField === 'id' ? 'bg-slate-100 text-slate-900' : 'text-slate-500 hover:text-slate-900' }}">
                        ID {!! $sortField === 'id' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' !!}
                    </button>
                    <button wire:click="sortBy('amount')" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $sortField === 'amount' ? 'bg-slate-100 text-slate-900' : 'text-slate-500 hover:text-slate-900' }}">
                        Amount {!! $sortField === 'amount' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' !!}
                    </button>
                    <button wire:click="sortBy('type')" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $sortField === 'type' ? 'bg-slate-100 text-slate-900' : 'text-slate-500 hover:text-slate-900' }}">
                        Type {!! $sortField === 'type' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' !!}
                    </button>
                    <button wire:click="sortBy('created_at')" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $sortField === 'created_at' ? 'bg-slate-100 text-slate-900' : 'text-slate-500 hover:text-slate-900' }}">
                        Date {!! $sortField === 'created_at' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' !!}
                    </button>
                </div>

                <!-- Transactions Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($transactions as $transaction)
                        <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden border border-gray-100">
                            <div class="p-4">
                                <div class="flex justify-between items-start mb-3">
                                    <span class="text-sm text-gray-500">#{{ $transaction->id }}</span>
                                    <span class="font-medium text-lg {{ $transaction->amount > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaction->amount > 0 ? '+' : '' }}{{ number_format($transaction->amount, 2) }}
                                    </span>
                                </div>
                                
                                <div class="flex items-center justify-between mb-3">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                                        @switch($transaction->type)
                                            @case('cash_in')
                                                bg-green-100 text-green-800
                                                @break
                                            @case('cash_out')
                                                bg-red-100 text-red-800
                                                @break
                                            @case('bet')    
                                                bg-blue-100 text-blue-800
                                                @break
                                            @default
                                                bg-gray-100 text-gray-800
                                        @endswitch">
                                        {{ $transaction->type }}
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        {{ $transaction->created_at->format('M d, Y H:i') }}
                                    </span>
                                </div>

                                <div class="border-t border-gray-100 pt-3">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $transaction->transationable->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $transaction->transationable->email }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full p-6 text-center text-gray-500 bg-white rounded-lg border border-gray-100">
                            No transactions found
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>