<div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-6 text-gray-800">Withdrawal History</h2>
                
                <!-- Filters Section -->
                <div class="mb-6 bg-slate-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="date-filter" class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                            <select id="date-filter" wire:model.live="dateFilter" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-slate-500 focus:border-slate-500 sm:text-sm">
                                <option value="">All Time</option>
                                <option value="today">Today</option>
                                <option value="yesterday">Yesterday</option>
                                <option value="this_week">This Week</option>
                                <option value="last_week">Last Week</option>
                                <option value="this_month">This Month</option>
                                <option value="last_month">Last Month</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="status-filter" wire:model.live="statusFilter" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-slate-500 focus:border-slate-500 sm:text-sm">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <div class="relative rounded-md  p-2 border ">
                                <input type="text" wire:model.live.debounce.300ms="search" id="search" class="focus:ring-slate-500 focus:border-slate-500 block w-full pr-10 sm:text-sm border-gray-300 rounded-md" placeholder="Search by ID, amount...">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Withdrawals List -->
                @if($withdrawals->count() > 0)
                    
                    <!-- Mobile View Cards (visible on small screens) -->
                    <div class="mt-4 grid grid-cols-1 lg:grid-cols-3  gap-4 ">
                        @foreach($withdrawals as $withdrawal)
                            <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
                                <div class="px-4 py-3 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                                    <span class="text-xs text-gray-500">#{{ $withdrawal->id }}</span>
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $withdrawal->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                          ($withdrawal->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $withdrawal->status }}
                                    </span>
                                </div>
                                <div class="p-4">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-sm text-gray-500">Amount:</span>
                                        <span class="text-sm font-medium text-gray-900">{{ number_format($withdrawal->amount) }} Kyats</span>
                                    </div>
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-sm text-gray-500">Commission:</span>
                                        <span class="text-sm font-medium text-red-600"> - {{ number_format($withdrawal->transaction_fees) }} Kyats</span>
                                    </div>
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-sm text-gray-500">Received:</span>
                                        <span class="text-sm font-medium text-gray-900">{{ number_format($withdrawal->real_amount) }} Kyats</span>
                                    </div>
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-sm text-gray-500">Payment:</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $withdrawal->payment->name }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-500">Date:</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $withdrawal->created_at->format('M d, Y h:i A') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6">
                        {{ $withdrawals->links() }}
                    </div>
                    @else
                    <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No withdrawals found</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            You haven't made any withdrawal requests yet.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('withdraw') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-slate-600 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Make a Withdrawal
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>