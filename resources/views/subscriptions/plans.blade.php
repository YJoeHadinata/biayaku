<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Paket Langganan') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-800 text-sm">
                <i class="bi bi-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Hero Section -->
            <div class="text-center mb-12">
                <div
                    class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 mb-6">
                    <i class="bi bi-stars text-blue-600 mr-2"></i>
                    <span class="text-blue-700 text-sm font-medium">Pilih Paket yang Sesuai Bisnis Anda</span>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Tingkatkan Potensi Bisnis Anda</h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Dapatkan akses fitur premium untuk mengoptimalkan
                    pengelolaan biaya produksi dan meningkatkan efisiensi operasional</p>
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div
                    class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl mb-8 flex items-center">
                    <i class="bi bi-check-circle-fill text-green-600 mr-3 text-xl"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-xl mb-8 flex items-center">
                    <i class="bi bi-exclamation-triangle-fill text-red-600 mr-3 text-xl"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Pricing Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
                @foreach ($plans as $plan)
                    <div
                        class="relative bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 {{ $currentSubscription && $currentSubscription->plan->id === $plan->id ? 'ring-2 ring-blue-500 shadow-blue-100' : 'hover:scale-105' }}">
                        @if ($currentSubscription && $currentSubscription->plan->id === $plan->id)
                            <div
                                class="absolute top-4 right-4 bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-medium">
                                <i class="bi bi-check-circle mr-1"></i> Aktif
                            </div>
                        @endif

                        @if ($plan->slug === 'pro')
                            <div
                                class="absolute top-4 left-4 bg-gradient-to-r from-purple-500 to-pink-500 text-white px-3 py-1 rounded-full text-xs font-medium">
                                <i class="bi bi-star mr-1"></i> Paling Populer
                            </div>
                        @endif

                        <div class="p-8">
                            <!-- Plan Header -->
                            <div class="text-center mb-6">
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h3>
                                <p class="text-gray-600 text-sm">{{ $plan->description }}</p>
                            </div>

                            <!-- Pricing -->
                            <div class="text-center mb-8">
                                <div class="flex items-baseline justify-center">
                                    <span
                                        class="text-5xl font-bold text-gray-900">{{ $plan->price > 0 ? 'Rp' . number_format($plan->price, 0, ',', '.') : 'Gratis' }}</span>
                                    @if ($plan->price > 0)
                                        <span class="text-gray-600 ml-2">/{{ $plan->interval }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Features -->
                            <div class="space-y-4 mb-8">
                                @if ($plan->slug === 'free')
                                    <div class="flex items-center text-gray-700">
                                        <i class="bi bi-check-circle-fill text-green-500 mr-3 text-lg"></i>
                                        <span class="text-sm">10 Material maksimal</span>
                                    </div>
                                    <div class="flex items-center text-gray-700">
                                        <i class="bi bi-check-circle-fill text-green-500 mr-3 text-lg"></i>
                                        <span class="text-sm">5 Produk maksimal</span>
                                    </div>
                                    <div class="flex items-center text-gray-700">
                                        <i class="bi bi-check-circle-fill text-green-500 mr-3 text-lg"></i>
                                        <span class="text-sm">10 Batch produksi per bulan</span>
                                    </div>
                                    <div class="flex items-center text-gray-700">
                                        <i class="bi bi-check-circle-fill text-green-500 mr-3 text-lg"></i>
                                        <span class="text-sm">1 User per cabang</span>
                                    </div>
                                @elseif($plan->slug === 'pro')
                                    <div class="flex items-center text-gray-700">
                                        <i class="bi bi-check-circle-fill text-green-500 mr-3 text-lg"></i>
                                        <span class="text-sm">200 Material maksimal</span>
                                    </div>
                                    <div class="flex items-center text-gray-700">
                                        <i class="bi bi-check-circle-fill text-green-500 mr-3 text-lg"></i>
                                        <span class="text-sm">100 Produk maksimal</span>
                                    </div>
                                    <div class="flex items-center text-gray-700">
                                        <i class="bi bi-check-circle-fill text-green-500 mr-3 text-lg"></i>
                                        <span class="text-sm">200 Batch produksi per bulan</span>
                                    </div>
                                    <div class="flex items-center text-gray-700">
                                        <i class="bi bi-check-circle-fill text-green-500 mr-3 text-lg"></i>
                                        <span class="text-sm">10 User per cabang</span>
                                    </div>
                                    <div class="flex items-center text-gray-700">
                                        <i class="bi bi-check-circle-fill text-blue-500 mr-3 text-lg"></i>
                                        <span class="text-sm font-medium">Multi-branch support</span>
                                    </div>
                                    <div class="flex items-center text-gray-700">
                                        <i class="bi bi-check-circle-fill text-blue-500 mr-3 text-lg"></i>
                                        <span class="text-sm font-medium">Export PDF & Excel</span>
                                    </div>
                                @elseif($plan->slug === 'enterprise')
                                    <div class="flex items-center text-gray-700">
                                        <i class="bi bi-check-circle-fill text-purple-500 mr-3 text-lg"></i>
                                        <span class="text-sm font-medium">Material Unlimited</span>
                                    </div>
                                    <div class="flex items-center text-gray-700">
                                        <i class="bi bi-check-circle-fill text-purple-500 mr-3 text-lg"></i>
                                        <span class="text-sm font-medium">Produk Unlimited</span>
                                    </div>
                                    <div class="flex items-center text-gray-700">
                                        <i class="bi bi-check-circle-fill text-purple-500 mr-3 text-lg"></i>
                                        <span class="text-sm font-medium">Batch Produksi Unlimited</span>
                                    </div>
                                    <div class="flex items-center text-gray-700">
                                        <i class="bi bi-check-circle-fill text-purple-500 mr-3 text-lg"></i>
                                        <span class="text-sm font-medium">User Unlimited</span>
                                    </div>
                                    <div class="flex items-center text-gray-700">
                                        <i class="bi bi-check-circle-fill text-purple-500 mr-3 text-lg"></i>
                                        <span class="text-sm font-medium">Semua fitur premium</span>
                                    </div>
                                    <div class="flex items-center text-gray-700">
                                        <i class="bi bi-check-circle-fill text-purple-500 mr-3 text-lg"></i>
                                        <span class="text-sm font-medium">API Access</span>
                                    </div>
                                @endif
                            </div>

                            <!-- CTA Button -->
                            <div class="text-center">
                                @if ($currentSubscription && $currentSubscription->plan->id === $plan->id)
                                    <div
                                        class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 text-blue-800 px-6 py-3 rounded-xl font-medium">
                                        <i class="bi bi-check-circle-fill mr-2"></i>
                                        Paket Anda Saat Ini
                                    </div>
                                @else
                                    @if ($currentSubscription && $plan->price > $currentSubscription->plan->price)
                                        <form method="POST" action="{{ route('subscriptions.upgrade', $plan->slug) }}">
                                            @csrf
                                            <button type="submit"
                                                class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-105">
                                                <i class="bi bi-arrow-up-circle mr-2"></i>
                                                Upgrade ke {{ $plan->name }}
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST"
                                            action="{{ route('subscriptions.subscribe', $plan->slug) }}">
                                            @csrf
                                            <button type="submit"
                                                class="w-full {{ $plan->slug === 'free' ? 'bg-gray-100 hover:bg-gray-200 text-gray-800' : 'bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white' }} font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-105">
                                                @if ($plan->slug === 'free')
                                                    <i class="bi bi-play-circle mr-2"></i>
                                                    Mulai Gratis
                                                @else
                                                    <i class="bi bi-rocket mr-2"></i>
                                                    Pilih {{ $plan->name }}
                                                @endif
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- FAQ Section -->
            <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-2xl p-8">
                <h3 class="text-2xl font-bold text-gray-900 text-center mb-8">Pertanyaan Umum</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h4 class="font-semibold text-gray-900 mb-2">Bisakah saya upgrade kapan saja?</h4>
                        <p class="text-gray-600 text-sm">Ya, Anda dapat upgrade paket kapan saja. Perubahan akan
                            berlaku segera setelah pembayaran.</p>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h4 class="font-semibold text-gray-900 mb-2">Apakah ada periode trial?</h4>
                        <p class="text-gray-600 text-sm">Paket Free dapat digunakan tanpa batas waktu. Untuk paket
                            berbayar, tersedia trial 14 hari.</p>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h4 class="font-semibold text-gray-900 mb-2">Bagaimana cara pembayaran?</h4>
                        <p class="text-gray-600 text-sm">Pembayaran dilakukan secara manual melalui transfer bank.
                            Invoice akan dikirim via email.</p>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h4 class="font-semibold text-gray-900 mb-2">Dapatkah saya downgrade?</h4>
                        <p class="text-gray-600 text-sm">Ya, Anda dapat downgrade paket di akhir periode billing. Data
                            akan disesuaikan dengan limit paket baru.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
