<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poin;
use App\Models\TindakLanjut;

class TindakLanjutController extends Controller
{
    public function index()
    {
        // Get all violations that are waiting for follow-up (status 'diproses')
        $pelanggaran = Poin::where('jenis', 'pelanggaran')
            ->whereHas('tindakLanjut', function ($query) {
                $query->where('status', 'diproses');
            })
            ->with(['siswa', 'tindakLanjut'])
            ->latest()
            ->paginate(10);

        // Count pending
        $pendingCount = Poin::where('jenis', 'pelanggaran')
            ->whereHas('tindakLanjut', function ($query) {
                $query->where('status', 'diproses');
            })->count();

        return view('tindak_lanjut.index', compact('pelanggaran', 'pendingCount'));
    }

    public function create(Poin $poin)
    {
        return view('tindak_lanjut.create', compact('poin'));
    }

    public function store(Request $request, Poin $poin)
    {
        $request->validate([
            'tindakan' => 'required|string',
            'status' => 'required|in:diproses,selesai',
            'catatan' => 'nullable|string',
        ]);

        TindakLanjut::updateOrCreate(
            ['poin_id' => $poin->id],
            [
                'tindakan' => $request->tindakan,
                'status' => $request->status,
                'catatan' => $request->catatan,
            ]
        );

        return redirect()->back()->with('success', 'Tindak lanjut berhasil diperbarui.');
    }
}
