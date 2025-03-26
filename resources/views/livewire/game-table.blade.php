<div id="box" x-data="{
    playerCards: {},
    selectedCards: {},
    countdown: {{ $countdown ?? 60 }},
    timer: null,
    winners: [],
    showWinnersModal: false,
    startCountdown(seconds) {
        clearInterval(this.timer);
        this.countdown = seconds;
        this.timer = setInterval(() => {
            this.countdown--;

            if (this.countdown <= 0) {
                clearInterval(this.timer);
                console.log('Countdown reached 0');
                // Auto-bet when countdown reaches 0
                if (!{{ $hasPlacedBet ? 'true' : 'false' }}) {
                    $wire.autoBet()
                    console.log('calling');
                } else {
                    console.log('placed');
                }
            }
        }, 1000);
    },
    init() {
        @foreach($players as $player)
        @php
            $playerRound = null;
            foreach($playerCards as $card) {
                if($card->player_id == $player->id) {
                    $playerRound = $card;
                    break;
                }
            }
        @endphp

        @if($playerRound && $playerRound->card_1 != null)
            this.playerCards[{{ $player->id }}] = {
                cards: ['{{ $playerRound->card_1 }}', '{{ $playerRound->card_2 }}'],
                score: {{ $playerRound->score ?? 0 }}
            };
            this.selectedCards[{{ $player->id }}] = {
                cards: ['{{ $playerRound->card_1 }}', '{{ $playerRound->card_2 }}'],
                playerName: '{{ $playerRound->player->user->name ?? 'UNKNOW' }}'
            };
        @endif
    @endforeach

        // Listen for countdown start event from the server
        Echo.channel('game.{{ $game->id }}')
            .listen('CountdownUpdated', (e) => {
                this.startCountdown(e.countdown);
            })
            .listen('CardSelected', (e) => {
                if (!this.selectedCards[e.playerId]) {
                    this.selectedCards[e.playerId] = [];
                }
                this.selectedCards[e.playerId].push(e.card);

                // Show notification
                console.log('Card selected:', this.selectedCards);

                // Refresh the component to show the new selection
                {{-- @this.call('$refresh'); --}}
            })
            .listen('CardsAssigned', (e) => {
                // Clear selected cards
                {{-- this.selectedCards[e.playerId] = []; --}}

                // Update player cards
                this.playerCards[e.playerId] = {
                    cards: e.cards,
                    score: e.score
                };


                console.log('Card Assigned:', this.playerCards);

                // Refresh the component to show the new cards
                {{-- @this.call('$refresh'); --}}
            })
            .listen('Test', (e) => {
                console.log('TEST EVENT RECEIVED:', e);
                console.log('TEST DATA:', e.data);
                console.log('TEST GAME ID:', e.gameId);
            })
            .listen('NewRoundStarted', (d) => {
                // Show winners modal if there are winners
                console.log('Winners:', d);
                console.log('Winners:', d.winners);
                if (d.winners && d.winners.length > 0) {
                    console.log('Winners:', d.winners);
                    this.winners = d.winners;
                    this.showWinnersModal = true;
                    
                    // Auto-close after 5 seconds
                    setTimeout(() => {
                        this.showWinnersModal = false;
                         // Refresh the component to load the new round
                        this.playerCards = {};
                        this.selectedCards = {};

                        $wire.resetData();

                    }, 5000);
                }
                
                // Show notification
                console.log('New Round Started');
            });
    }
}" class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    
    <!-- Winners Modal -->
    <div x-show="showWinnersModal" 
         x-cloak
         class="fixed inset-0 bg-slate-100 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full">
            <h2 class="text-2xl font-bold text-center mb-4 text-slate-700">Round Winners</h2>
            <div class="space-y-3">
                <template x-for="winner in winners" :key="winner.player_id">
                    <div class="flex justify-between items-center p-3 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center mr-3">
                                <span class="text-slate-800 font-medium" x-text="winner.name.substring(0, 2)"></span>
                            </div>
                            <span class="font-medium" x-text="winner.name"></span>
                        </div>
                        <span class="font-bold text-green-600" x-text="'+' + winner.amount.toLocaleString() + ' Kyats'"></span>
                    </div>
                </template>
            </div>
            <button @click="showWinnersModal = false" 
                    class="mt-6 w-full py-2 px-4 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-md">
                Continue
            </button>
        </div>
    </div>

    <!-- Live Stream Section -->
    <div class="my-6 bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="bg-gradient-to-r from-slate-700 to-slate-900 px-6 py-4">
            <h2 class="text-xl font-bold text-white">Live Dealer</h2>
        </div>
        <div class="aspect-w-16 aspect-h-9">
            <iframe
                src="https://www.youtube.com/embed/{{ $liveStreamId ?? 'WO7mr-umtZM?si=swlBcTDG7w9JbKW3' }}?autoplay=1&mute=0"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
                class="w-full h-96"
            ></iframe>
        </div>
        <!-- Add this after the game controls section -->
        @if ($isHost)
            <div class="mt-6 bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="bg-gradient-to-r from-slate-700 to-slate-900 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">Host Controls</h2>
                </div>
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Select Player</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            @foreach ($players as $player)
                                <button wire:click="selectPlayer({{ $player->id }})"
                                    class="px-3 py-2 border rounded-md text-sm {{ $selectedPlayer == $player->id ? 'bg-indigo-100 border-indigo-500 text-indigo-800' : 'border-gray-300 text-gray-700' }}">
                                    {{ $player->user->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    @if ($selectedPlayer)
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Selected Cards
                                ({{ count($selectedCards) }}/2)</h3>
                            <div class="flex space-x-2 mb-4">
                                @foreach ($selectedCards as $card)
                                    <div
                                        class="w-12 h-18 bg-white rounded-lg shadow-md border border-gray-200 flex items-center justify-center relative overflow-hidden">
                                        @php
                                            $suit = substr($card, -1);
                                            $value = substr($card, 0, strlen($card) - 1);
                                            $color = in_array($suit, ['H', 'D']) ? 'text-red-600' : 'text-slate-800';
                                            $suitSymbol =
                                                [
                                                    'H' => '♥',
                                                    'D' => '♦',
                                                    'C' => '♣',
                                                    'S' => '♠',
                                                ][$suit] ?? '';
                                        @endphp

                                        <div class="text-center">
                                            <div class="text-sm font-bold {{ $color }}">{{ $value }}</div>
                                            <div class="text-lg {{ $color }}">{{ $suitSymbol }}</div>
                                        </div>
                                    </div>
                                @endforeach

                                @for ($i = count($selectedCards); $i < 2; $i++)
                                    <div
                                        class="w-12 h-18 bg-gray-100 rounded-lg border border-dashed border-gray-300 flex items-center justify-center">
                                        <span class="text-gray-400">+</span>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Select Cards</h3>
                            <div
                                class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-13 gap-1 p-2 bg-gray-50 rounded-lg">
                                @foreach ($availableCards as $card)
                                    <button wire:click="selectCard('{{ $card }}')"
                                        class="w-10 h-14 bg-white rounded border border-gray-200 flex items-center justify-center hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        @php
                                            $suit = substr($card, -1);
                                            $value = substr($card, 0, strlen($card) - 1);
                                            $color = in_array($suit, ['H', 'D']) ? 'text-red-600' : 'text-slate-800';
                                            $suitSymbol =
                                                [
                                                    'H' => '♥',
                                                    'D' => '♦',
                                                    'C' => '♣',
                                                    'S' => '♠',
                                                ][$suit] ?? '';
                                        @endphp

                                        <div class="text-center">
                                            <div class="text-xs font-bold {{ $color }}">{{ $value }}
                                            </div>
                                            <div class="text-sm {{ $color }}">{{ $suitSymbol }}</div>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mt-6 flex justify-between">
                        <button wire:click="callForBets"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            Call for Bets (10s)
                        </button>

                        <button wire:click="determineWinner"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Determine Winner
                        </button>

                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- Game Header -->
        <div class="bg-gradient-to-r from-slate-700 to-slate-900 px-6 py-4 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-white">Game Table</h2>
                <p class="text-slate-300 text-sm">Round #{{ $round->id }}</p>
            </div>

            <!-- Countdown Timer -->
            <div class="bg-slate-800 px-4 py-2 rounded-lg text-center">
                <p class="text-xs text-slate-400 mb-1">Betting ends in</p>
                <div class="text-xl font-bold text-white" x-text="countdown + 's'"></div>
            </div>
        </div>

        <!-- Game Table -->
        <div class="p-6">
            <!-- Player Cards Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach ($players as $player)
                    @php
                        $playerRound = null;
                        foreach ($playerCards as $card) {
                            if ($card->player_id == $player->id) {
                                $playerRound = $card;
                                break;
                            }
                        }
                        $isCurrentUser = $player->user_id === auth()->id();
                    @endphp

                    <div
                        class="bg-white border {{ $isCurrentUser ? 'border-indigo-300 ring-2 ring-indigo-500' : 'border-gray-200' }} rounded-lg shadow-sm overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <span
                                        class="text-indigo-800 font-medium">{{ substr($player->user->name ?? 'User', 0, 2) }}</span>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $player->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $isCurrentUser ? 'You' : 'Player' }}</p>
                                </div>
                            </div>
                            @if ($playerRound && $playerRound->amount)
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                    Bet: {{ number_format($playerRound->amount) }} Kyats
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                    Waiting for bet
                                </span>
                            @endif
                        </div>

                        <!-- Inside the player card section, add this after the player info -->
                        <div class="p-4">
                            <!-- Cards Display section -->
                            <!-- Replace the template that uses formatCard with direct HTML -->
                            <template
                            x-if="playerCards[{{ $player->id }}] && playerCards[{{ $player->id }}].cards && playerCards[{{ $player->id }}].cards.length > 0"
                            x-init="console.log('Selected cards for player', {{ $player->id }}, ':', playerCards[{{ $player->id }}])">
                                <div class="mb-2">
                                    <div class="flex justify-center space-x-2">
                                        <template x-for="(card, index) in playerCards[{{ $player->id }}].cards"
                                            :key="index">
                                            <div
                                                class="w-16 h-24 bg-white rounded-lg shadow-md border border-gray-200 flex items-center justify-center relative overflow-hidden">
                                                <div class="text-center">
                                                    <div x-bind:class="['H', 'D'].includes(card.slice(-1)) ? 'text-red-600' :
                                                        'text-slate-800'"
                                                        class="text-sm font-bold" x-text="card.slice(0, -1)"></div>
                                                    <div x-bind:class="['H', 'D'].includes(card.slice(-1)) ? 'text-red-600' :
                                                        'text-slate-800'"
                                                        class="text-lg"
                                                        x-text="{
                                                            'H': '♥',
                                                            'D': '♦',
                                                            'C': '♣',
                                                            'S': '♠'
                                                            }[card.slice(-1)] || ''">
                                                    </div>
                                                </div>
                                            </div>
                                        </template>

                                        <template
                                            x-for="i in (2 - (selectedCards[{{ $player->id }}] ? selectedCards[{{ $player->id }}].length : 0))">
                                            <div
                                                class="w-12 h-18 bg-gray-100 rounded-lg border border-dashed border-gray-300 flex items-center justify-center">
                                                <span class="text-red-400">?</span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <template x-if="!selectedCards[{{ $player->id }}] || selectedCards[{{ $player->id }}].length === 0">
                                <div class="flex justify-center space-x-2 mb-4">
                                    @for ($i = 0; $i < 2; $i++)
                                        <div
                                            class="w-16 h-24 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg shadow-md flex items-center justify-center">
                                            <svg class="h-8 w-8 text-white opacity-50"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    @endfor
                                </div>
                            </template>


                            {{-- //|| $showAllCards && $isCurrentUser --}}
                            {{-- @if ($playerRound)
                                <div class="flex justify-center space-x-2 mb-4">
                                    @foreach ([$playerRound->card_1, $playerRound->card_2] as $card)
                                        <div
                                            class="w-16 h-24 bg-white rounded-lg shadow-md border border-gray-200 flex items-center justify-center relative overflow-hidden">
                                            @php
                                                $suit = substr($card, -1);
                                                $value = substr($card, 0, strlen($card) - 1);
                                                $color = in_array($suit, ['H', 'D'])
                                                    ? 'text-red-600'
                                                    : 'text-slate-800';
                                                $suitSymbol =
                                                    [
                                                        'H' => '♥',
                                                        'D' => '♦',
                                                        'C' => '♣',
                                                        'S' => '♠',
                                                    ][$suit] ?? '';
                                            @endphp

                                            <div class="text-center">
                                                <div class="text-lg font-bold {{ $color }}">{{ $value }}
                                                </div>
                                                <div class="text-2xl {{ $color }}">{{ $suitSymbol }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @elseif($cardsDealt)
                                <div  x-if="selectedCards[{{ $player->id }}] && selectedCards[{{ $player->id }}].length == 0 " class="flex justify-center space-x-2 mb-4">
                                    @for ($i = 0; $i < 2; $i++)
                                        <div
                                            class="w-16 h-24 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg shadow-md flex items-center justify-center">
                                            <svg class="h-8 w-8 text-white opacity-50"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    @endfor
                                </div>
                            @else
                                <div  x-if="selectedCards[{{ $player->id }}] && selectedCards[{{ $player->id }}].length > 0" class="flex justify-center items-center h-24 text-gray-400">
                                    Waiting for cards to be dealt...
                                </div>
                            @endif --}}
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Game Controls -->
            <div class="flex justify-between items-center">
                {{-- @if ($cardsDealt)
                    <button wire:click="showResults"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        Show Results
                    </button>
                @endif --}}

                <button wire:click="leaveGame"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Leave Game
                </button>
            </div>
        </div>
    </div>

    <!-- Betting Panel  && $playerCards !$showAllCards && -->
    @if (!$hasPlacedBet && !$isHost)
        <div class="mt-6 bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Place Your Bet</h3>
                <div class="text-sm text-gray-500" x-text="'Time remaining: ' + countdown + 's'"></div>
                {{-- <p class="text-sm text-gray-500">Time remaining: {{ $countdown ?? '10' }}s</p> --}}
            </div>

            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-sm text-gray-500">Minimum bet: {{ number_format($minBet) }} Kyats</div>
                    <div class="text-sm font-medium text-gray-900">Your balance:
                        {{ number_format(auth()->user()->wallet->amount ?? 0) }} Kyats</div>
                </div>

                <div class="mb-4">
                    <label for="bet-amount" class="block text-sm font-medium text-gray-700 mb-1">Bet Amount</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="text" wire:model="betAmount" id="bet-amount"
                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md"
                            placeholder="{{ $minBet }}">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Kyats</span>
                        </div>
                    </div>
                    @error('betAmount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-between">
                    <div class="flex space-x-2">
                        @foreach ([1000, 5000, 10000, 50000] as $quickAmount)
                            <button type="button" wire:click="setBetAmount({{ $quickAmount }})"
                                class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-indigo-500">
                                {{ number_format($quickAmount) }}
                            </button>
                        @endforeach
                    </div>
                    <button type="button" wire:click="placeBet"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Place Bet
                    </button>
                </div>
            </div>
        </div>
    @endif
    @if (session()->has('message'))
        <div class="mt-6 rounded-md bg-green-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mt-6 rounded-md bg-red-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

</div>
{{-- @script
    <!-- Add this script at the bottom of your file -->
    <script>
        function formatCard(card) {
            const suit = card.slice(-1);
            const value = card.slice(0, -1);
            const color = ['H', 'D'].includes(suit) ? 'text-red-600' : 'text-slate-800';
            const suitSymbol = {
                'H': '♥',
                'D': '♦',
                'C': '♣',
                'S': '♠'
            } [suit] || '';

            return `<div class="text-sm font-bold ${color}">${value}</div><div class="text-lg ${color}">${suitSymbol}</div>`;
        }
    </script>
@endscript --}}
