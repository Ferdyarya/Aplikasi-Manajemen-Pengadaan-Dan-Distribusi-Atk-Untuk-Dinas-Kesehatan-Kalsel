<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_masterbarang','qty','id_masterdinaspenerima','tanggal','keteranganbarang','bukti','status'
    ];

    public function masterdinaspenerima()
    {
        return $this->hasOne(Masterdinaspenerima::class, 'id', 'id_masterdinaspenerima');
    }
    public function masterbarang()
    {
        return $this->hasOne(Masterbarang::class, 'id', 'id_masterbarang');
    }
}
