<?php

namespace App\Models;

use App\Models\Distribarang;
use App\Models\Masterbarang;
use App\Models\Masterdinaspenerima;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengiriman extends Model
{
    use HasFactory;
    protected $fillable = ['nokirim', 'id_masterdinaspenerima', 'tanggal', 'status', 'id_distribarang','id_requestbarang'];

    public function masterdinaspenerima()
    {
        return $this->hasOne(Masterdinaspenerima::class, 'id', 'id_masterdinaspenerima');
    }

    public function requestbarang()
    {
        return $this->hasOne(Requestbarang::class, 'id', 'id_requestbarang');
    }

    public function distribarang()
    {
        return $this->hasMany(Distribarang::class, 'id_pengiriman', 'id');
    }
}
