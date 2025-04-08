<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>KoeMee</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="{{ request()->cookie('dark_mode') ? 'dark' : '' }} min-h-screen bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 flex flex-col">
        <div class="container mx-auto px-4 py-8 flex-grow flex flex-col">
            <header class="flex justify-between items-center mb-8">
                <div class="text-2xl font-semibold">KoeMee</div>
            </header>
            
            <div class="max-w-md w-full mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                @auth
                    <h2 class="text-xl font-semibold mb-4 text-center">Welcome back!</h2>
                    <a href="{{ url('/dashboard') }}" class="block w-full bg-gray-900 dark:bg-gray-100 hover:bg-gray-800 dark:hover:bg-gray-200 text-white dark:text-gray-900 font-medium py-2 px-4 rounded-md text-center transition-colors duration-200 mb-3">
                        Go to Dashboard
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium py-2 px-4 rounded-md text-center transition-colors duration-200">
                            Logout
                        </button>
                    </form>
                @else
                    <h2 class="text-xl font-semibold mb-4 text-center">Join KoeMee Today</h2>
                    <a href="{{ route('login') }}" class="block w-full bg-gray-900 dark:bg-gray-100 hover:bg-gray-800 dark:hover:bg-gray-200 text-white dark:text-gray-900 font-medium py-2 px-4 rounded-md text-center transition-colors duration-200 mb-3">
                        Login
                    </a>
                    
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="block w-full border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium py-2 px-4 rounded-md text-center transition-colors duration-200">
                            Register
                        </a>
                    @endif
                @endauth
            </div>
            
            <footer class="mt-auto text-center py-6 text-sm text-gray-500 dark:text-gray-400">
                <p>&copy; {{ date('Y') }} KoeMee. All rights reserved.</p>
            </footer>
        </div>
    </body>
</html>
