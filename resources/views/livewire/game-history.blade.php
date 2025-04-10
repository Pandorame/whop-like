<div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-6 text-gray-800">Game History</h2>
                
                <!-- Filter Buttons -->
                <div class="mb-6 space-y-4">
                    <div class="flex space-x-2">
                        <button wire:click="setFilter('all')" 
                                class="px-4 py-2 rounded-md {{ $filter === 'all' ? 'bg-slate-700 text-white' : 'bg-slate-100 text-slate-700' }}">
                            All Games
                        </button>
                        <button wire:click="setFilter('wins')" 
                                class="px-4 py-2 rounded-md {{ $filter === 'wins' ? 'bg-green-600 text-white' : 'bg-green-50 text-green-700' }}">
                            Wins
                        </button>
                        <button wire:click="setFilter('losses')" 
                                class="px-4 py-2 rounded-md {{ $filter === 'losses' ? 'bg-red-600 text-white' : 'bg-red-50 text-red-700' }}">
                            Losses
                        </button>
                    </div>
                    
                    <!-- Game ID Filter -->
                    <div class="flex space-x-2 items-center">
                        <label for="game-id" class="text-sm font-medium text-gray-700">Game ID:</label>
                        <div class="relative rounded-md shadow-sm">
                            <input type="text" wire:model.defer="gameId" id="game-id" 
                                class="focus:ring-indigo-500 p-2 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" 
                                placeholder="Enter Game ID">
                        </div>
                        <button wire:click="filterByGameId" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Filter
                        </button>
                        @if($gameIdFilter)
                            <button wire:click="clearGameIdFilter" 
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Clear
                            </button>
                            <span class="text-sm text-gray-500">Showing Game #{{ $gameIdFilter }}</span>
                        @endif
                    </div>
                </div>
                
                <!-- History Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Game
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Round
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cards
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Score
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Bet Amount
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Win Amount
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Result
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($this->history as $record)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Game #{{ $record->round->game->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Round #{{ $record->round->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex space-x-1">
                                            @foreach ($record->cards as $card)
                                                <div class="w-10 h-14 bg-white rounded-lg shadow-md border border-gray-200 flex items-center justify-center relative overflow-hidden">
                                                    <div class="text-center">
                                                        <div class="{{ in_array(substr($card, -1), ['H', 'D']) ? 'text-red-600' : 'text-slate-800' }} text-xs font-bold">
                                                            {{ substr($card, 0, -1) }}
                                                        </div>
                                                        <div class="{{ in_array(substr($card, -1), ['H', 'D']) ? 'text-red-600' : 'text-slate-800' }} text-xs">
                                                            @switch(substr($card, -1))
                                                                @case('H')
                                                                    ♥
                                                                    @break
                                                                @case('D')
                                                                    ♦
                                                                    @break
                                                                @case('C')
                                                                    ♣
                                                                    @break
                                                                @case('S')
                                                                    ♠
                                                                    @break
                                                            @endswitch
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $record->score }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($record->amount) }} Kyats
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($record->win_amount) }} Kyats
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($record->isWin())
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Won {{ number_format($record->win_amount) }} Kyats
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Lost
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $record->created_at->format('M d, Y h:i A') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        No game history found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-4">
                    {{-- {{ $history->links() }} --}}
                </div>
                
                <!-- Summary Statistics -->
                <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Summary</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white p-4 rounded-lg shadow">
                            <p class="text-sm text-gray-500">Total Bet Amount</p>
                            <p class="text-xl font-bold text-gray-900">{{ number_format($totalBetAmount) }} Kyats</p>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow">
                            <p class="text-sm text-green-500">Total Win Amount</p>
                            <p class="text-xl font-bold text-green-600">{{ number_format($totalWinAmount) }} Kyats</p>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow">
                            <p class="text-sm {{ $netProfit >= 0 ? 'text-green-500' : 'text-red-500' }}">Net Profit/Loss</p>
                            <p class="text-xl font-bold {{ $netProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $netProfit >= 0 ? '+' : '' }}{{ number_format($netProfit) }} Kyats
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
