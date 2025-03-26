<div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-6 text-gray-800">Transaction History</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Deposit History -->
                    <a href="{{ route('deposit.history') }}" class="group">
                        <div class="border border-gray-200 rounded-lg p-5 transition-all duration-200 hover:shadow-md hover:border-slate-400 group-hover:bg-slate-50">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-slate-100 p-3 rounded-full group-hover:bg-slate-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900 group-hover:text-slate-700">Deposit History</h3>
                                    <p class="text-sm text-gray-500 group-hover:text-slate-600">View all deposit transactions</p>
                                </div>
                                <div class="ml-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-hover:text-slate-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Withdrawal History -->
                    <a href="{{ route('withdraw.history') }}" class="group">
                        <div class="border border-gray-200 rounded-lg p-5 transition-all duration-200 hover:shadow-md hover:border-slate-400 group-hover:bg-slate-50">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-slate-100 p-3 rounded-full group-hover:bg-slate-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900 group-hover:text-slate-700">Withdrawal History</h3>
                                    <p class="text-sm text-gray-500 group-hover:text-slate-600">View all withdrawal transactions</p>
                                </div>
                                <div class="ml-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-hover:text-slate-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Game History -->
                    {{-- <a href="{{ route('game.history') }}" class="group">
                        <div class="border border-gray-200 rounded-lg p-5 transition-all duration-200 hover:shadow-md hover:border-slate-400 group-hover:bg-slate-50">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-slate-100 p-3 rounded-full group-hover:bg-slate-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900 group-hover:text-slate-700">Game History</h3>
                                    <p class="text-sm text-gray-500 group-hover:text-slate-600">View your game rounds and results</p>
                                </div>
                                <div class="ml-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-hover:text-slate-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a> --}}



                    <!-- Bet History -->
                    <a href="{{ route('withdraw.history') }}" class="group">
                        <div class="border border-gray-200 rounded-lg p-5 transition-all duration-200 hover:shadow-md hover:border-slate-400 group-hover:bg-slate-50">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-slate-100 p-3 rounded-full group-hover:bg-slate-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900 group-hover:text-slate-700">Bet History</h3>
                                    <p class="text-sm text-gray-500 group-hover:text-slate-600">View all betting transactions</p>
                                </div>
                                <div class="ml-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-hover:text-slate-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- All Transactions -->
                    <a href="{{ route('all.transactions') }}" class="group">
                        <div class="border border-gray-200 rounded-lg p-5 transition-all duration-200 hover:shadow-md hover:border-slate-400 group-hover:bg-slate-50">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-slate-100 p-3 rounded-full group-hover:bg-slate-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900 group-hover:text-slate-700">All Transactions</h3>
                                    <p class="text-sm text-gray-500 group-hover:text-slate-600">View complete transaction history</p>
                                </div>
                                <div class="ml-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-hover:text-slate-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>