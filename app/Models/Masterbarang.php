<?php

namespace App\Models;

use App\Models\Pengiriman;
use App\Models\Requestbarang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Masterbarang extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama','qty','kategori','kodebarang'
    ];

    public function pengiriman()
{
    return $this->hasMany(Pengiriman::class);
}

    public function barangmasuk()
    {
        return $this->hasMany(Requestbarang::class);
    }

}
