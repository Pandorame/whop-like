<div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-6 text-gray-800">Earning History</h2>
                
                <!-- Filters Section -->
                <div class="hidden lg:block  mb-6 bg-slate-50 p-4 rounded-lg">
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
                                <option value="completed">Completed</option>
                                <option value="pending">Pending</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <div class="relative rounded-md shadow-sm p-2 ">
                                <input type="text" wire:model.live.debounce.300ms="search" id="search" class="focus:outline-0 focus:ring-0  block w-full pr-10 sm:text-sm border-gray-300 rounded-md" placeholder="Search by ID, amount...">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Stats Section -->
                <div class="hidden lg:grid  grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-slate-100 rounded-md p-3">
                                    <svg class="h-6 w-6 text-slate-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Earning</dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-gray-900">{{ number_format($totalDeposits) }} Kyats</div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                                    <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Completed Earning</dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-gray-900">{{ number_format($completedDeposits) }} Kyats</div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                                    <svg class="h-6 w-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Pending Earning</dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-gray-900">{{ number_format($pendingDeposits) }} Kyats</div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Deposits Cards -->
                @if($deposits->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                        @foreach($deposits as $deposit)
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-all duration-300 border border-gray-100">
                            <!-- Card Header with Status Badge -->
                            <div class="relative">
                                <div class="absolute top-3 right-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $deposit->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                          ($deposit->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $deposit->status }}
                                    </span>
                                </div>
                                <div class="h-2 bg-gradient-to-r 
                                    {{ $deposit->status === 'completed' ? 'from-green-400 to-green-500' : 
                                      ($deposit->status === 'pending' ? 'from-yellow-400 to-yellow-500' : 'from-red-400 to-red-500') }}">
                                </div>
                            </div>
                            
                            <!-- Card Content -->
                            <div class="p-5">
                                <!-- Amount (Highlighted) -->
                                <div class="mb-4 text-center">
                                    <p class="text-xs text-gray-500 mb-1">Earning AMOUNT</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ number_format($deposit->amount) }} <span class="text-sm font-normal">Kyats</span></p>
                                </div>
                                
                                <!-- Divider -->
                                <div class="border-t border-gray-100 my-4"></div>
                                
                                <!-- Info Grid -->
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- ID -->
                                    <div class="">
                                        <p class="text-xs text-gray-500 mb-1">ID</p>
                                        <p class="text-sm font-medium text-gray-800">#{{ $deposit->id }}</p>
                                    </div>
                                    
                                    <!-- Date -->
                                    <div class="">
                                        <p class="text-xs text-gray-500 mb-1">DATE</p>
                                        <p class="text-sm font-medium text-gray-800">{{ $deposit->created_at->format('M d, Y') }}</p>
                                    </div>
                                    
                                    <!-- Agent -->
                                    <div class="">
                                        <p class="text-xs text-gray-500 mb-1">AGENT</p>
                                        <p class="text-sm font-medium text-gray-800 truncate">{{ $deposit->toAdmin->name ?? 'Unknown Agent' }}</p>
                                    </div>
                                    
                                    <!-- Time -->
                                    <div class="">
                                        <p class="text-xs text-gray-500 mb-1">Payment</p>
                                        <div class="">
                                            <p class="text-sm font-medium text-gray-900">{{ $deposit->paymentAccount->name ?? 'Unknown Account' }}</p>
                                            <p class="text-xs mt-1  text-gray-500">{{ $deposit->payment->name ?? 'Unknown Payment' }}</p>
                                        </div>
                                    </div>
                                </div>
                               
                                
                                <!-- View Details Button (if needed) -->
                                @if($deposit->slip)
                                <div class="mt-4">
                                    <a href="{{ asset('storage/'.$deposit->slip) }}" class="w-full flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-slate-600 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                                        <svg class="h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View Payment Slip
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-6 flex justify-center">
                        {{ $deposits->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
