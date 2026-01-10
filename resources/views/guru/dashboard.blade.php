@extends('layouts.admin')

@section('content')
    <div class="grid grid-cols-1 gap-8">
        <!-- Recent Inputs List -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-lg font-bold text-gray-800">Riwayat Input Terakhir <span
                            class="ml-2 px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Live Data</span></h2>
                </div>
                <span class="text-xs text-gray-400">5 Terakhir</span>
            </div>

            <div class="space-y-4">
                @forelse($riwayatInput as $poin)
                    <div class="flex items-start p-4 border border-gray-100 rounded-lg hover:shadow-md transition bg-white">
                        <!-- Avatar -->
                        <div class="flex-shrink-0 mr-4">
                            <div
                                class="w-10 h-10 rounded bg-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                                {{ substr($poin->siswa->nama, 0, 1) }}
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mr-2">
                                        {{ $poin->siswa->kelas }}
                                    </span>
                                    <h3 class="text-sm font-bold text-gray-900 truncate inline-block">{{ $poin->siswa->nama }}
                                    </h3>
                                </div>

                                @php
                                    $badgeColor = 'bg-gray-100 text-gray-800';
                                    if ($poin->jenis == 'prestasi')
                                        $badgeColor = 'bg-green-100 text-green-800';
                                    elseif ($poin->jenis == 'pelanggaran')
                                        $badgeColor = 'bg-red-100 text-red-800';
                                @endphp
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeColor }}">
                                    {{ strtoupper($poin->jenis) }}
                                </span>
                            </div>

                            <div class="mt-1 flex items-center gap-2">
                                @if($poin->jenis == 'pelanggaran')
                                    <span class="text-xs text-red-500 font-semibold">{{ $poin->kategori }}</span>
                                @else
                                    <span class="text-xs text-green-500 font-semibold">Prestasi</span>
                                @endif
                                <span class="text-gray-300">•</span>
                                <p class="text-sm text-gray-600">{{Str::limit($poin->keterangan, 80)}}</p>
                            </div>

                            <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ \Carbon\Carbon::parse($poin->tanggal)->translatedFormat('d F Y') }}
                                <span class="mx-1">•</span>
                                <span class="font-bold {{ $poin->jenis == 'prestasi' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $poin->jenis == 'prestasi' ? '+' : '-' }}{{ $poin->jumlah }} Poin
                                </span>
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        Belum ada data input poin terbaru.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection