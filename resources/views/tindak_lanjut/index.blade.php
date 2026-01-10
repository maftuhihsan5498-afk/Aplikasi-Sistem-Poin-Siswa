@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto" x-data="{ 
            showModal: false, 
            selectedPoin: null, 
            selectedSiswa: '', 
            selectedViolation: '',
            violationId: '',
            tindakan: '',

            openModal(poin) {
                this.selectedPoin = poin;
                this.violationId = poin.id;
                this.selectedSiswa = poin.siswa.nama;
                this.selectedViolation = poin.keterangan;
                this.tindakan = '';
                this.showModal = true;
            },
            closeModal() {
                this.showModal = false;
            }
        }">

        <!-- Header Statistic -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-6 h-6 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    Antrian Tindak Lanjut
                </h2>
                <p class="text-gray-500 mt-1">Daftar siswa yang menunggu proses bimbingan/konseling.</p>
            </div>
            <div class="flex flex-col items-center bg-orange-50 px-6 py-3 rounded-lg border border-orange-100">
                <span class="text-2xl font-bold text-orange-600">{{ $pendingCount }}</span>
                <span class="text-xs font-bold text-orange-400 uppercase tracking-wider">Menunggu</span>
            </div>
        </div>

        <!-- List of Cards -->
        <div class="space-y-4">
            @forelse($pelanggaran as $item)
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition duration-200">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Left Border Indicator -->
                        <div class="hidden md:block w-1 bg-orange-400 rounded-full self-stretch"></div>

                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="bg-gray-100 text-gray-600 text-xs font-mono px-2 py-1 rounded">
                                    CPS-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}
                                </span>
                                <span
                                    class="bg-orange-100 text-orange-600 text-xs font-bold px-2 py-1 rounded-full flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Menunggu Penanganan
                                </span>
                                <span class="text-sm text-gray-400">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                                </span>
                            </div>

                            <div class="flex items-start gap-4 mb-4">
                                <div
                                    class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-lg">
                                    {{ substr($item->siswa->nama, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800">{{ $item->siswa->nama }}</h3>
                                    <p class="text-gray-500 text-sm">{{ $item->siswa->kelas }} • {{ $item->siswa->nis }}</p>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Jenis
                                            Pelanggaran</p>
                                        <p class="text-gray-700 font-medium">{{ $item->keterangan }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Lokasi</p>
                                        <p class="text-gray-700 font-medium font-mono text-sm">
                                            @php
                                                // Extract location from bracket if exists, otherwise placeholder
                                                if (preg_match('/\[Lokasi: (.*?)\]/', $item->keterangan, $matches)) {
                                                    echo $matches[1];
                                                } else {
                                                    echo '-';
                                                }
                                            @endphp
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Actions -->
                        <div class="flex flex-col items-end justify-between gap-4 border-l border-gray-50 pl-6 min-w-[200px]">
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 rounded-full text-xs font-bold
                                            @if($item->kategori == 'Berat') bg-red-100 text-red-600
                                            @elseif($item->kategori == 'Sedang') bg-yellow-100 text-yellow-600
                                            @else bg-green-100 text-green-600 @endif">
                                    {{ $item->kategori }}
                                </span>
                                <span class="text-gray-400 font-medium text-sm">{{ $item->jumlah }} Poin</span>
                            </div>

                            <button @click="openModal({{ $item }})"
                                class="w-full bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2.5 rounded-lg shadow-lg shadow-cyan-500/20 font-medium text-sm flex items-center justify-center gap-2 transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Proses Tindak Lanjut
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="bg-green-50 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Tidak ada antrian</h3>
                    <p class="text-gray-500 mt-1">Semua pelanggaran telah diproses.</p>
                </div>
            @endforelse

            <div class="mt-6">
                {{ $pelanggaran->links() }}
            </div>
        </div>

        <!-- Modal -->
        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                    @click="closeModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">

                    <form :action="'{{ url(request()->route()->getPrefix() . '/tindak-lanjut') }}/' + violationId" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg leading-6 font-bold text-gray-900 flex items-center gap-2">
                                    <span class="text-2xl">📝</span> Form Tindak Lanjut
                                </h3>
                                <button type="button" @click="closeModal" class="text-gray-400 hover:text-gray-500">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <div class="bg-indigo-50 rounded-lg p-4 mb-6 border border-indigo-100 flex items-start gap-4">
                                <div class="bg-white p-2 rounded-lg shadow-sm">
                                    <svg class="w-6 h-6 text-indigo-500" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900" x-text="selectedSiswa"></h4>
                                    <p class="text-sm text-indigo-600 mt-1" x-text="selectedViolation"></p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Hasil & Dokumentasi Tindak
                                    Lanjut</label>
                                <textarea name="tindakan" rows="4" x-model="tindakan"
                                    class="shadow-sm focus:ring-cyan-500 focus:border-cyan-500 block w-full sm:text-sm border-2 border-cyan-100 rounded-lg p-3"
                                    placeholder="Contoh: Siswa telah diberikan teguran lisan dan berjanji tidak mengulangi. Orang tua sudah dihubungi."></textarea>
                                <p class="mt-2 text-xs text-gray-500 text-right">Minimal 5 karakter</p>
                            </div>

                            <!-- Hidden Fields -->
                            <input type="hidden" name="status" value="selesai">
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-cyan-600 text-base font-medium text-white hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 sm:ml-3 sm:w-auto sm:text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                Kirim Data
                            </button>
                            <button type="button" @click="closeModal"
                                class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection