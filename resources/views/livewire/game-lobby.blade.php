<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="bg-gradient-to-r from-slate-700 to-slate-900 px-6 py-4">
            <h2 class="text-xl font-bold text-white">Available Games</h2>
            <p class="text-slate-300 text-sm">Join an existing game or create a new one</p>
        </div>
        
        <div class="p-6">
            @if($games->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($games as $game)
                        @php
                            $playerCount = $game->players->count();
                            $isFull = $playerCount >= $game->max_players;
                        @endphp
                        
                        @if(!$isFull && $game->status === 'waiting' && Auth::user()->wallet->amount >= $game->bet_amount )
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
                                <div class="p-4 border-b border-gray-200 bg-gray-50">
                                    <div class="flex    justify-between items-center">
                                        <span class="text-xs font-medium text-gray-900">Game #{{ $game->room_code }}</span>
                                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                            {{ ucfirst($game->status) }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="p-4">
                                    <div class="flex items-center mb-3">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-slate-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3 w-full">
                                            <p class=" text-end text-xs font-medium text-gray-900">{{ $playerCount }} / {{$game->max_players }} Players</p>
                                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                                <div class="bg-slate-600 h-2 rounded-full" style="width: {{ ($playerCount / $game->max_players) * 100 }}%"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center mb-3">
                                        <svg class="h-5 w-5 text-slate-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm ml-3 font-bold text-slate-600">{{ number_format($game->bet_amount, 0) }} coins</span>
                                    </div>
                                    
                                    <div class="flex items-center mb-3">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-slate-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">Created {{ $game->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    
                                    <button wire:click="joinGame({{ $game->id }})" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-slate-600 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                                        Join Game
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                
                @if($games->filter(function($game) { return $game->players->count() < 6; })->count() === 0)
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">All games are full</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            There are no available games to join at the moment.
                        </p>
                    </div>
                @endif
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No games available</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        There are no games waiting for players at the moment.
                    </p>
                </div>
            @endif
            
        </div>
    </div>
</div>