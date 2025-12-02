<nav x-data="{ open: false }" class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                        <div class="p-2 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg">
                            <x-application-logo class="block h-6 w-auto fill-current text-white" />
                        </div>
                        <span class="text-xl font-bold text-gray-900 hidden sm:block">BiayaKu</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="flex items-center space-x-2">
                        <i class="bi bi-house-door text-sm"></i>
                        <span>{{ __('Dashboard') }}</span>
                    </x-nav-link>
                    <x-nav-link :href="route('materials.index')" :active="request()->routeIs('materials.*')" class="flex items-center space-x-2">
                        <i class="bi bi-box-seam text-sm"></i>
                        <span>{{ __('Material') }}</span>
                    </x-nav-link>
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="flex items-center space-x-2">
                        <i class="bi bi-cup-hot text-sm"></i>
                        <span>{{ __('Produk') }}</span>
                    </x-nav-link>
                    <x-nav-link :href="route('production-batches.index')" :active="request()->routeIs('production-batches.*')" class="flex items-center space-x-2">
                        <i class="bi bi-gear text-sm"></i>
                        <span>{{ __('Batch Produksi') }}</span>
                    </x-nav-link>
                    <x-nav-link :href="route('operational.index')" :active="request()->routeIs('operational.*')" class="flex items-center space-x-2">
                        <i class="bi bi-cash-stack text-sm"></i>
                        <span>{{ __('Biaya Operasional') }}</span>
                    </x-nav-link>
                    <x-nav-link :href="route('miscellaneous.index')" :active="request()->routeIs('miscellaneous.*')" class="flex items-center space-x-2">
                        <i class="bi bi-receipt text-sm"></i>
                        <span>{{ __('Biaya Lain-lain') }}</span>
                    </x-nav-link>
                    <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" class="flex items-center space-x-2">
                        <i class="bi bi-file-earmark-bar-graph text-sm"></i>
                        <span>{{ __('Laporan') }}</span>
                    </x-nav-link>
                    <x-nav-link :href="route('subscriptions.status')" :active="request()->routeIs('subscriptions.*')" class="flex items-center space-x-2">
                        <i class="bi bi-credit-card text-sm"></i>
                        <span>{{ __('Langganan') }}</span>
                    </x-nav-link>

                    @if (Auth::user()->isAdmin())
                        <div class="border-l border-gray-300 pl-4 ml-4">
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')"
                                class="flex items-center space-x-2 bg-red-50 text-red-700">
                                <i class="bi bi-shield-check text-sm"></i>
                                <span>{{ __('Admin') }}</span>
                            </x-nav-link>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="64">
                    <x-slot name="trigger">
                        <button
                            class="flex items-center space-x-3 px-4 py-2 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <div
                                class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                <i class="bi bi-person-fill text-white text-sm"></i>
                            </div>
                            <div class="text-left">
                                <div class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ Auth::user()->branch->name ?? 'Tidak ada cabang' }}</div>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-gray-200">
                            <div class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</div>
                            <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
                            <div class="text-xs text-gray-400 mt-1">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    @if (Auth::user()->isSuperAdmin()) bg-red-100 text-red-800
                                    @elseif(Auth::user()->isAdmin()) bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}
                                </span>
                            </div>
                        </div>

                        <x-dropdown-link :href="route('subscriptions.status')" class="flex items-center space-x-3">
                            <i class="bi bi-credit-card text-blue-500"></i>
                            <span>{{ __('Langganan') }}</span>
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center space-x-3">
                            <i class="bi bi-person-gear text-gray-500"></i>
                            <span>{{ __('Profile') }}</span>
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="flex items-center space-x-3 text-red-600 hover:bg-red-50">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>{{ __('Log Out') }}</span>
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-xl text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-600 transition duration-200 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden bg-white border-t border-gray-200">
        <div class="px-4 py-6 space-y-2">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="flex items-center space-x-3"
                @click="open = false">
                <i class="bi bi-house-door text-lg"></i>
                <span>{{ __('Dashboard') }}</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('materials.index')" :active="request()->routeIs('materials.*')" class="flex items-center space-x-3"
                @click="open = false">
                <i class="bi bi-box-seam text-lg"></i>
                <span>{{ __('Material') }}</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="flex items-center space-x-3"
                @click="open = false">
                <i class="bi bi-cup-hot text-lg"></i>
                <span>{{ __('Produk') }}</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('production-batches.index')" :active="request()->routeIs('production-batches.*')" class="flex items-center space-x-3"
                @click="open = false">
                <i class="bi bi-gear text-lg"></i>
                <span>{{ __('Batch Produksi') }}</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('operational.index')" :active="request()->routeIs('operational.*')" class="flex items-center space-x-3"
                @click="open = false">
                <i class="bi bi-cash-stack text-lg"></i>
                <span>{{ __('Biaya Operasional') }}</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('miscellaneous.index')" :active="request()->routeIs('miscellaneous.*')" class="flex items-center space-x-3"
                @click="open = false">
                <i class="bi bi-receipt text-lg"></i>
                <span>{{ __('Biaya Lain-lain') }}</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" class="flex items-center space-x-3"
                @click="open = false">
                <i class="bi bi-file-earmark-bar-graph text-lg"></i>
                <span>{{ __('Laporan') }}</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('subscriptions.status')" :active="request()->routeIs('subscriptions.*')" class="flex items-center space-x-3"
                @click="open = false">
                <i class="bi bi-credit-card text-lg"></i>
                <span>{{ __('Langganan') }}</span>
            </x-responsive-nav-link>

            @if (Auth::user()->isAdmin())
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')"
                        class="flex items-center space-x-3 bg-red-50 text-red-700" @click="open = false">
                        <i class="bi bi-shield-check text-lg"></i>
                        <span>{{ __('Admin') }}</span>
                    </x-responsive-nav-link>
                </div>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="border-t border-gray-200 bg-gray-50 px-4 py-4">
            <div class="flex items-center space-x-3 mb-4">
                <div
                    class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                    <i class="bi bi-person-fill text-white"></i>
                </div>
                <div>
                    <div class="font-semibold text-gray-900">{{ Auth::user()->name }}</div>
                    <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    <div class="text-xs text-gray-400">
                        <span
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                            @if (Auth::user()->isSuperAdmin()) bg-red-100 text-red-800
                            @elseif(Auth::user()->isAdmin()) bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <x-responsive-nav-link :href="route('subscriptions.status')" class="flex items-center space-x-3" @click="open = false">
                    <i class="bi bi-credit-card text-blue-500"></i>
                    <span>{{ __('Langganan') }}</span>
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('profile.edit')" class="flex items-center space-x-3" @click="open = false">
                    <i class="bi bi-person-gear text-gray-500"></i>
                    <span>{{ __('Profile') }}</span>
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();"
                        class="flex items-center space-x-3 text-red-600" @click="open = false">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>{{ __('Log Out') }}</span>
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
