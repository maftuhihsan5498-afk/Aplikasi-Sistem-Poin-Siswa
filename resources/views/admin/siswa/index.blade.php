@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto" x-data="{
                showModal: false,
                selectedSiswa: null,

                openModal(siswa) {
                    this.selectedSiswa = siswa;
                    this.showModal = true;
                },
                closeModal() {
                    this.showModal = false;
                },
                get filteredViolations() {
                    if (!this.selectedSiswa || !this.selectedSiswa.poins) return [];
                    return this.selectedSiswa.poins.filter(p => p.jenis === 'pelanggaran');
                },
                get filteredAchievements() {
                    if (!this.selectedSiswa || !this.selectedSiswa.poins) return [];
                    return this.selectedSiswa.poins.filter(p => p.jenis === 'prestasi');
                },
                get totalPoints() {
                     if (!this.selectedSiswa || !this.selectedSiswa.poins) return 0;
                     // Calculate total points: Achievement - Violation
                     let achievements = this.selectedSiswa.poins.filter(p => p.jenis === 'prestasi').reduce((sum, p) => p.jumlah + sum, 0);
                     let violations = this.selectedSiswa.poins.filter(p => p.jenis === 'pelanggaran').reduce((sum, p) => p.jumlah + sum, 0);
                     return achievements - violations;
                },
                get violationCount() {
                    return this.filteredViolations.length;
                },
                get achievementCount() {
                    return this.filteredAchievements.length;
                }
            }">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <span class="text-purple-800">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </span>
                Data Siswa & Poin
            </h2>

            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.siswa.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition shadow-sm font-medium">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Siswa
                </a>
            @endif
        </div>

        <!-- Search & Filter Bar -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
            <form action="{{ url()->current() }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-lg leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-1 focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                        placeholder="Cari nama siswa atau NIS...">
                </div>

                <div class="w-full md:w-64 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                    </div>
                    <select name="kelas" onchange="this.form.submit()"
                        class="block w-full pl-10 pr-10 py-2.5 border border-gray-200 rounded-lg leading-5 bg-gray-50 focus:outline-none focus:bg-white focus:ring-1 focus:ring-purple-500 focus:border-purple-500 sm:text-sm appearance-none cursor-pointer">
                        <option value="">Semua Kelas</option>
                        @foreach($kelas_list as $kelas)
                            <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </form>
        </div>

        <p class="text-gray-500 text-sm mb-4">Menampilkan {{ $siswas->total() }} siswa</p>

        <!-- Student Cards -->
        <div class="space-y-4">
            @forelse($siswas as $siswa)
                <div
                    class="bg-white rounded-lg p-4 shadow-sm border border-gray-100 flex items-center hover:shadow-md transition duration-200 group">
                    <!-- Green Left Border -->
                    <div class="w-1.5 h-16 bg-emerald-400 rounded-full mr-4"></div>

                    <!-- Avatar -->
                    <div class="flex-shrink-0">
                        <div
                            class="h-12 w-12 rounded-full bg-emerald-500 flex items-center justify-center text-white font-bold text-lg">
                            {{ substr($siswa->nama, 0, 1) }}
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="ml-4 flex-1">
                        <h3 class="text-base font-bold text-gray-800">{{ $siswa->nama }}</h3>
                        <div class="flex items-center text-gray-500 text-sm mt-0.5 font-medium">
                            <span
                                class="bg-gray-100 px-1.5 rounded text-xs text-gray-600 mr-2 font-bold">{{ $siswa->kelas }}</span>
                            <span>• {{ $siswa->nis }}</span>
                        </div>
                    </div>

                    <!-- Points -->
                    <div class="text-right px-4 border-r border-gray-100 mr-4">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Poin</div>
                        @php
                            $poin = $siswa->totalPoinAkhir();
                            $poinColor = $poin >= 100 ? 'text-emerald-500' : 'text-emerald-500';
                            if ($poin < 0)
                                $poinColor = 'text-red-500';
                        @endphp
                        <div class="text-2xl font-bold {{ $poinColor }}">{{ $poin }}</div>
                    </div>

                    <!-- Action -->
                    <div class="flex items-center gap-2">
                        <button @click="openModal({{ $siswa->load('poins') }})"
                            class="p-2 text-gray-400 hover:text-purple-600 transition rounded-full hover:bg-gray-50 inline-block focus:outline-none"
                            title="Lihat Detail">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>

                        @if(Auth::user()->role === 'admin')
                            <form action="{{ route('admin.siswa.destroy', $siswa->id) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa ini? Data user terkait juga akan dihapus.');"
                                class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="p-2 text-gray-400 hover:text-red-600 transition rounded-full hover:bg-red-50 focus:outline-none"
                                    title="Hapus Siswa">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <p class="text-gray-500">Tidak ada data siswa ditemukan.</p>
                </div>
            @endforelse

            <div class="mt-6">
                {{ $siswas->links() }}
            </div>
        </div>

        <!-- Detail Modal -->
        <template x-if="selectedSiswa">
            <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
                aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-center justify-center min-h-screen px-4 py-6 text-center sm:p-0">

                    <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                        @click="closeModal"></div>

                    <div x-show="showModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">

                        <!-- Header -->
                        <div class="px-6 py-4 flex justify-between items-center border-b border-gray-100">
                            <h3 class="text-lg leading-6 font-bold text-gray-900 flex items-center gap-2">
                                <span>📋</span> Detail Siswa
                            </h3>
                            <button @click="closeModal" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Card Content -->
                        <div class="p-6">
                            <!-- Blue Identity Card -->
                            <div
                                class="bg-gradient-to-br from-cyan-400 to-blue-600 rounded-2xl p-6 text-center text-white shadow-lg mb-6 relative overflow-hidden">
                                <!-- Decoration -->
                                <div
                                    class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 bg-white opacity-10 rounded-full blur-xl">
                                </div>
                                <div
                                    class="absolute bottom-0 left-0 -ml-4 -mb-4 w-20 h-20 bg-black opacity-10 rounded-full blur-xl">
                                </div>

                                <div class="relative z-10 text-center">
                                    <div
                                        class="mx-auto w-16 h-16 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-2xl font-bold border-2 border-white/30 mb-3">
                                        <span x-text="selectedSiswa.nama.charAt(0)"></span>
                                    </div>
                                    <h2 class="text-xl font-bold tracking-tight" x-text="selectedSiswa.nama"></h2>
                                    <p class="text-blue-100 text-sm font-medium mt-1">
                                        <span x-text="selectedSiswa.kelas"></span> • <span
                                            x-text="selectedSiswa.nis"></span>
                                    </p>

                                    <div class="flex justify-center gap-4 mt-6">
                                        <div class="bg-white/20 backdrop-blur-md rounded-lg p-2.5 px-3 min-w-[30%]">
                                            <div class="text-[10px] font-bold text-blue-100 uppercase tracking-widest mb-1">
                                                Total Poin</div>
                                            <div class="text-2xl font-bold leading-none" x-text="totalPoints"></div>
                                        </div>
                                        <div class="bg-white/20 backdrop-blur-md rounded-lg p-2.5 px-3 min-w-[30%]">
                                            <div class="text-[10px] font-bold text-blue-100 uppercase tracking-widest mb-1">
                                                Prestasi</div>
                                            <div class="text-2xl font-bold leading-none" x-text="achievementCount"></div>
                                        </div>
                                        <div class="bg-white/20 backdrop-blur-md rounded-lg p-2.5 px-3 min-w-[30%]">
                                            <div class="text-[10px] font-bold text-blue-100 uppercase tracking-widest mb-1">
                                                Pelanggaran</div>
                                            <div class="text-2xl font-bold leading-none" x-text="violationCount"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Riwayat Prestasi Section -->
                            <div class="mb-6" x-show="filteredAchievements.length > 0">
                                <h4
                                    class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-1">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Riwayat Prestasi
                                </h4>
                                <div class="max-h-48 overflow-y-auto space-y-2">
                                    <template x-for="achievement in filteredAchievements" :key="achievement.id">
                                        <div
                                            class="flex gap-3 p-3 rounded-lg border border-gray-100 hover:bg-emerald-50 transition">
                                            <div class="flex-shrink-0 mt-0.5">
                                                <div class="w-2 h-2 rounded-full bg-emerald-400 mt-1.5"></div>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-800"
                                                    x-text="achievement.keterangan"></p>
                                                <p class="text-xs text-gray-400 mt-0.5"
                                                    x-text="new Date(achievement.tanggal).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })">
                                                </p>
                                            </div>
                                            <div class="ml-auto text-right">
                                                <span class="text-xs font-bold text-emerald-500"
                                                    x-text="'+' + achievement.jumlah"></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Riwayat Pelanggaran Section -->
                            <div class="mb-4">
                                <h4
                                    class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-1">
                                    <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    Riwayat Pelanggaran
                                </h4>

                                <template x-if="filteredViolations.length === 0">
                                    <div
                                        class="border-2 border-dashed border-gray-100 rounded-xl p-8 text-center bg-gray-50/50">
                                        <div
                                            class="bg-yellow-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <svg class="w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                            </svg>
                                        </div>
                                        <h5 class="text-gray-800 font-bold text-sm">Siswa Teladan!</h5>
                                        <p class="text-xs text-gray-400 mt-1">Tidak ada catatan pelanggaran.</p>
                                    </div>
                                </template>

                                <div class="max-h-48 overflow-y-auto space-y-2" x-show="filteredViolations.length > 0">
                                    <template x-for="violation in filteredViolations" :key="violation.id">
                                        <div
                                            class="flex gap-3 p-3 rounded-lg border border-gray-100 hover:bg-gray-50 transition">
                                            <div class="flex-shrink-0 mt-0.5">
                                                <div class="w-2 h-2 rounded-full bg-red-400 mt-1.5"></div>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-800" x-text="violation.keterangan">
                                                </p>
                                                <p class="text-xs text-gray-400 mt-0.5"
                                                    x-text="new Date(violation.tanggal).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })">
                                                </p>
                                            </div>
                                            <div class="ml-auto text-right">
                                                <span class="text-xs font-bold text-red-500"
                                                    x-text="'-' + violation.jumlah"></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Footer Info -->
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 mt-6 md:grid md:grid-cols-2 gap-4">
                                <div class="mb-3 md:mb-0">
                                    <p class="text-[10px] uppercase font-bold text-gray-400 mb-1">Wali Kelas</p>
                                    <p class="text-sm font-semibold text-gray-700" x-text="selectedSiswa.wali_kelas || '-'">
                                    </p>
                                </div>
                                <div class="md:text-right">
                                    <p class="text-[10px] uppercase font-bold text-gray-400 mb-1">Kontak Ortu</p>
                                    <p class="text-sm font-semibold text-gray-700 font-mono"
                                        x-text="selectedSiswa.kontak_orang_tua || '-'"></p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
@endsection