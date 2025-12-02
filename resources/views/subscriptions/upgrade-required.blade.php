<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Upgrade Diperlukan') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-800 text-sm">
                <i class="bi bi-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Main Upgrade Prompt -->
            <div
                class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl p-8 mb-8 border border-amber-200 shadow-lg">
                <div class="text-center">
                    <div class="bg-amber-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                        <i class="bi bi-lock-fill text-amber-600 text-3xl"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">
                        Tingkatkan Paket Anda
                    </h1>
                    <p class="text-xl text-gray-700 mb-6">
                        Untuk mengakses <span class="font-semibold text-amber-700">{{ $feature }}</span>, upgrade
                        paket langganan Anda sekarang!
                    </p>
                    <div class="bg-white bg-opacity-50 rounded-xl p-4 inline-block">
                        <p class="text-amber-800 font-medium">
                            <i class="bi bi-lightbulb mr-2"></i>
                            Fitur ini tersedia di paket Pro dan Enterprise
                        </p>
                    </div>
                </div>
            </div>

            <!-- Available Plans -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                @foreach ($plans as $plan)
                    @if ($plan->slug !== 'free')
                        <div
                            class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 {{ $plan->slug === 'pro' ? 'ring-2 ring-purple-300' : '' }}">
                            @if ($plan->slug === 'pro')
                                <div
                                    class="bg-gradient-to-r from-purple-500 to-pink-500 text-white px-4 py-2 text-center text-sm font-medium">
                                    <i class="bi bi-star mr-1"></i> Rekomendasi
                                </div>
                            @endif

                            <div class="p-8">
                                <!-- Plan Header -->
                                <div class="text-center mb-6">
                                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h3>
                                    <p class="text-gray-600">{{ $plan->description }}</p>
                                </div>

                                <!-- Pricing -->
                                <div class="text-center mb-8">
                                    <div class="flex items-baseline justify-center">
                                        <span
                                            class="text-4xl font-bold text-gray-900">Rp{{ number_format($plan->price, 0, ',', '.') }}</span>
                                        <span class="text-gray-600 ml-2">/{{ $plan->interval }}</span>
                                    </div>
                                </div>

                                <!-- Key Features -->
                                <div class="space-y-3 mb-8">
                                    @if ($plan->slug === 'pro')
                                        <div class="flex items-center text-gray-700">
                                            <i class="bi bi-check-circle-fill text-green-500 mr-3 text-lg"></i>
                                            <span>200 Material & 100 Produk</span>
                                        </div>
                                        <div class="flex items-center text-gray-700">
                                            <i class="bi bi-check-circle-fill text-green-500 mr-3 text-lg"></i>
                                            <span>200 Batch produksi per bulan</span>
                                        </div>
                                        <div class="flex items-center text-gray-700">
                                            <i class="bi bi-check-circle-fill text-blue-500 mr-3 text-lg"></i>
                                            <span class="font-medium">Multi-branch support</span>
                                        </div>
                                        <div class="flex items-center text-gray-700">
                                            <i class="bi bi-check-circle-fill text-blue-500 mr-3 text-lg"></i>
                                            <span class="font-medium">Export PDF & Excel</span>
                                        </div>
                                    @elseif($plan->slug === 'enterprise')
                                        <div class="flex items-center text-gray-700">
                                            <i class="bi bi-check-circle-fill text-purple-500 mr-3 text-lg"></i>
                                            <span class="font-medium">Material & Produk Unlimited</span>
                                        </div>
                                        <div class="flex items-center text-gray-700">
                                            <i class="bi bi-check-circle-fill text-purple-500 mr-3 text-lg"></i>
                                            <span class="font-medium">Batch Produksi Unlimited</span>
                                        </div>
                                        <div class="flex items-center text-gray-700">
                                            <i class="bi bi-check-circle-fill text-purple-500 mr-3 text-lg"></i>
                                            <span class="font-medium">User Unlimited</span>
                                        </div>
                                        <div class="flex items-center text-gray-700">
                                            <i class="bi bi-check-circle-fill text-purple-500 mr-3 text-lg"></i>
                                            <span class="font-medium">API Access & Semua Fitur</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- CTA Button -->
                                <form method="POST" action="{{ route('subscriptions.subscribe', $plan->slug) }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full {{ $plan->slug === 'pro' ? 'bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600' : 'bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700' }} text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg">
                                        @if ($plan->slug === 'pro')
                                            <i class="bi bi-rocket mr-2"></i>
                                            Upgrade ke Pro
                                        @else
                                            <i class="bi bi-diamond mr-2"></i>
                                            Pilih Enterprise
                                        @endif
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Alternative Actions -->
            <div class="bg-gray-50 rounded-2xl p-8 text-center">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Atau pilih opsi lain</h3>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('subscriptions.plans') }}"
                        class="inline-flex items-center bg-white hover:bg-gray-50 text-gray-700 font-medium py-3 px-6 rounded-xl border border-gray-300 transition-all duration-200">
                        <i class="bi bi-grid mr-2"></i>
                        Lihat Semua Paket
                    </a>
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-6 rounded-xl transition-all duration-200">
                        <i class="bi bi-house mr-2"></i>
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>

            <!-- Help Section -->
            <div class="mt-8 text-center">
                <p class="text-gray-600">
                    Butuh bantuan? <a href="mailto:support@biayaku.com"
                        class="text-blue-600 hover:text-blue-800 font-medium">Hubungi support</a>
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
