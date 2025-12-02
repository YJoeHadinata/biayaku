<x-admin-layout>
    <div class="p-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
            <p class="text-gray-600 mt-2">Ringkasan sistem dan aktivitas terbaru</p>
        </div>
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="bi bi-people-fill text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="bi bi-box-seam text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Materials</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_materials'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="bi bi-cup-hot text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Products</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_products'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="bi bi-gear text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Batches</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_batches'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Summary -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Ringkasan Keuangan</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <div class="text-2xl font-bold text-green-600 mb-1">
                        Rp {{ number_format($stats['total_hpp'], 0, ',', '.') }}
                    </div>
                    <div class="text-sm text-gray-600">Total HPP Produksi</div>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600 mb-1">
                        Rp {{ number_format($stats['total_operational'], 0, ',', '.') }}
                    </div>
                    <div class="text-sm text-gray-600">Total Biaya Operasional</div>
                </div>
                <div class="text-center p-4 bg-red-50 rounded-lg">
                    <div class="text-2xl font-bold text-red-600 mb-1">
                        Rp {{ number_format($stats['total_miscellaneous'], 0, ',', '.') }}
                    </div>
                    <div class="text-sm text-gray-600">Total Biaya Lain-lain</div>
                </div>
            </div>
            <div class="border-t pt-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-gray-900 mb-1">
                        Rp
                        {{ number_format($stats['total_hpp'] + $stats['total_operational'] + $stats['total_miscellaneous'], 0, ',', '.') }}
                    </div>
                    <div class="text-sm text-gray-600">Total Pengeluaran Keseluruhan</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Recent Production Batches -->
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Batch Produksi Terbaru</h3>
                        <a href="{{ route('production-batches.index') }}"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors duration-200">
                            Lihat Semua →
                        </a>
                    </div>
                    <div class="space-y-4">
                        @forelse($stats['recent_batches'] as $batch)
                            <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $batch->product->nama }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ $batch->tanggal_produksi->format('d M Y') }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold text-gray-900">
                                        Rp {{ number_format($batch->total_hpp_dengan_tambahan, 0, ',', '.') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $batch->jumlah_output }} {{ $batch->product->unit_output }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <i class="bi bi-gear text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">Belum ada batch produksi</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Users -->
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">User Terbaru</h3>
                        <a href="{{ route('admin.users') }}"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors duration-200">
                            Kelola Users →
                        </a>
                    </div>
                    <div class="space-y-4">
                        @forelse($stats['recent_users'] as $user)
                            <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                                        <i class="bi bi-person text-gray-600"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if ($user->role === 'super_admin') bg-red-100 text-red-800
                                @elseif($user->role === 'admin') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <i class="bi bi-people text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">Belum ada user</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Actions</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('admin.users.create') }}"
                    class="flex flex-col items-center p-6 border-2 border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 group">
                    <i
                        class="bi bi-person-plus text-3xl text-blue-500 mb-3 group-hover:scale-110 transition-transform duration-200"></i>
                    <span class="text-sm font-medium text-gray-700">Tambah User</span>
                </a>
                <a href="{{ route('materials.create') }}"
                    class="flex flex-col items-center p-6 border-2 border-gray-200 rounded-lg hover:border-green-300 hover:bg-green-50 transition-all duration-200 group">
                    <i
                        class="bi bi-box-seam text-3xl text-green-500 mb-3 group-hover:scale-110 transition-transform duration-200"></i>
                    <span class="text-sm font-medium text-gray-700">Tambah Material</span>
                </a>
                <a href="{{ route('products.create') }}"
                    class="flex flex-col items-center p-6 border-2 border-gray-200 rounded-lg hover:border-yellow-300 hover:bg-yellow-50 transition-all duration-200 group">
                    <i
                        class="bi bi-cup-hot text-3xl text-yellow-500 mb-3 group-hover:scale-110 transition-transform duration-200"></i>
                    <span class="text-sm font-medium text-gray-700">Tambah Produk</span>
                </a>
                <a href="{{ route('reports.index') }}"
                    class="flex flex-col items-center p-6 border-2 border-gray-200 rounded-lg hover:border-purple-300 hover:bg-purple-50 transition-all duration-200 group">
                    <i
                        class="bi bi-file-earmark-bar-graph text-3xl text-purple-500 mb-3 group-hover:scale-110 transition-transform duration-200"></i>
                    <span class="text-sm font-medium text-gray-700">Lihat Laporan</span>
                </a>
            </div>
        </div>
    </div>
</x-admin-layout>
