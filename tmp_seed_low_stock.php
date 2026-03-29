<?php

use App\Models\Requestbarang;
use App\Models\Masterbarang;
use App\Models\Mastersupplyment;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$mb = Masterbarang::first();
$ms = Mastersupplyment::first();

if ($mb && $ms) {
    Requestbarang::updateOrCreate(
        ['id_masterbarang' => $mb->id, 'id_supplyment' => $ms->id],
        ['qty' => 3, 'tanggal' => now(), 'status' => 'Terverifikasi']
    );
    echo "Seed low stock success\n";
} else {
    echo "Master data missing\n";
}
