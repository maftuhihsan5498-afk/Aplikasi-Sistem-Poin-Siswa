@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto" x-data="studentForm()">

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show"
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="show = false">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </span>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="px-8 py-6 border-b border-gray-100 flex items-center gap-4">
                <div class="p-3 bg-cyan-50 rounded-lg">
                    <svg class="w-6 h-6 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Input Data Poin</h2>
            </div>

            <form action="{{ route('poin.store') }}" method="POST" class="p-8 space-y-8">
                @csrf

                <!-- 1. Data Siswa -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">1. Data Siswa</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Kelas</label>
                            <div class="relative">
                                <select x-model="selectedClass" @change="filterStudents"
                                    class="block w-full rounded-lg border-gray-300 bg-gray-50 focus:border-cyan-500 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 py-2.5 px-4 appearance-none">
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($kelas_list as $kelas)
                                        <option value="{{ $kelas }}">{{ $kelas }}</option>
                                    @endforeach
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Siswa</label>
                            <div class="relative">
                                <select name="siswa_id" x-model="studentId" @change="updateStudentDetails"
                                    :disabled="!selectedClass"
                                    class="block w-full rounded-lg border-gray-300 bg-gray-50 focus:border-cyan-500 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 py-2.5 px-4 appearance-none disabled:opacity-50 disabled:cursor-not-allowed">
                                    <option value="">-- Pilih Siswa --</option>
                                    <template x-for="student in filteredStudents" :key="student.id">
                                        <option :value="student.id" x-text="student.nama"></option>
                                    </template>
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student Detail Card -->
                <div class="bg-indigo-50 rounded-xl p-6 border border-indigo-100 flex items-start gap-6"
                    x-show="selectedStudent" x-transition>
                    <div
                        class="w-16 h-16 rounded-full bg-white flex items-center justify-center border-2 border-indigo-200 flex-shrink-0">
                        <svg class="w-8 h-8 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-xs text-indigo-400 font-medium uppercase tracking-wider mb-1">Nama Lengkap</p>
                            <p class="text-lg font-bold text-gray-800" x-text="selectedStudent?.nama"></p>
                        </div>
                        <div>
                            <p class="text-xs text-indigo-400 font-medium uppercase tracking-wider mb-1">NIS</p>
                            <p class="text-lg font-bold text-gray-800" x-text="selectedStudent?.nis"></p>
                        </div>
                        <div>
                            <p class="text-xs text-indigo-400 font-medium uppercase tracking-wider mb-1">Kelas</p>
                            <p class="text-lg font-bold text-gray-800" x-text="selectedStudent?.kelas"></p>
                        </div>
                        <div>
                            <p class="text-xs text-indigo-400 font-medium uppercase tracking-wider mb-1">Wali Kelas</p>
                            <p class="text-lg font-bold text-gray-800" x-text="selectedStudent?.wali_kelas || '-'"></p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-xs text-indigo-400 font-medium uppercase tracking-wider mb-1">Kontak Ortu</p>
                            <p class="text-lg font-bold text-gray-800" x-text="selectedStudent?.kontak_orang_tua || '-'">
                            </p>
                        </div>
                    </div>
                </div>

        </div>

        <!-- 2. Detail Poin -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">2. Detail Poin</h3>

            <!-- Code Box -->
            <div class="bg-gray-50 rounded-lg p-4 flex justify-between items-center border border-gray-200 mb-6">
                <div class="flex items-center gap-3">
                    <div class="bg-white px-3 py-1 rounded border border-gray-200 text-cyan-600 font-bold text-sm">
                        CP-{{ rand(1000, 9999) }}
                    </div>
                    <span class="text-sm text-gray-500 font-medium">Kode Laporan</span>
                </div>
                <span class="text-sm text-gray-400">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Laporan</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}"
                        class="block w-full rounded-lg border-gray-300 bg-gray-50 focus:border-cyan-500 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 py-2.5 px-4">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Poin</label>
                    <div class="relative">
                        <select name="jenis_poin" x-model="pointType" @change="resetSelection"
                            class="block w-full rounded-lg border-gray-300 bg-gray-50 focus:border-cyan-500 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 py-2.5 px-4 appearance-none">
                            <option value="">-- Pilih Jenis Poin --</option>
                            <option value="Prestasi">Prestasi</option>
                            <option value="Pelanggaran">Pelanggaran</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-6" x-show="pointType" x-transition>
                <label class="block text-sm font-medium text-gray-700 mb-2"
                    x-text="pointType === 'Prestasi' ? 'Jenis Prestasi' : 'Jenis Pelanggaran'"></label>
                <div class="relative">
                    <!-- Select for Prestasi -->
                    <select name="jenis_prestasi" x-show="pointType === 'Prestasi'" @change="updatePointDetails"
                        class="block w-full rounded-lg border-gray-300 bg-gray-50 focus:border-cyan-500 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 py-2.5 px-4 appearance-none">
                        <option value="">-- Pilih Jenis Prestasi --</option>
                        <optgroup label="1. Prestasi Akademik">
                            <option value="Juara lomba akademik (Kab/Kota)" data-points="30" data-category="Akademik">Juara
                                lomba akademik (Kab/Kota) (30 poin)</option>
                            <option value="Juara lomba akademik (Sekolah)" data-points="20" data-category="Akademik">Juara
                                lomba akademik (Sekolah) (20 poin)</option>
                            <option value="Peringkat 1 kelas" data-points="25" data-category="Akademik">Peringkat 1 kelas
                                (25 poin)</option>
                            <option value="Peringkat 2–3 kelas" data-points="15" data-category="Akademik">Peringkat 2–3
                                kelas (15 poin)</option>
                        </optgroup>
                        <optgroup label="2. Prestasi Non-Akademik">
                            <option value="Juara lomba olahraga/seni (Kab/Kota)" data-points="25"
                                data-category="Non-Akademik">Juara lomba olahraga/seni (Kab/Kota) (25 poin)</option>
                            <option value="Juara lomba olahraga/seni (Sekolah)" data-points="15"
                                data-category="Non-Akademik">Juara lomba olahraga/seni (Sekolah) (15 poin)</option>
                            <option value="Peserta lomba resmi" data-points="10" data-category="Non-Akademik">Peserta lomba
                                resmi (10 poin)</option>
                        </optgroup>
                        <optgroup label="3. Organisasi & Keaktifan">
                            <option value="Ketua OSIS / Ketua Ekskul" data-points="25" data-category="Organisasi">Ketua OSIS
                                / Ketua Ekskul (25 poin)</option>
                            <option value="Pengurus OSIS / Ekskul" data-points="15" data-category="Organisasi">Pengurus OSIS
                                / Ekskul (15 poin)</option>
                            <option value="Anggota aktif ekskul" data-points="10" data-category="Organisasi">Anggota aktif
                                ekskul (10 poin)</option>
                            <option value="Panitia kegiatan sekolah" data-points="10" data-category="Organisasi">Panitia
                                kegiatan sekolah (10 poin)</option>
                        </optgroup>
                        <optgroup label="4. Sikap & Perilaku Positif">
                            <option value="Disiplin & kehadiran penuh (1 semester)" data-points="20" data-category="Sikap">
                                Disiplin & kehadiran penuh (1 semester) (20 poin)</option>
                            <option value="Membantu kegiatan sekolah" data-points="10" data-category="Sikap">Membantu
                                kegiatan sekolah (10 poin)</option>
                            <option value="Tidak melanggar tata tertib (1 semester)" data-points="15" data-category="Sikap">
                                Tidak melanggar tata tertib (1 semester) (15 poin)</option>
                        </optgroup>
                    </select>

                    <!-- Select for Pelanggaran -->
                    <select name="jenis_pelanggaran" x-show="pointType === 'Pelanggaran'" @change="updatePointDetails"
                        class="block w-full rounded-lg border-gray-300 bg-gray-50 focus:border-cyan-500 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 py-2.5 px-4 appearance-none">
                        <option value="">-- Pilih Jenis Pelanggaran --</option>
                        <optgroup label="Kategori Ringan">
                            <option value="Terlambat masuk kelas" data-points="5" data-category="Ringan">Terlambat masuk
                                kelas (5 poin)</option>
                            <option value="Tidak mengerjakan PR" data-points="5" data-category="Ringan">Tidak mengerjakan PR
                                (5 poin)</option>
                            <option value="Tidak membawa buku pelajaran" data-points="3" data-category="Ringan">Tidak
                                membawa buku pelajaran (3 poin)</option>
                            <option value="Ribut di kelas" data-points="5" data-category="Ringan">Ribut di kelas (5 poin)
                            </option>
                            <option value="Tidak memakai atribut lengkap" data-points="5" data-category="Ringan">Tidak
                                memakai atribut lengkap (5 poin)</option>
                            <option value="Membuang sampah sembarangan" data-points="5" data-category="Ringan">Membuang
                                sampah sembarangan (5 poin)</option>
                        </optgroup>
                        <optgroup label="Kategori Sedang">
                            <option value="Tidak masuk tanpa keterangan" data-points="15" data-category="Sedang">Tidak masuk
                                tanpa keterangan (15 poin)</option>
                            <option value="Keluar kelas tanpa izin" data-points="10" data-category="Sedang">Keluar kelas
                                tanpa izin (10 poin)</option>
                            <option value="Menyontek saat ujian" data-points="20" data-category="Sedang">Menyontek saat
                                ujian (20 poin)</option>
                            <option value="Berbohong kepada guru" data-points="15" data-category="Sedang">Berbohong kepada
                                guru (15 poin)</option>
                            <option value="Merusak fasilitas sekolah" data-points="20" data-category="Sedang">Merusak
                                fasilitas sekolah (20 poin)</option>
                            <option value="Membawa HP tanpa izin" data-points="15" data-category="Sedang">Membawa HP tanpa
                                izin (15 poin)</option>
                            <option value="Tidak mengikuti upacara" data-points="10" data-category="Sedang">Tidak mengikuti
                                upacara (10 poin)</option>
                        </optgroup>
                        <optgroup label="Kategori Berat">
                            <option value="Berkelahi dengan teman" data-points="50" data-category="Berat">Berkelahi dengan
                                teman (50 poin)</option>
                            <option value="Membully teman" data-points="40" data-category="Berat">Membully teman (40 poin)
                            </option>
                            <option value="Merokok di area sekolah" data-points="50" data-category="Berat">Merokok di area
                                sekolah (50 poin)</option>
                            <option value="Membawa barang terlarang" data-points="75" data-category="Berat">Membawa barang
                                terlarang (75 poin)</option>
                            <option value="Memalsukan tanda tangan" data-points="30" data-category="Berat">Memalsukan tanda
                                tangan (30 poin)</option>
                            <option value="Mencuri" data-points="75" data-category="Berat">Mencuri (75 poin)</option>
                            <option value="Melawan/Kasar kepada guru" data-points="100" data-category="Berat">Melawan/Kasar
                                kepada guru (100 poin)</option>
                        </optgroup>
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Dynamic Display Box -->
            <div class="grid grid-cols-2 gap-6 mb-6" x-show="currentPoints > 0" x-transition>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <div class="w-full rounded-lg py-2.5 px-4 font-bold text-center border" :class="{
                                        'bg-green-50 text-green-700 border-green-200': currentCategory === 'Ringan' || ['Akademik', 'Non-Akademik', 'Organisasi', 'Sikap'].includes(currentCategory),
                                        'bg-yellow-50 text-yellow-700 border-yellow-200': currentCategory === 'Sedang',
                                        'bg-red-50 text-red-700 border-red-200': currentCategory === 'Berat'
                                    }" x-text="currentCategory">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Poin</label>
                    <div class="w-full rounded-lg py-2.5 px-4 font-bold text-center border bg-gray-50 text-gray-800 border-gray-200"
                        x-text="currentPoints + ' Poin'">
                    </div>
                </div>
            </div>

            <div x-show="pointType === 'Pelanggaran'">
                <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi Kejadian</label>
                <input type="text" name="lokasi_kejadian" placeholder="Contoh: Kantin, Kelas 9A"
                    class="block w-full rounded-lg border-gray-300 bg-gray-50 focus:border-cyan-500 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 py-2.5 px-4 mb-6">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi & Catatan</label>
                <textarea name="deskripsi" rows="3" placeholder="Ceritakan detail..."
                    class="block w-full rounded-lg border-gray-300 bg-gray-50 focus:border-cyan-500 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 py-2.5 px-4"></textarea>
            </div>
        </div>

        <!-- 3. Status Tindak Lanjut (Only for Pelanggaran) -->
        <div x-show="pointType === 'Pelanggaran'">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">3. Status Tindak Lanjut</h3>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <div class="relative">
                    <select name="status_tindak_lanjut"
                        class="block w-full rounded-lg border-gray-300 bg-gray-50 focus:border-cyan-500 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 py-2.5 px-4 appearance-none">
                        <option value="Menunggu Tindak Lanjut">Menunggu Tindak Lanjut</option>
                        <option value="Selesai">Selesai (Langsung Ditangani)</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="pt-6">
        <button type="submit"
            class="w-full bg-[#0ea5e9] hover:bg-cyan-600 text-white font-bold py-3 rounded-lg shadow-lg shadow-cyan-500/30 transition duration-300 flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
            </svg>
            Simpan Data
        </button>
    </div>
    </form>
    </div>
    </div>

    <script>
        function studentForm() {
            return {
                selectedClass: '',
                studentId: '',
                allStudents: @json($siswas),
                filteredStudents: [],
                selectedStudent: null,
                pointType: '',
                currentPoints: 0,
                currentCategory: '',

                filterStudents() {
                    this.selectedStudent = null;
                    this.studentId = '';
                    if (this.selectedClass === '') {
                        this.filteredStudents = [];
                    } else {
                        this.filteredStudents = this.allStudents.filter(student => student.kelas == this.selectedClass);
                    }
                },

                updateStudentDetails() {
                    if (this.studentId) {
                        this.selectedStudent = this.allStudents.find(s => s.id == this.studentId);
                    } else {
                        this.selectedStudent = null;
                    }
                },

                resetSelection() {
                    this.currentPoints = 0;
                    this.currentCategory = '';
                },

                updatePointDetails(e) {
                    const selectedOption = e.target.options[e.target.selectedIndex];
                    this.currentPoints = selectedOption.getAttribute('data-points') || 0;
                    this.currentCategory = selectedOption.getAttribute('data-category') || '';
                }
            }
        }
    </script>
@endsection