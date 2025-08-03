<?php

namespace App\Models;

use App\Models\Mastersupplyment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Requestbarang extends Model
{
    use HasFactory;
    protected $fillable = ['id_masterbarang', 'qty', 'id_supplyment', 'tanggal', 'status', 'kebutuhan'];

    public function mastersupplyment()
    {
        return $this->hasOne(Mastersupplyment::class, 'id', 'id_supplyment');
    }
    public function masterbarang()
    {
        return $this->hasOne(Masterbarang::class, 'id', 'id_masterbarang');
    }

    public function pengiriman()
    {
        return $this->hasMany(Pengiriman::class);
    }
}
