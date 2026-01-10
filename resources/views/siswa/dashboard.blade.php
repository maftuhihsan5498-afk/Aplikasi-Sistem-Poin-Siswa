@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    <!-- Profile Card (Top) -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8 border border-gray-100">
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 h-32 relative">
             <div class="absolute bottom-0 left-0 w-full h-1/2 bg-white/10 backdrop-blur-sm"></div>
        </div>
        <div class="px-8 pb-8">
            <div class="relative flex justify-between items-end -mt-12 mb-6">
                <div class="flex items-end gap-6">
                    <div class="w-24 h-24 rounded-2xl bg-white p-1 shadow-md">
                        <div class="w-full h-full bg-indigo-100 rounded-xl flex items-center justify-center text-3xl font-bold text-indigo-600">
                            {{ substr($siswa->nama, 0, 1) }}
                        </div>
                    </div>
                    <div class="mb-2">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $siswa->nama }}</h1>
                         <p class="text-gray-500 font-medium">{{ $siswa->kelas }} • {{ $siswa->nis }}</p>
                    </div>
                </div>
                 <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 text-center min-w-[120px] mb-2 hidden sm:block">
                    <div class="text-xs uppercase font-bold text-gray-400 tracking-wider">Total Poin</div>
                     @php
                        $poin = $totalAkhir;
                        $poinColor = $poin >= 100 ? 'text-emerald-500' : 'text-indigo-600'; 
                        if ($poin < 0) $poinColor = 'text-red-500';
                    @endphp
                    <div class="text-3xl font-bold {{ $poinColor }}">{{ $poin }}</div>
                </div>
            </div>

            <!-- Stats Mobile -->
             <div class="sm:hidden bg-gray-50 rounded-xl p-4 border border-gray-100 text-center mb-6">
                <div class="text-xs uppercase font-bold text-gray-400 tracking-wider">Total Poin</div>
                <div class="text-3xl font-bold {{ $poinColor }}">{{ $poin }}</div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Wali Kelas -->
                <div class="p-4 rounded-xl bg-blue-50 border border-blue-100">
                    <div class="text-xs font-bold text-blue-400 uppercase tracking-wider mb-1">Wali Kelas</div>
                    <div class="font-semibold text-blue-900">{{ $siswa->wali_kelas ?? '-' }}</div>
                </div>
                <!-- Kontak Ortu -->
                 <div class="p-4 rounded-xl bg-purple-50 border border-purple-100">
                    <div class="text-xs font-bold text-purple-400 uppercase tracking-wider mb-1">Kontak Orang Tua</div>
                    <div class="font-semibold text-purple-900 font-mono">{{ $siswa->kontak_orang_tua ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- History Section -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
             <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Riwayat Poin
            </h3>
             <div class="flex gap-2">
                 <span class="px-2 py-1 rounded text-xs font-semibold bg-emerald-100 text-emerald-700">
                    {{ $totalPrestasi }} Prestasi
                 </span>
                 <span class="px-2 py-1 rounded text-xs font-semibold bg-red-100 text-red-700">
                    {{ $totalPelanggaran }} Pelanggaran
                 </span>
             </div>
        </div>

        <div class="p-0">
            @forelse($riwayat as $poin)
                <div class="p-6 border-b border-gray-100 last:border-0 hover:bg-gray-50 transition flex flex-col sm:flex-row gap-4">
                    <div class="flex-shrink-0">
                         <div class="w-12 h-12 rounded-full flex items-center justify-center {{ $poin->jenis == 'prestasi' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">
                             @if($poin->jenis == 'prestasi')
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                             @else
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                             @endif
                         </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-1">
                             <h4 class="font-bold text-gray-800 text-lg">{{ $poin->jenis == 'prestasi' ? 'Prestasi Akademik/Non-Akademik' : 'Pelanggaran Tata Tertib' }}</h4>
                             <span class="font-bold {{ $poin->jenis == 'prestasi' ? 'text-emerald-500' : 'text-red-500' }}">
                                {{ $poin->jenis == 'prestasi' ? '+' : '-' }}{{ $poin->jumlah }}
                             </span>
                        </div>
                        <p class="text-gray-600 mb-2">{{ $poin->keterangan }}</p>
                        <div class="flex items-center gap-3 text-xs text-gray-400">
                             <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $poin->tanggal->isoFormat('dddd, D MMMM Y') }}
                             </span>
                             <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                             <span class="uppercase tracking-wider font-semibold {{ $poin->jenis == 'prestasi' ? 'text-emerald-500' : 'text-red-500' }}">{{ $poin->kategori }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-16 text-center">
                    <div class="bg-gray-50 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 011.414.586l5.414 5.414a1 1 0 01.586 1.414V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-gray-900 font-bold text-lg">Belum Ada Data</h3>
                    <p class="text-gray-500 mt-2 max-w-sm mx-auto">Riwayat poin prestasi maupun pelanggaran belum tersedia untuk saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection