<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TindakLanjut extends Model
{
    use HasFactory;

    protected $fillable = [
        'poin_id',
        'tindakan',
        'status',
        'catatan',
    ];

    public function poin()
    {
        return $this->belongsTo(Poin::class);
    }
}
