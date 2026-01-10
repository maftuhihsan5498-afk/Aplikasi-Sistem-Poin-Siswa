<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nis',
        'nama',
        'kelas',
        'jenis_kelamin',
        'wali_kelas',
        'kontak_orang_tua',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function poins()
    {
        return $this->hasMany(Poin::class);
    }

    public function totalPoinPrestasi()
    {
        return $this->poins()->where('jenis', 'prestasi')->sum('jumlah');
    }

    public function totalPoinPelanggaran()
    {
        return $this->poins()->where('jenis', 'pelanggaran')->sum('jumlah');
    }

    public function totalPoinAkhir()
    {
        return $this->totalPoinPrestasi() - $this->totalPoinPelanggaran();
    }
}
