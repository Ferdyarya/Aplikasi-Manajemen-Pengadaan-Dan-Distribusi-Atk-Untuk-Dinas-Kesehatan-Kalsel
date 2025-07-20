<?php

namespace App\Models;

use App\Models\Pengembalian;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rusak extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_masterpengembalian','bukti','tanggal'
    ];

    public function masterpengembalian()
{
    return $this->belongsTo(Pengembalian::class, 'id_masterpengembalian', 'id');
}

}
