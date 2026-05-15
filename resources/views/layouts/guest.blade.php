<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        
        <style>
            body { 
                font-family: 'Outfit', sans-serif; 
                background-color: #f8fafc;
                background-image: 
                    radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.05) 0px, transparent 50%),
                    radial-gradient(at 100% 0%, rgba(168, 85, 247, 0.05) 0px, transparent 50%),
                    radial-gradient(at 100% 100%, rgba(236, 72, 153, 0.05) 0px, transparent 50%),
                    radial-gradient(at 0% 100%, rgba(59, 130, 246, 0.05) 0px, transparent 50%);
            }
            .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body>
        <div class="font-sans text-gray-900 antialiased">
            {{ $slot }}
        </div>

        @livewireScripts
    </body>
</html>
