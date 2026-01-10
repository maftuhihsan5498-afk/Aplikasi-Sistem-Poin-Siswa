<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Poin;
use App\Models\Siswa;

class DashboardController extends Controller
{
    public function index()
    {
        $riwayatInput = Poin::with('siswa')->latest()->take(5)->get();

        return view('guru.dashboard', compact('riwayatInput'));
    }
}
