<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-white">
        <!-- Loading Screen -->
        <x-loading-screen />

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-white to-gray-50">
            <div class="mb-6 sm:mb-8">
                <a href="/" class="flex items-center justify-center">
                    <span class="text-5xl sm:text-6xl font-bold text-black">𝕏</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 sm:mt-8 px-6 sm:px-8 py-8 sm:py-10 bg-white shadow-lg border border-gray-200 overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
