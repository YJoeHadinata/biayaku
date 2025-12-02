<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100" x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-800 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
            :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">

            <div class="flex items-center justify-center h-16 bg-gray-900">
                <h1 class="text-white text-xl font-bold">Admin Panel</h1>
            </div>

            <nav class="mt-8">
                <div class="px-4 space-y-2">
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center px-4 py-3 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700 text-white' : '' }}">
                        <i class="bi bi-speedometer2 mr-3"></i>
                        Dashboard
                    </a>

                    <a href="{{ route('admin.subscriptions.index') }}"
                        class="flex items-center px-4 py-3 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.subscriptions.*') ? 'bg-gray-700 text-white' : '' }}">
                        <i class="bi bi-credit-card mr-3"></i>
                        Subscription Pending
                    </a>

                    <a href="{{ route('admin.subscriptions.all') }}"
                        class="flex items-center px-4 py-3 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.subscriptions.all') ? 'bg-gray-700 text-white' : '' }}">
                        <i class="bi bi-list-ul mr-3"></i>
                        Semua Subscription
                    </a>

                    <a href="{{ route('admin.branches.index') }}"
                        class="flex items-center px-4 py-3 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.branches.*') ? 'bg-gray-700 text-white' : '' }}">
                        <i class="bi bi-building mr-3"></i>
                        Manajemen Cabang
                    </a>

                    <a href="{{ route('admin.users') }}"
                        class="flex items-center px-4 py-3 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.users*') ? 'bg-gray-700 text-white' : '' }}">
                        <i class="bi bi-people mr-3"></i>
                        Manajemen User
                    </a>
                </div>
            </nav>

            <!-- User Info & Logout -->
            <div class="absolute bottom-0 w-full p-4 border-t border-gray-700">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center">
                        <i class="bi bi-person text-white"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-white text-sm font-medium">{{ Auth::user()->name }}</p>
                        <p class="text-gray-400 text-xs">{{ Auth::user()->role }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center px-4 py-2 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200">
                        <i class="bi bi-box-arrow-right mr-3"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:ml-64">
            <!-- Top Bar -->
            <div class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <i class="bi bi-list text-xl"></i>
                    </button>

                    <div class="flex items-center space-x-4 ml-auto">
                        <a href="{{ route('dashboard') }}"
                            class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="bi bi-house-door mr-2"></i>
                            Kembali ke App
                        </a>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>

        <!-- Overlay for mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden" x-transition></div>
    </div>
</body>

</html>
