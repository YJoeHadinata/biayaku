<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Status Langganan') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-800 text-sm">
                <i class="bi bi-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
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

            @if ($subscription && $subscription->isActive())
                <!-- Active Subscription Card -->
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl p-8 text-white mb-8 shadow-xl">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                        <div class="mb-6 lg:mb-0">
                            <div class="flex items-center mb-4">
                                <div class="bg-white bg-opacity-20 rounded-full p-3 mr-4">
                                    <i class="bi bi-check-circle-fill text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold">Paket {{ $subscription->plan->name }}</h3>
                                    <p class="text-blue-100">{{ $subscription->plan->description }}</p>
                                </div>
                            </div>
                            <div class="flex items-center text-blue-100">
                                <i class="bi bi-calendar-event mr-2"></i>
                                <span>Berlaku hingga {{ $subscription->current_period_end->format('d M Y') }}</span>
                            </div>
                        </div>
                        <div class="text-center lg:text-right">
                            <div class="text-4xl font-bold mb-2">
                                {{ $subscription->plan->price > 0 ? 'Rp' . number_format($subscription->plan->price, 0, ',', '.') : 'Gratis' }}
                                @if ($subscription->plan->price > 0)
                                    <span class="text-blue-200 text-lg">/{{ $subscription->plan->interval }}</span>
                                @endif
                            </div>
                            <div class="flex flex-col sm:flex-row gap-3 mt-4">
                                <a href="{{ route('subscriptions.plans') }}"
                                    class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-semibold py-2 px-6 rounded-xl transition-all duration-200 inline-flex items-center justify-center">
                                    <i class="bi bi-grid mr-2"></i>
                                    Lihat Semua Paket
                                </a>
                                @if ($subscription->plan->slug !== 'free')
                                    <form method="POST" action="{{ route('subscriptions.cancel') }}"
                                        onsubmit="return confirm('Apakah Anda yakin ingin membatalkan langganan?')"
                                        class="inline">
                                        @csrf
                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-6 rounded-xl transition-all duration-200 inline-flex items-center justify-center">
                                            <i class="bi bi-x-circle mr-2"></i>
                                            Batalkan
                                        </button>
                                    </form>
                                @else
                                    <span
                                        class="bg-gray-500 bg-opacity-20 text-white font-semibold py-2 px-6 rounded-xl inline-flex items-center justify-center cursor-not-allowed">
                                        <i class="bi bi-shield-check mr-2"></i>
                                        Paket Free
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Subscriptions -->
                @if ($pendingSubscriptions->count() > 0)
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-8">
                        <div class="bg-gradient-to-r from-amber-50 to-orange-50 px-8 py-6 border-b border-gray-100">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                <i class="bi bi-clock-history mr-3 text-amber-600"></i>
                                Langganan Sedang Diajukan
                            </h3>
                            <p class="text-gray-600 mt-1">Permintaan langganan yang sedang menunggu konfirmasi admin</p>
                        </div>

                        <div class="p-8">
                            <div class="space-y-6">
                                @foreach ($pendingSubscriptions as $pendingSub)
                                    <div
                                        class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl p-6 border border-amber-200">
                                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                                            <div class="mb-4 lg:mb-0">
                                                <div class="flex items-center mb-3">
                                                    <div class="bg-amber-500 rounded-full p-2 mr-3">
                                                        <i class="bi bi-hourglass-split text-white text-lg"></i>
                                                    </div>
                                                    <div>
                                                        <h4 class="text-lg font-bold text-gray-900">Paket
                                                            {{ $pendingSub->plan->name }}</h4>
                                                        <p class="text-gray-600">{{ $pendingSub->plan->description }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center text-gray-500 text-sm">
                                                    <i class="bi bi-calendar-event mr-2"></i>
                                                    <span>Diajukan pada
                                                        {{ $pendingSub->created_at->format('d M Y H:i') }}</span>
                                                </div>
                                            </div>
                                            <div class="text-center lg:text-right">
                                                <div class="text-2xl font-bold text-gray-900 mb-2">
                                                    {{ $pendingSub->plan->price > 0 ? 'Rp' . number_format($pendingSub->plan->price, 0, ',', '.') : 'Gratis' }}
                                                    @if ($pendingSub->plan->price > 0)
                                                        <span
                                                            class="text-gray-500 text-sm">/{{ $pendingSub->plan->interval }}</span>
                                                    @endif
                                                </div>
                                                <div
                                                    class="bg-amber-100 text-amber-800 px-4 py-2 rounded-lg font-medium text-sm">
                                                    <i class="bi bi-clock mr-2"></i>
                                                    Menunggu Konfirmasi Admin
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Usage Stats -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-8 py-6 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            <i class="bi bi-bar-chart-line mr-3 text-blue-600"></i>
                            Penggunaan Saat Ini
                        </h3>
                        <p class="text-gray-600 mt-1">Pantau penggunaan fitur Anda</p>
                    </div>

                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <!-- Materials -->
                            <div
                                class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="bg-blue-500 rounded-full p-3">
                                        <i class="bi bi-box-seam text-white text-xl"></i>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-3xl font-bold text-blue-900">
                                            {{ auth()->user()->branch?->materials()->count() ?? 0 }}
                                        </div>
                                        <div class="text-sm text-blue-700">Material</div>
                                    </div>
                                    <div class="text-xs text-blue-600">
                                        Limit: {{ $subscription->plan->getLimit('materials') ?: 'Unlimited' }}
                                    </div>
                                    <div class="mt-3 bg-blue-200 rounded-full h-2">
                                        @php
                                            $materialLimit = $subscription->plan->getLimit('materials');
                                            $materialUsage = auth()->user()->branch?->materials()->count() ?? 0;
                                            $materialPercent = $materialLimit
                                                ? min(($materialUsage / $materialLimit) * 100, 100)
                                                : 0;
                                        @endphp
                                        <div class="bg-blue-500 h-2 rounded-full"
                                            style="width: {{ $materialPercent }}%">
                                        </div>
                                    </div>
                                </div>

                                <!-- Products -->
                                <div
                                    class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="bg-green-500 rounded-full p-3">
                                            <i class="bi bi-package text-white text-xl"></i>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-3xl font-bold text-green-900">
                                                {{ auth()->user()->branch?->products()->count() ?? 0 }}
                                            </div>
                                            <div class="text-sm text-green-700">Produk</div>
                                        </div>
                                    </div>
                                    <div class="text-xs text-green-600">
                                        Limit: {{ $subscription->plan->getLimit('products') ?: 'Unlimited' }}
                                    </div>
                                    <div class="mt-3 bg-green-200 rounded-full h-2">
                                        @php
                                            $productLimit = $subscription->plan->getLimit('products');
                                            $productUsage = auth()->user()->branch?->products()->count() ?? 0;
                                            $productPercent = $productLimit
                                                ? min(($productUsage / $productLimit) * 100, 100)
                                                : 0;
                                        @endphp
                                        <div class="bg-green-500 h-2 rounded-full"
                                            style="width: {{ $productPercent }}%">
                                        </div>
                                    </div>
                                </div>

                                <!-- Production Batches -->
                                <div
                                    class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border border-purple-200">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="bg-purple-500 rounded-full p-3">
                                            <i class="bi bi-gear text-white text-xl"></i>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-3xl font-bold text-purple-900">
                                                {{ auth()->user()->branch?->productionBatches()->count() ?? 0 }}
                                            </div>
                                            <div class="text-sm text-purple-700">Batch Produksi</div>
                                        </div>
                                    </div>
                                    <div class="text-xs text-purple-600">
                                        Limit:
                                        {{ $subscription->plan->getLimit('production_batches') ?: 'Unlimited' }}/bulan
                                    </div>
                                    <div class="mt-3 bg-purple-200 rounded-full h-2">
                                        @php
                                            $batchLimit = $subscription->plan->getLimit('production_batches');
                                            $batchUsage = auth()->user()->branch?->productionBatches()->count() ?? 0;
                                            $batchPercent = $batchLimit
                                                ? min(($batchUsage / $batchLimit) * 100, 100)
                                                : 0;
                                        @endphp
                                        <div class="bg-purple-500 h-2 rounded-full"
                                            style="width: {{ $batchPercent }}%">
                                        </div>
                                    </div>
                                </div>

                                <!-- Users -->
                                <div
                                    class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-6 border border-orange-200">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="bg-orange-500 rounded-full p-3">
                                            <i class="bi bi-people text-white text-xl"></i>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-3xl font-bold text-orange-900">
                                                {{ auth()->user()->branch?->users()->count() ?? 0 }}
                                            </div>
                                            <div class="text-sm text-orange-700">User</div>
                                        </div>
                                    </div>
                                    <div class="text-xs text-orange-600">
                                        Limit: {{ $subscription->plan->getLimit('users_per_branch') ?: 'Unlimited' }}
                                    </div>
                                    <div class="mt-3 bg-orange-200 rounded-full h-2">
                                        @php
                                            $userLimit = $subscription->plan->getLimit('users_per_branch');
                                            $userUsage = auth()->user()->branch?->users()->count() ?? 0;
                                            $userPercent = $userLimit ? min(($userUsage / $userLimit) * 100, 100) : 0;
                                        @endphp
                                        <div class="bg-orange-500 h-2 rounded-full"
                                            style="width: {{ $userPercent }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif ($subscription && $subscription->isPending())
                    <!-- Pending Subscription Card -->
                    <div
                        class="bg-gradient-to-r from-amber-400 to-orange-500 rounded-2xl p-8 text-white mb-8 shadow-xl">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                            <div class="mb-6 lg:mb-0">
                                <div class="flex items-center mb-4">
                                    <div class="bg-white bg-opacity-20 rounded-full p-3 mr-4">
                                        <i class="bi bi-clock-history text-2xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold">Menunggu Konfirmasi</h3>
                                        <p class="text-amber-100">Subscription paket {{ $subscription->plan->name }}
                                            sedang diproses</p>
                                    </div>
                                </div>
                                <div class="flex items-center text-amber-100">
                                    <i class="bi bi-calendar-event mr-2"></i>
                                    <span>Diajukan pada {{ $subscription->created_at->format('d M Y H:i') }}</span>
                                </div>
                            </div>
                            <div class="text-center lg:text-right">
                                <div class="text-4xl font-bold mb-2">
                                    {{ $subscription->plan->price > 0 ? 'Rp' . number_format($subscription->plan->price, 0, ',', '.') : 'Gratis' }}
                                    @if ($subscription->plan->price > 0)
                                        <span
                                            class="text-amber-200 text-lg">/{{ $subscription->plan->interval }}</span>
                                    @endif
                                </div>
                                <div class="bg-white bg-opacity-20 text-white px-4 py-2 rounded-xl font-medium">
                                    <i class="bi bi-hourglass-split mr-2"></i>
                                    Status: Menunggu Konfirmasi Admin
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- No Active Subscription -->
                    <div
                        class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-2xl p-12 text-center shadow-lg border border-gray-100">
                        <div class="max-w-md mx-auto">
                            <div
                                class="bg-blue-100 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-6">
                                <i class="bi bi-info-circle text-blue-600 text-4xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">
                                Belum Ada Langganan Aktif
                            </h3>
                            <p class="text-gray-600 mb-8 text-lg">
                                Tingkatkan produktivitas bisnis Anda dengan paket premium yang menawarkan fitur-fitur
                                canggih untuk pengelolaan biaya produksi.
                            </p>
                            <div class="space-y-4">
                                <a href="{{ route('subscriptions.plans') }}"
                                    class="inline-flex items-center bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold py-4 px-8 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg">
                                    <i class="bi bi-rocket-launch mr-3"></i>
                                    Jelajahi Paket Langganan
                                </a>
                                <p class="text-sm text-gray-500">
                                    Mulai dengan paket Free untuk mencoba fitur dasar
                                </p>
                            </div>
                        </div>
                    </div>
            @endif
        </div>
    </div>
</x-app-layout>
