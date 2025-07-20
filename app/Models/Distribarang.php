<?php

namespace App\Models;

use App\Models\Pengiriman;
use App\Models\Masterbarang;
use App\Models\Masterpegawai;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Distribarang extends Model
{
    use HasFactory;
    protected $fillable = ['id_masterbarang', 'qty', 'id_pengiriman'];

    public function masterbarang()
    {
        return $this->belongsTo(Masterbarang::class, 'id_masterbarang', 'id');
    }

    public function pengiriman()
    {
        return $this->belongsTo(Pengiriman::class, 'id_pengiriman', 'id');
    }
}
