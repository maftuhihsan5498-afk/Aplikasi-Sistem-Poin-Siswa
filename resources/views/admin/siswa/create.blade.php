@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.siswa.index') }}" class="text-indigo-600 hover:text-indigo-800">&larr; Kembali</a>
    </div>

    <div class="w-full max-w-lg mx-auto bg-white p-6 rounded-md shadow-md">
        <h3 class="text-2xl font-bold text-gray-700 mb-6">Tambah Siswa Baru</h3>

        <form action="{{ route('admin.siswa.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">NIS</label>
                <input type="text" name="nis"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                <input type="text" name="nama"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Kelas</label>
                <input type="text" name="kelas"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="Contoh: VII-A" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Wali Kelas</label>
                <input type="text" name="wali_kelas"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="Nama Wali Kelas">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Kontak Orang Tua</label>
                <input type="text" name="kontak_orang_tua"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="08xxxxxxxxxx">
            </div>

            <div class="mb-4 bg-yellow-50 p-3 rounded text-sm text-yellow-800 border border-yellow-200">
                <span class="font-bold">Info:</span> Akun login akan dibuat otomatis. Username/Password default adalah NIS.
            </div>

            <div class="mt-6">
                <button type="submit"
                    class="w-full bg-indigo-600 text-white font-bold py-2 px-4 rounded hover:bg-indigo-700 focus:outline-none focus:shadow-outline">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
@endsection