<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Poin;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total Pelanggaran (Count of Poin records where jenis = pelanggaran)
        $totalPelanggaran = Poin::where('jenis', 'pelanggaran')->count();

        // 2. Siswa Terlibat (Count of distinct students who have violations)
        $siswaTerlibat = Poin::where('jenis', 'pelanggaran')->distinct('siswa_id')->count('siswa_id');

        // 3. Menunggu Tindak Lanjut (Count of TindakLanjut where status = diproses)
        // If we want to capture points that DON'T have a tindak lanjut yet, we might need a different query.
        // For now, let's assume we track created tindak lanjuts.
        $menungguTindakLanjut = \App\Models\TindakLanjut::where('status', 'diproses')->count();

        // 4. Selesai (Count of TindakLanjut where status = selesai)
        $selesai = \App\Models\TindakLanjut::where('status', 'selesai')->count();

        // 5. Recent Pelanggaran (Get latest 5 violations with siswa relation)
        $recentPelanggaran = Poin::with('siswa')
            ->where('jenis', 'pelanggaran')
            ->latest()
            ->take(5)
            ->get();

        // 6. Chart Data (Count by Category)
        // Assuming categories are 'Ringan', 'Sedang', 'Berat' stored in 'kategori' column.
        // We need to normalize case if needed, but assuming standard input.
        $statsKategori = Poin::where('jenis', 'pelanggaran')
            ->select('kategori', DB::raw('count(*) as total'))
            ->groupBy('kategori')
            ->pluck('total', 'kategori')
            ->toArray();

        // Ensure keys exist for chart
        $chartData = [
            'Ringan' => $statsKategori['Ringan'] ?? 0,
            'Sedang' => $statsKategori['Sedang'] ?? 0,
            'Berat' => $statsKategori['Berat'] ?? 0,
        ];

        return view('admin.dashboard', compact(
            'totalPelanggaran',
            'siswaTerlibat',
            'menungguTindakLanjut',
            'selesai',
            'recentPelanggaran',
            'chartData'
        ));
    }
}
