<!-- resources/views/livewire/campaign-grid.blade.php -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Content rewards</h1>
        <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">
            Post content on social media and get paid for the views you generate.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($campaigns as $campaign)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300">
            <!-- Campaign Image (extract first image from HTML) -->
            @if(preg_match('/<img[^>]+src="([^">]+)"/', $campaign->description, $matches))
            <div class="h-48 overflow-hidden">
                <img src="{{ $matches[1] }}" alt="{{ $campaign->name }}" class="w-full h-full object-cover">
            </div>
            @else
            <div class="h-48 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center">
                <svg class="h-16 w-16 text-blue-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            @endif

            <div class="p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $campaign->name }}</h2>
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-100">
                        {{ $campaign->content_type }}
                    </span>
                </div>

                <!-- Progress Bar -->
                <div class="mt-4">
                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-300 mb-1">
                        <span>${{ number_format($campaign->amount_paid, 2) }} paid</span>
                        <span>${{ number_format($campaign->budget, 2) }} total</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $campaign->progress }}%"></div>
                    </div>
                    <p class="text-right text-xs text-gray-500 dark:text-gray-400 mt-1">{{ round($campaign->progress) }}% funded</p>
                </div>

                <!-- Platforms -->
                <div class="mt-4">
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">Platforms:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($campaign->platforms as $platform)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                            {{ ucfirst($platform) }}
                        </span>
                        @endforeach
                    </div>
                </div>

                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
    <span>Payout: {{ $campaign->payout_structure_display }}</span>
</div>
                <!-- Views -->
                <div class="mt-4 flex items-center text-sm text-gray-600 dark:text-gray-300">
                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    {{ number_format($campaign->submissions->sum('views')) }} views
                </div>

                <!-- Participate Button -->
                <div class="mt-6">
                    <a href="{{ route('campaigns.show', $campaign) }}" wire:navigate
                       class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                        Participate
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>