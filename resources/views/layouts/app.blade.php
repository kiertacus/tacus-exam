<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Chirper') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-white text-black">
        <div class="min-h-screen bg-white">
            @include('layouts.navigation')

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <script>
            // Add visual feedback to navigation links
            document.querySelectorAll('a').forEach(link => {
                // Skip links that are part of forms or have special handling
                if (link.closest('form') || link.getAttribute('href')?.startsWith('#')) {
                    return;
                }
                
                link.addEventListener('click', function(e) {
                    // Don't prevent default, just add visual feedback
                    if (!this.classList.contains('no-feedback')) {
                        // Add a subtle active state
                        this.style.opacity = '0.8';
                        setTimeout(() => {
                            this.style.opacity = '1';
                        }, 100);
                    }
                });
            });

            // Add feedback to all form submit buttons
            document.querySelectorAll('form button[type="submit"]').forEach(button => {
                button.addEventListener('click', function() {
                    // Keep original text visible, just show state change
                    if (!this.hasAttribute('onclick')) {
                        this.style.pointerEvents = 'none';
                    }
                });
            });
        </script>
    </body>
</html>
