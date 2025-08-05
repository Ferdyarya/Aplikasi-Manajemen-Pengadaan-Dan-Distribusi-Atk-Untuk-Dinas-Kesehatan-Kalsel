<?php

namespace App\Models;

use App\Models\Pengiriman;
use App\Models\Requestbarang;
use App\Models\Masterbarang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Distribarang extends Model
{
    use HasFactory;
    protected $fillable = ['id_requestbarang', 'qty', 'id_pengiriman', 'id_masterbarang'];

    public function requestbarang()
    {
        return $this->belongsTo(Requestbarang::class, 'id_requestbarang', 'id');
    }

    public function pengiriman()
    {
        return $this->belongsTo(Pengiriman::class, 'id_pengiriman', 'id');
    }

    public function masterbarang()
{
    return $this->belongsTo(Masterbarang::class, 'id_masterbarang');
}

}
