<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with([
            'user',
            'poins' => function ($query) {
                $query->latest();
            }
        ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%")
                    ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        if ($request->has('kelas') && $request->kelas != '') {
            $query->where('kelas', $request->kelas);
        }

        $siswas = $query->paginate(10);

        // Get unique kelas for filter
        $kelas_list = Siswa::select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');

        return view('admin.siswa.index', compact('siswas', 'kelas_list'));
    }

    public function create()
    {
        return view('admin.siswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nis' => 'required|unique:siswas,nis',
            'kelas' => 'required',
            'wali_kelas' => 'nullable|string',
            'kontak_orang_tua' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            // Auto-generate email from NIS
            $email = $request->nis . '@yamis.com';

            $user = User::create([
                'name' => $request->nama,
                'email' => $email,
                'password' => Hash::make($request->nis), // Default password is NIS
                'role' => 'siswa',
            ]);

            Siswa::create([
                'user_id' => $user->id,
                'nis' => $request->nis,
                'nama' => $request->nama,
                'kelas' => $request->kelas,
                'wali_kelas' => $request->wali_kelas,
                'kontak_orang_tua' => $request->kontak_orang_tua,
            ]);
        });

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil ditambahkan');
    }

    public function show(Siswa $siswa)
    {
        $riwayat = $siswa->poins()->latest()->get();
        return view('admin.siswa.show', compact('siswa', 'riwayat'));
    }

    public function destroy(Siswa $siswa)
    {
        DB::transaction(function () use ($siswa) {
            $user = $siswa->user;
            $siswa->delete();
            if ($user) {
                $user->delete();
            }
        });

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil dihapus');
    }
}
