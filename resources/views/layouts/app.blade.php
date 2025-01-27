@include('components.bootstrap')

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <!-- <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>{{ config('app.name', 'Laravel') }}</title>

         Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link
            href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
            rel="stylesheet"
        />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    </head>


    <style>
        .menu-item:hover {
            background-color: #555; /* Change this to your desired color */
        }
    </style>
    <body class="bg-light">
        <div class="d-flex min-vh-100">
            <!-- Sidebar -->
            <div id="sidebar" class="bg-dark text-white p-4" style="width: 250px;">
                <h2 class="h5 mb-4">Menu</h2>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a
                            href="{{ route('dashboard') }}"
                            class="nav-link text-white rounded menu-item"
                        >
                            <i class="fas fa-table-columns"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a
                            href="{{ route('vehicles.adminIndex') }}"
                            class="nav-link text-white rounded menu-item"
                        >
                            <i class="fas fa-car"></i> Vehicles
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a
                            href="{{ route('settings.index') }}"
                            class="nav-link text-white rounded menu-item"
                        >
                            <i class="fas fa-cog"></i> Settings
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="flex-grow-1 d-flex flex-column">
                @include('layouts.navigation')

                <!-- Page Heading -->
                @isset($header)
                <header class="bg-white shadow-sm">
                    <div class="container py-3">{{ $header }}</div>
                </header>
                @endisset

                <!-- Page Content -->
                <main class="flex-grow-1 p-4">{{ $slot }}</main>
            </div>
        </div>
    </body>
</html>
