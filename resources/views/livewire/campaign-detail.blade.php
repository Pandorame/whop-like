<!-- resources/views/livewire/campaign-detail.blade.php -->
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">{{ $campaign->name }}</h1>
    
    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Campaign Rules</h2>
        <div class="prose dark:prose-invert mb-6">
            {!! Str::markdown($campaign->description) !!}
        </div>
        
        <h2 class="text-xl font-semibold mb-4">Submit Your Content</h2>
        <form wire:submit.prevent="submit">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Content URL</label>
                    <input type="url" wire:model="contentUrl" class="w-full rounded border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900">
                    @error('contentUrl') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-1">Platform</label>
                    <select wire:model="platform" class="w-full rounded border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900">
                        <option value="">Select platform</option>
                        @foreach($campaign->platforms as $platform)
                            <option value="{{ $platform }}">{{ ucfirst($platform) }}</option>
                        @endforeach
                    </select>
                    @error('platform') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input wire:model="agreeTerms" type="checkbox" class="rounded border-zinc-300 text-blue-600 focus:ring-blue-500">
                    </div>
                    <div class="ml-3 text-sm">
                        <label class="font-medium">I agree to the campaign terms</label>
                        @error('agreeTerms') <span class="text-red-500 text-sm block">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                    Submit Content
                </button>
            </div>
        </form>
    </div>
</div>