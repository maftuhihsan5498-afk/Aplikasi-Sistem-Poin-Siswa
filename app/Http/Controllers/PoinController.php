<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poin;
use App\Models\Siswa;

class PoinController extends Controller
{
    public function create()
    {
        $siswas = Siswa::orderBy('nama')->get();
        // Get unique, sorted classes for the dropdown
        $kelas_list = Siswa::select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');

        return view('poin.create', compact('siswas', 'kelas_list'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'jenis_poin' => 'required|in:Prestasi,Pelanggaran',
            'tanggal' => 'required|date',
            'lokasi_kejadian' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'status_tindak_lanjut' => 'nullable|in:Menunggu Tindak Lanjut,Selesai',
        ]);

        if ($request->jenis_poin == 'Pelanggaran') {
            $request->validate(['jenis_pelanggaran' => 'required|string']);
        } else {
            $request->validate(['jenis_prestasi' => 'required|string']);
        }

        // Violation Mapping
        $violationMap = [
            // Ringan
            'Terlambat masuk kelas' => ['points' => 5, 'category' => 'Ringan'],
            'Tidak mengerjakan PR' => ['points' => 5, 'category' => 'Ringan'],
            'Tidak membawa buku pelajaran' => ['points' => 3, 'category' => 'Ringan'],
            'Ribut di kelas' => ['points' => 5, 'category' => 'Ringan'],
            'Tidak memakai atribut lengkap' => ['points' => 5, 'category' => 'Ringan'],
            'Membuang sampah sembarangan' => ['points' => 5, 'category' => 'Ringan'],
            // Sedang
            'Tidak masuk tanpa keterangan' => ['points' => 15, 'category' => 'Sedang'],
            'Keluar kelas tanpa izin' => ['points' => 10, 'category' => 'Sedang'],
            'Menyontek saat ujian' => ['points' => 20, 'category' => 'Sedang'],
            'Berbohong kepada guru' => ['points' => 15, 'category' => 'Sedang'],
            'Merusak fasilitas sekolah' => ['points' => 20, 'category' => 'Sedang'],
            'Membawa HP tanpa izin' => ['points' => 15, 'category' => 'Sedang'],
            'Tidak mengikuti upacara' => ['points' => 10, 'category' => 'Sedang'],
            // Berat
            'Berkelahi dengan teman' => ['points' => 50, 'category' => 'Berat'],
            'Membully teman' => ['points' => 40, 'category' => 'Berat'],
            'Merokok di area sekolah' => ['points' => 50, 'category' => 'Berat'],
            'Membawa barang terlarang' => ['points' => 75, 'category' => 'Berat'],
            'Memalsukan tanda tangan' => ['points' => 30, 'category' => 'Berat'],
            'Mencuri' => ['points' => 75, 'category' => 'Berat'],
            'Melawan/Kasar kepada guru' => ['points' => 100, 'category' => 'Berat'],
        ];

        // Prestasi Mapping
        $prestasiMap = [
            // Akademik
            'Juara lomba akademik (Kab/Kota)' => ['points' => 30, 'category' => 'Akademik'],
            'Juara lomba akademik (Sekolah)' => ['points' => 20, 'category' => 'Akademik'],
            'Peringkat 1 kelas' => ['points' => 25, 'category' => 'Akademik'],
            'Peringkat 2–3 kelas' => ['points' => 15, 'category' => 'Akademik'],
            // Non-Akademik
            'Juara lomba olahraga/seni (Kab/Kota)' => ['points' => 25, 'category' => 'Non-Akademik'],
            'Juara lomba olahraga/seni (Sekolah)' => ['points' => 15, 'category' => 'Non-Akademik'],
            'Peserta lomba resmi' => ['points' => 10, 'category' => 'Non-Akademik'],
            // Organisasi
            'Ketua OSIS / Ketua Ekskul' => ['points' => 25, 'category' => 'Organisasi'],
            'Pengurus OSIS / Ekskul' => ['points' => 15, 'category' => 'Organisasi'],
            'Anggota aktif ekskul' => ['points' => 10, 'category' => 'Organisasi'],
            'Panitia kegiatan sekolah' => ['points' => 10, 'category' => 'Organisasi'],
            // Sikap
            'Disiplin & kehadiran penuh (1 semester)' => ['points' => 20, 'category' => 'Sikap'],
            'Membantu kegiatan sekolah' => ['points' => 10, 'category' => 'Sikap'],
            'Tidak melanggar tata tertib (1 semester)' => ['points' => 15, 'category' => 'Sikap'],
        ];

        $points = 0;
        $kategori = '';
        $jenis = strtolower($request->jenis_poin); // 'prestasi' or 'pelanggaran'
        $itemName = '';

        if ($request->jenis_poin == 'Pelanggaran') {
            $itemName = $request->jenis_pelanggaran;
            $data = $violationMap[$itemName] ?? ['points' => 0, 'category' => 'Unknown'];
        } else {
            $itemName = $request->jenis_prestasi;
            $data = $prestasiMap[$itemName] ?? ['points' => 0, 'category' => 'Unknown'];
        }

        $points = $data['points'];
        $kategori = $data['category'];

        // Build description
        $keterangan = $itemName . ". " . $request->deskripsi;
        if ($request->lokasi_kejadian && $request->jenis_poin == 'Pelanggaran') {
            $keterangan .= " [Lokasi: " . $request->lokasi_kejadian . "]";
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $points, $kategori, $keterangan, $jenis) {
            $poin = Poin::create([
                'siswa_id' => $request->siswa_id,
                'jenis' => $jenis,
                'kategori' => $kategori,
                'jumlah' => $points,
                'tanggal' => $request->tanggal,
                'keterangan' => $keterangan,
            ]);

            // Only create Tindak Lanjut for Violations
            if ($jenis == 'pelanggaran') {
                $status = $request->status_tindak_lanjut == 'Selesai' ? 'selesai' : 'diproses';
                \App\Models\TindakLanjut::create([
                    'poin_id' => $poin->id,
                    'tindakan' => 'Initial Report',
                    'status' => $status,
                    'catatan' => 'Auto-generated from Input Pelanggaran',
                ]);
            }
        });

        return redirect()->back()->with('success', 'Data Poin berhasil disimpan.');
    }
}
