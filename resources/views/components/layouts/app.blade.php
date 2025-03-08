<x-layouts.app.header :title="$title ?? null">
    <flux:main class="container mx-auto ">
        {{ $slot }}
    </flux:main>
</x-layouts.app.header>
