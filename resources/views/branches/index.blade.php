<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Cabang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">Daftar Cabang</h3>
                        <a href="{{ route('admin.branches.create') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold inline-flex items-center transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="bi bi-plus-circle-fill mr-2"></i>
                            Tambah Cabang
                        </a>
                    </div>

                    <!-- Search Form -->
                    <form method="GET" class="mb-6">
                        <div class="flex">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari cabang..."
                                class="flex-1 rounded-l-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <button type="submit"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-r-md">
                                <i class="bi bi-search"></i> Cari
                            </button>
                        </div>
                    </form>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-2 text-left">ID</th>
                                    <th class="px-4 py-2 text-left">Nama</th>
                                    <th class="px-4 py-2 text-left">Alamat</th>
                                    <th class="px-4 py-2 text-left">Telepon</th>
                                    <th class="px-4 py-2 text-left">Email</th>
                                    <th class="px-4 py-2 text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($branches as $branch)
                                    <tr class="border-t">
                                        <td class="px-4 py-2">{{ $branch->id }}</td>
                                        <td class="px-4 py-2">{{ $branch->name }}</td>
                                        <td class="px-4 py-2">{{ $branch->address ?: '-' }}</td>
                                        <td class="px-4 py-2">{{ $branch->phone ?: '-' }}</td>
                                        <td class="px-4 py-2">{{ $branch->email ?: '-' }}</td>
                                        <td class="px-4 py-2">
                                            <a href="{{ route('admin.branches.edit', $branch) }}"
                                                class="text-blue-600 hover:text-blue-900 mr-2">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form method="POST"
                                                action="{{ route('admin.branches.destroy', $branch) }}" class="inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus cabang ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-2 text-center text-gray-500">Tidak ada
                                            cabang ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $branches->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
