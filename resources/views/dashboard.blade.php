<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
                        <p class="text-gray-600 mt-1">Selamat datang kembali, {{ auth()->user()->name }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Cabang</p>
                            <p class="font-semibold text-gray-900">
                                {{ auth()->user()->branch->name ?? 'Tidak ada cabang' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-xl">
                            <i class="bi bi-calendar-day text-blue-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-600 mb-1">Biaya Hari Ini</h3>
                            <p class="text-2xl font-bold text-blue-600">
                                Rp {{ number_format($totalToday, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-xl">
                            <i class="bi bi-calendar-month text-green-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-600 mb-1">Biaya Bulan Ini</h3>
                            <p class="text-2xl font-bold text-green-600">
                                Rp {{ number_format($totalMonth, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-xl">
                            <i class="bi bi-calendar-event text-purple-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-600 mb-1">Biaya Tahun Ini</h3>
                            <p class="text-2xl font-bold text-purple-600">
                                Rp {{ number_format($totalYtd, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subscription Status -->
            @php
                $subscription = auth()->user()->activeSubscription();
            @endphp
            @if ($subscription)
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6 mb-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-4 bg-green-500 rounded-xl shadow-lg">
                                <i class="bi bi-check-circle-fill text-white text-2xl"></i>
                            </div>
                            <div class="ml-6">
                                <h3 class="text-xl font-bold text-green-900 mb-1">
                                    Paket {{ $subscription->plan->name }}
                                </h3>
                                <p class="text-green-700 font-medium mb-1">
                                    {{ $subscription->plan->formatted_price }}/{{ $subscription->plan->interval }}
                                </p>
                                <p class="text-sm text-green-600">
                                    <i class="bi bi-calendar-check mr-1"></i>
                                    Berlaku hingga {{ $subscription->current_period_end->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('subscriptions.status') }}"
                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold inline-flex items-center transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <i class="bi bi-gear mr-2"></i>
                                Kelola Subscription
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-xl p-6 mb-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-4 bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl shadow-lg">
                                <i class="bi bi-star-fill text-white text-2xl"></i>
                            </div>
                            <div class="ml-6">
                                <h3 class="text-xl font-bold text-amber-900 mb-1">Upgrade untuk Fitur Premium</h3>
                                <p class="text-amber-700">Dapatkan akses ke export data, multi-branch management, dan
                                    fitur lainnya</p>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('subscriptions.plans') }}"
                                class="bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white px-6 py-3 rounded-xl font-semibold inline-flex items-center transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 hover:scale-105">
                                <i class="bi bi-rocket-launch-fill mr-2"></i>
                                Lihat Paket Premium
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Detailed Summary -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Cost Breakdown -->
                <div class="bg-white rounded-xl shadow-sm border">
                    <div class="p-6">
                        <div class="flex items-center mb-6">
                            <div class="p-3 bg-indigo-100 rounded-xl">
                                <i class="bi bi-pie-chart text-indigo-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Breakdown Biaya Hari Ini</h3>
                                <p class="text-sm text-gray-600">Detail pengeluaran hari ini</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                                    <span class="text-gray-700 font-medium">HPP Produksi</span>
                                </div>
                                <span class="font-bold text-blue-600">Rp
                                    {{ number_format($hppToday, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                    <span class="text-gray-700 font-medium">Biaya Operasional</span>
                                </div>
                                <span class="font-bold text-green-600">Rp
                                    {{ number_format($operationalToday, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-purple-500 rounded-full mr-3"></div>
                                    <span class="text-gray-700 font-medium">Biaya Lain-lain</span>
                                </div>
                                <span class="font-bold text-purple-600">Rp
                                    {{ number_format($miscellaneousToday, 0, ',', '.') }}</span>
                            </div>
                            <div class="border-t pt-4 mt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-gray-900">Total</span>
                                    <span class="text-xl font-bold text-indigo-600">Rp
                                        {{ number_format($totalToday, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div class="bg-white rounded-xl shadow-sm border">
                    <div class="p-6">
                        <div class="flex items-center mb-6">
                            <div class="p-3 bg-emerald-100 rounded-xl">
                                <i class="bi bi-receipt text-emerald-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Transaksi Terbaru</h3>
                                <p class="text-sm text-gray-600">Aktivitas pengeluaran terkini</p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            @forelse($recentTransactions as $transaction)
                                <div
                                    class="flex justify-between items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900 mb-1">{{ $transaction['description'] }}
                                        </p>
                                        <div class="flex items-center space-x-3">
                                            <p class="text-sm text-gray-500">
                                                <i class="bi bi-calendar mr-1"></i>
                                                {{ $transaction['date']->format('d M Y') }}
                                            </p>
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if ($transaction['type'] === 'HPP') bg-blue-100 text-blue-800
                                                @elseif($transaction['type'] === 'Operasional') bg-green-100 text-green-800
                                                @else bg-purple-100 text-purple-800 @endif">
                                                {{ $transaction['type'] }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-lg text-gray-900">
                                            Rp {{ number_format($transaction['amount'], 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <i class="bi bi-receipt text-4xl text-gray-300 mb-3"></i>
                                    <p class="text-gray-500 font-medium">Belum ada transaksi</p>
                                    <p class="text-sm text-gray-400">Transaksi akan muncul di sini setelah Anda
                                        menambah data</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6">
                    <div class="flex items-center mb-6">
                        <div class="p-3 bg-cyan-100 rounded-xl">
                            <i class="bi bi-graph-up text-cyan-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-semibold text-gray-900">Tren Biaya 30 Hari Terakhir</h3>
                            <p class="text-sm text-gray-600">Analisis pengeluaran selama sebulan terakhir</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <canvas id="costChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('costChart').getContext('2d');
        const costChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartData['dates']),
                datasets: [{
                    label: 'HPP Produksi',
                    data: @json($chartData['hpp']),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4
                }, {
                    label: 'Biaya Operasional',
                    data: @json($chartData['operational']),
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4
                }, {
                    label: 'Biaya Lain-lain',
                    data: @json($chartData['miscellaneous']),
                    borderColor: 'rgb(147, 51, 234)',
                    backgroundColor: 'rgba(147, 51, 234, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
