<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $siswa = $user->siswa; // Assuming relation exists

        if (!$siswa) {
            // Handle case where siswa data is missing for user
            return view('siswa.dashboard', [
                'siswa' => null,
                'totalPrestasi' => 0,
                'totalPelanggaran' => 0,
                'totalAkhir' => 0,
                'riwayat' => []
            ]);
        }

        $totalPrestasi = $siswa->totalPoinPrestasi();
        $totalPelanggaran = $siswa->totalPoinPelanggaran();
        $totalAkhir = $siswa->totalPoinAkhir();
        $riwayat = $siswa->poins()->latest()->get();

        return view('siswa.dashboard', compact('siswa', 'totalPrestasi', 'totalPelanggaran', 'totalAkhir', 'riwayat'));
    }
}
