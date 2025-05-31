<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <div class="flex min-h-screen">
            <!-- Minimal Sidebar (Whop-style) with Heroicons -->
            <div class="hidden lg:flex flex-col items-center w-16 hover:w-64 transition-all duration-300 ease-in-out border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 group/sidebar">
                <!-- Logo -->
                  <!--    <div class="flex items-center justify-center w-full px-4 py-5 overflow-hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" 
                         class="w-8 h-8 text-zinc-800 dark:text-white group-hover/sidebar:mr-2 transition-all">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 7.5.415-.207a.75.75 0 0 1 1.085.67V10.5m0 0h6m-6 0h-1.5m1.5 0v5.438c0 .354.161.697.473.865a3.751 3.751 0 0 0 5.452-2.553c.083-.409-.263-.75-.68-.75h-.745M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span class="text-xl font-semibold whitespace-nowrap opacity-0 group-hover/sidebar:opacity-100 transition-opacity duration-200">
                        Whop
                    </span>
                </div> -->

                <!-- Navigation Icons with Heroicons -->
                <div class="flex-1 w-full space-y-1 px-2 py-4">
                    <!-- Dashboard -->
                    <div class="flex">
                    <div class="w-20 h-20 flex">
         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" 
                         class="w-8 h-8 text-zinc-800 dark:text-white group-hover/sidebar:mr-2 transition-all">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 7.5.415-.207a.75.75 0 0 1 1.085.67V10.5m0 0h6m-6 0h-1.5m1.5 0v5.438c0 .354.161.697.473.865a3.751 3.751 0 0 0 5.452-2.553c.083-.409-.263-.75-.68-.75h-.745M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg> </div>
                    <span class="ml-4 whitespace-nowrap opacity-0 group-hover/sidebar:opacity-100 transition-opacity duration-200">
                            Whop-Like-Web
                        </span>  </div>
           

                    <a href="{{ route('campaign') }}" class="flex items-center w-full p-3 rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-800 group/navitem" wire:navigate>
                        <div class="w-6 h-6 flex items-center justify-center text-zinc-600 dark:text-zinc-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                            </svg>
                        </div>
                        <span class="ml-4 whitespace-nowrap opacity-0 group-hover/sidebar:opacity-100 transition-opacity duration-200">
                            Discover
                        </span>
                    </a>

                    <!-- Deposit -->
                    <a href="{{ route('deposit') }}" class="flex items-center w-full p-3 rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-800 group/navitem" wire:navigate>
                        <div class="w-6 h-6 flex items-center justify-center text-zinc-600 dark:text-zinc-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                            </svg>
                        </div>
                        <span class="ml-4 whitespace-nowrap opacity-0 group-hover/sidebar:opacity-100 transition-opacity duration-200">
                            Deposit
                        </span>
                    </a>

                    <!-- Histories -->
                    <a href="{{ route('histoires') }}" class="flex items-center w-full p-3 rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-800 group/navitem" wire:navigate>
                        <div class="w-6 h-6 flex items-center justify-center text-zinc-600 dark:text-zinc-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="ml-4 whitespace-nowrap opacity-0 group-hover/sidebar:opacity-100 transition-opacity duration-200">
                            Histories
                        </span>
                    </a>

                    <!-- Games -->
                    <a href="{{ route('game.lobby') }}" class="flex items-center w-full p-3 rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-800 group/navitem" wire:navigate>
                        <div class="w-6 h-6 flex items-center justify-center text-zinc-600 dark:text-zinc-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 010 .656l-5.603 3.113a.375.375 0 01-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112z" />
                            </svg>
                        </div>
                        <span class="ml-4 whitespace-nowrap opacity-0 group-hover/sidebar:opacity-100 transition-opacity duration-200">
                            Games
                        </span>
                    </a>
                </div>

                <!-- User Profile Dropdown at Bottom -->
<div class="w-full p-2 border-t border-zinc-200 dark:border-zinc-700">
    <div x-data="{ open: false }" class="relative">
        <!-- Profile Button -->
        <button @click="open = !open" class="flex items-center w-full p-2 rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-800 group/profile">
            <div class="w-8 h-8 flex items-center justify-center rounded-full bg-zinc-200 dark:bg-zinc-700 text-sm font-medium">
                {{ auth()->user()->initials() }}
            </div>
            <div class="ml-3 overflow-hidden whitespace-nowrap opacity-0 group-hover/sidebar:opacity-100 transition-opacity duration-200">
                <div class="text-sm font-medium truncate">{{ auth()->user()->name }}</div>
                <div class="text-xs text-zinc-500 dark:text-zinc-400 truncate">{{ auth()->user()->email }}</div>
            </div>
        </button>

        <!-- Dropdown Menu -->
        <div x-show="open" 
             @click.outside="open = false"
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="absolute left-0 bottom-full mb-2 w-48 origin-bottom-left rounded-md bg-white dark:bg-zinc-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
            <div class="py-1">
                <!-- Profile Link -->
                <a href="/settings/profile" wire:navigate class="block px-4 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        Profile Settings
                    </div>
                </a>
                
                <!-- Divider -->
                <div class="border-t border-zinc-200 dark:border-zinc-700 my-1"></div>
                
                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

</div>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col">
                <!-- Top Header (for mobile) -->
                <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 lg:hidden">
                    <flux:sidebar.toggle icon="bars-2" inset="left" />
                    
                    <a href="{{ route('dashboard') }}" class="ml-2 mr-5 flex items-center space-x-2 lg:ml-0" wire:navigate>
                        <x-app-logo />
                    </a>
                    
                    <flux:spacer />
                    
                    <!-- Mobile User Menu -->
                    <flux:dropdown position="bottom" align="end">
                        <flux:profile
                            class="cursor-pointer"
                            :initials="auth()->user()->initials()"
                        />
                        <!-- ... same user menu as before ... -->
                    </flux:dropdown>
                </flux:header>

                <!-- Page Content -->
                <main class="flex-1 bg-white dark:bg-zinc-800 p-6 overflow-auto">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Mobile Sidebar (hidden on desktop) -->
        <flux:sidebar stashable sticky class="lg:hidden border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <!-- ... keep your existing mobile sidebar content ... -->
        </flux:sidebar>

        @fluxScripts
    </body>
</html>