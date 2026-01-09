<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = 'transaksi';
    protected $casts = [
        'tanggal' => 'date'
    ];

    protected $fillable = [
        'tanggal',
        'nominal',
        'deskripsi',
        'kategori_id',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
