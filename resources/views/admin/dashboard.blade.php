@extends('layouts.admin')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card: Total Pelanggaran -->
    <div class="bg-[#009ca6] rounded-xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10">
            <p class="text-sm font-medium text-white/90">Total Pelanggaran</p>
            <h3 class="text-4xl font-bold mt-2">{{ $totalPelanggaran }}</h3>
        </div>
        <div class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/20 p-3 rounded-lg">
            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
    </div>

    <!-- Stat Card: Siswa Terlibat -->
    <div class="bg-[#8b5cf6] rounded-xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10">
            <p class="text-sm font-medium text-white/90">Siswa Terlibat</p>
            <h3 class="text-4xl font-bold mt-2">{{ $siswaTerlibat }}</h3>
        </div>
        <div class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/20 p-3 rounded-lg">
            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </div>
    </div>

    <!-- Stat Card: Menunggu Tindak Lanjut -->
    <div class="bg-[#f59e0b] rounded-xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10">
            <p class="text-sm font-medium text-white/90">Menunggu Tindak Lanjut</p>
            <h3 class="text-4xl font-bold mt-2">{{ $menungguTindakLanjut }}</h3>
        </div>
        <div class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/20 p-3 rounded-lg">
            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    </div>

    <!-- Stat Card: Selesai -->
    <div class="bg-[#10b981] rounded-xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10">
            <p class="text-sm font-medium text-white/90">Selesai</p>
            <h3 class="text-4xl font-bold mt-2">{{ $selesai }}</h3>
        </div>
        <div class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/20 p-3 rounded-lg">
            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Violations List -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                </svg>
                <h2 class="text-lg font-bold text-gray-800">Pelanggaran Terbaru <span class="ml-2 px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Live Data</span></h2>
            </div>
            <span class="text-xs text-gray-400">5 Terakhir dari Database</span>
        </div>

        <div class="space-y-4">
            @forelse($recentPelanggaran as $poin)
            <div class="flex items-start p-4 border border-gray-100 rounded-lg hover:shadow-md transition bg-white">
                <!-- Avatar -->
                <div class="flex-shrink-0 mr-4">
                    <div class="w-10 h-10 rounded bg-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                        {{ substr($poin->siswa->nama, 0, 1) }}
                    </div>
                </div>
                
                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mr-2">
                                {{ $poin->siswa->nis }}
                            </span>
                            <h3 class="text-sm font-bold text-gray-900 truncate inline-block">{{ $poin->siswa->nama }}</h3>
                        </div>
                        
                        @php
                            $badgeColor = 'bg-gray-100 text-gray-800';
                            if(strtolower($poin->kategori) == 'ringan') $badgeColor = 'bg-green-100 text-green-800';
                            elseif(strtolower($poin->kategori) == 'sedang') $badgeColor = 'bg-yellow-100 text-yellow-800';
                            elseif(strtolower($poin->kategori) == 'berat') $badgeColor = 'bg-red-100 text-red-800';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeColor }}">
                            {{ strtoupper($poin->kategori) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">{{Str::limit($poin->keterangan, 80)}}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::parse($poin->tanggal)->translatedFormat('d F Y') }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                Belum ada data pelanggaran.
            </div>
            @endforelse
        </div>
    </div>

    <!-- Stats Chart -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Statistik Kategori
        </h2>

        <div class="h-64 flex items-end justify-center gap-6 px-4 pb-4">
            @php
                $maxVal = max(1, max($chartData)); 
                $heightScale = 200 / $maxVal; // Max height 200px
            @endphp

            <!-- Ringan -->
            <div class="flex flex-col items-center group">
                <div class="w-12 bg-green-500 rounded-t-lg transition-all duration-500 group-hover:bg-green-600 relative" style="height: {{ $chartData['Ringan'] * $heightScale }}px; min-height: 4px;">
                    <span class="absolute -top-6 left-1/2 -translate-x-1/2 text-xs font-bold text-gray-600">{{ $chartData['Ringan'] }}</span>
                </div>
                <span class="mt-2 text-xs font-medium text-gray-500">Ringan</span>
            </div>

            <!-- Sedang -->
            <div class="flex flex-col items-center group">
                <div class="w-12 bg-yellow-500 rounded-t-lg transition-all duration-500 group-hover:bg-yellow-600 relative" style="height: {{ $chartData['Sedang'] * $heightScale }}px; min-height: 4px;">
                     <span class="absolute -top-6 left-1/2 -translate-x-1/2 text-xs font-bold text-gray-600">{{ $chartData['Sedang'] }}</span>
                </div>
                <span class="mt-2 text-xs font-medium text-gray-500">Sedang</span>
            </div>

            <!-- Berat -->
            <div class="flex flex-col items-center group">
                <div class="w-12 bg-red-500 rounded-t-lg transition-all duration-500 group-hover:bg-red-600 relative" style="height: {{ $chartData['Berat'] * $heightScale }}px; min-height: 4px;">
                     <span class="absolute -top-6 left-1/2 -translate-x-1/2 text-xs font-bold text-gray-600">{{ $chartData['Berat'] }}</span>
                </div>
                <span class="mt-2 text-xs font-medium text-gray-500">Berat</span>
            </div>
        </div>

        <div class="mt-6 space-y-3">
            <div class="flex justify-between items-center text-sm">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    <span class="text-gray-600">Ringan</span>
                </div>
                <span class="font-bold text-gray-900">{{ $chartData['Ringan'] }}</span>
            </div>
             <div class="flex justify-between items-center text-sm">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                    <span class="text-gray-600">Sedang</span>
                </div>
                <span class="font-bold text-gray-900">{{ $chartData['Sedang'] }}</span>
            </div>
             <div class="flex justify-between items-center text-sm">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                    <span class="text-gray-600">Berat</span>
                </div>
                <span class="font-bold text-gray-900">{{ $chartData['Berat'] }}</span>
            </div>
        </div>
    </div>
</div>
@endsection