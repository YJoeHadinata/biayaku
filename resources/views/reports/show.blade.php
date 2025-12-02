<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan ' . ucfirst($type)) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-medium">
                                Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} -
                                {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
                            </h3>
                        </div>
                        <div class="space-x-2">
                            <a href="{{ route('reports.export.excel', ['type' => $type, 'start_date' => $startDate, 'end_date' => $endDate]) }}"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                                <i class="bi bi-file-earmark-excel"></i> Export Excel
                            </a>
                            <a href="{{ route('reports.export.pdf', ['type' => $type, 'start_date' => $startDate, 'end_date' => $endDate]) }}"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">
                                <i class="bi bi-file-earmark-pdf"></i> Export PDF
                            </a>
                            <a href="{{ route('reports.index') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>

                    @if ($type === 'hpp')
                        @include('reports.partials.hpp_report')
                    @elseif($type === 'operational')
                        @include('reports.partials.operational_report')
                    @elseif($type === 'miscellaneous')
                        @include('reports.partials.miscellaneous_report')
                    @elseif($type === 'summary')
                        @include('reports.partials.summary_report')
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
