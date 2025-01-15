<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link
            href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
            rel="stylesheet"
        />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
        <div class="flex min-h-screen">
            <!-- Sidebar -->
            <div class="w-64 bg-gray-800 text-white p-4">
                <h2 class="text-lg font-bold mb-4">Menu</h2>
                <ul>
                    <li class="mb-2">
                        <a
                            href="{{ route('dashboard') }}"
                            class="block p-2 rounded hover:bg-gray-700"
                        >
                            Dashboard
                        </a>
                    </li>
                    <li class="mb-2">
                        <a
                            href="{{ route('profile.edit') }}"
                            class="block p-2 rounded hover:bg-gray-700"
                        >
                            Modifica Profilo
                        </a>
                    </li>
                    <li class="mb-2">
                        <form
                            action="{{ route('profile.destroy') }}"
                            method="POST"
                            onsubmit="return confirm('Sei sicuro di voler eliminare il profilo?');"
                        >
                            @csrf @method('DELETE')
                            <button
                                type="submit"
                                class="block p-2 rounded w-full text-left hover:bg-gray-700"
                            >
                                Elimina Profilo
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

            <!-- Contenuto principale -->
            <div class="flex-1 flex flex-col">
                @include('layouts.navigation')

                <!-- Page Heading -->
                @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">{{ $header }}</div>
                </header>
                @endisset

                <!-- Page Content -->
                <main class="flex-1 p-6">{{ $slot }}</main>
            </div>
        </div>
    </body>
</html>
