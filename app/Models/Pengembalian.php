<?php

namespace App\Models;

use App\Models\Requestbarang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengembalian extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_requestbarang','qty','id_masterdinaspenerima','tanggal','keteranganbarang','buktikembali','status'
    ];

    public function masterdinaspenerima()
    {
        return $this->hasOne(Masterdinaspenerima::class, 'id', 'id_masterdinaspenerima');
    }
    public function masterrequest()
    {
        return $this->hasOne(Requestbarang::class, 'id', 'id_requestbarang');
    }
}
