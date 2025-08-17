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
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen flex flex-col">
            @include('components.navigation')

            <div class="flex flex-1">
                @include('components.sidebar')

                <main class="flex-1 p-6 lg:p-8">
                    <div class="max-w-7xl mx-auto">
                        @isset($header)
                            <header class="mb-6">
                                <div class="bg-white shadow rounded-lg p-4">
                                    {{ $header }}
                                </div>
                            </header>
                        @endisset

                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
