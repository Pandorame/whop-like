<!-- resources/views/livewire/campaign-list.blade.php -->
<div class="space-y-6">
    <h1 class="text-2xl font-bold">Content rewards</h1>
    <p class="text-zinc-600 dark:text-zinc-400">
        Post content on social media and get paid for the views you generate.
    </p>

    @foreach($campaigns as $campaign)
    <div class="border rounded-lg p-4 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
        <div class="flex justify-between items-start">
            <h2 class="font-semibold text-lg">{{ $campaign->name }}</h2>
            <span class="text-sm bg-zinc-100 dark:bg-zinc-700 px-2 py-1 rounded">
                {{ $campaign->payout_structure }}
            </span>
        </div>
        
        <div class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
            <p>{{ $campaign->description }}</p>
            
            <div class="mt-3 grid grid-cols-2 gap-4">
                <div>
                    <p class="font-medium">${{ number_format($campaign->amount_paid, 2) }} of ${{ number_format($campaign->budget, 2) }} paid out</p>
                    <div class="w-full bg-zinc-200 rounded-full h-2.5 dark:bg-zinc-700 mt-1">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $campaign->progress }}%"></div>
                    </div>
                    <p class="text-xs mt-1">{{ round($campaign->progress) }}%</p>
                </div>
                
                <div class="space-y-1">
                    <p><span class="font-medium">Type:</span> {{ $campaign->content_type }}</p>
                    <p><span class="font-medium">Platforms:</span> {{ implode(', ', $campaign->platforms) }}</p>
                    <p><span class="font-medium">Views:</span> {{ number_format($campaign->submissions->sum('views')) }}</p>
                </div>
            </div>
            
            <a href="{{ route('campaigns.show', $campaign) }}" wire:navigate class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                Participate
            </a>
        </div>
    </div>
    @endforeach
</div>