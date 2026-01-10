@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto bg-white p-6 rounded-md shadow-md">
        <h3 class="text-2xl font-bold text-gray-700 mb-6">Proses Tindak Lanjut</h3>

        <div class="mb-6 p-4 bg-gray-100 rounded">
            <p><strong>Siswa:</strong> {{ $poin->siswa->nama }} ({{ $poin->siswa->kelas }})</p>
            <p><strong>Pelanggaran:</strong> {{ $poin->kategori }}</p>
            <p><strong>Tanggal:</strong> {{ $poin->tanggal->format('d F Y') }}</p>
        </div>

        <form action="{{ route('tindak_lanjut.store', $poin->id) }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Tindakan yang Diambil</label>
                <input type="text" name="tindakan"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="Contoh: Pemanggilan Orang Tua, Skorsing, dll."
                    value="{{ $poin->tindakLanjut->tindakan ?? '' }}" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                <select name="status"
                    class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                    <option value="diproses" {{ ($poin->tindakLanjut->status ?? '') == 'diproses' ? 'selected' : '' }}>
                        Diproses</option>
                    <option value="selesai" {{ ($poin->tindakLanjut->status ?? '') == 'selesai' ? 'selected' : '' }}>Selesai
                    </option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Catatan Tambahan</label>
                <textarea name="catatan"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    rows="3">{{ $poin->tindakLanjut->catatan ?? '' }}</textarea>
            </div>

            <div class="mt-6">
                <button type="submit"
                    class="w-full bg-indigo-600 text-white font-bold py-2 px-4 rounded hover:bg-indigo-700 focus:outline-none focus:shadow-outline">
                    Simpan Tindak Lanjut
                </button>
            </div>
        </form>
    </div>
@endsection