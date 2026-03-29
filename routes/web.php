<?php

use App\Models\Pengiriman;
use App\Models\Masterbarang;
use App\Models\Pengembalian;
use App\Models\Requestbarang;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RusakController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KekuranganController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\BarangjarangController;
use App\Http\Controllers\MasterbarangController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\MasterpegawaiController;
use App\Http\Controllers\RequestbarangController;
use App\Http\Controllers\AnalisisbarangController;
use App\Http\Controllers\MastersupplymentController;
use App\Http\Controllers\MasterdinaspenerimaController;
use App\Models\Barangjarang;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $pengirimanCount = Pengiriman::count();
    $pengembalianCount = Pengembalian::count();
    $requestbarangCount = Requestbarang::count();
    $masterbarangCount = Masterbarang::count();

    $stokData = \Illuminate\Support\Facades\DB::table('requestbarangs')
        ->join('masterbarangs', 'requestbarangs.id_masterbarang', '=', 'masterbarangs.id')
        ->select('masterbarangs.nama', \Illuminate\Support\Facades\DB::raw('SUM(requestbarangs.qty) as total'))
        ->groupBy('masterbarangs.id', 'masterbarangs.nama')
        ->get();

    $pengirimanData = \Illuminate\Support\Facades\DB::table('distribarangs')
        ->join('masterbarangs', 'distribarangs.id_masterbarang', '=', 'masterbarangs.id')
        ->select('masterbarangs.nama', \Illuminate\Support\Facades\DB::raw('SUM(distribarangs.qty) as total'))
        ->groupBy('masterbarangs.id', 'masterbarangs.nama')
        ->get();

    $pendingPengiriman = \App\Models\Pengiriman::with(['masterdinaspenerima'])
        ->where(function($query) {
            $query->where('status', '!=', 'Terkirim')
                  ->orWhereNull('status');
        })->get();

    $lowStockList = \App\Models\Requestbarang::with('masterbarang')
        ->where('qty', '<', 6)
        ->get();

    return view('dashboard',compact('pengirimanCount','pengembalianCount','requestbarangCount','masterbarangCount','stokData','pengirimanData', 'pendingPengiriman', 'lowStockList'));
})->middleware('auth');


Route::prefix('dashboard')->middleware(['auth:sanctum'])->group(function() {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Master Data
    // Route::resource('mastersupplyment', MasterpegawaiController::class);
    Route::resource('masterbarang', MasterbarangController::class);
    Route::resource('masterdinaspenerima', MasterdinaspenerimaController::class);
    Route::resource('mastersupplyment', MastersupplymentController::class);

    // Monitoring Barang
    Route::get('/monitoringbarang', [RequestbarangController::class, 'monitoringbarang'])->name('monitoringbarang');

    // Data Tables Surat
    Route::resource('pengiriman', PengirimanController::class);
    Route::resource('pengembalian', PengembalianController::class);
    Route::resource('requestbarang', RequestbarangController::class);
    Route::resource('rusak', RusakController::class);
    Route::resource('kekurangan', KekuranganController::class);
    Route::resource('analisisbarang', AnalisisbarangController::class);
    Route::resource('barangjarang', BarangjarangController::class);

     // Data Tables Detail
        Route::get('/pengiriman/{id}/detail', [PengirimanController::class, 'detail'])->name('pengiriman.detail');


    // Report
    // Pengiriman
    Route::get('laporannya/laporanpengiriman', [PengirimanController::class, 'cetakpengirimanpertanggal'])->name('laporanpengiriman');
    Route::get('laporanpengiriman', [PengirimanController::class, 'filterdatepengiriman'])->name('laporanpengiriman');
    Route::get('laporanpengirimanpdf/filter={filter}', [PengirimanController::class, 'laporanpengirimanpdf'])->name('laporanpengirimanpdf');

    // Pengembalian
    Route::get('laporannya/laporanpengembalian', [PengembalianController::class, 'cetakpengembalianpertanggal'])->name('laporanpengembalian');
    Route::get('laporanpengembalian', [PengembalianController::class, 'filterdatepengembalian'])->name('laporanpengembalian');
    Route::get('laporanpengembalianpdf/filter={filter}', [PengembalianController::class, 'laporanpengembalianpdf'])->name('laporanpengembalianpdf');

    // Request Barang
    Route::get('laporannya/laporanrequestbarang', [RequestbarangController::class, 'cetakrequestbarangpertanggal'])->name('laporanrequestbarang');
    Route::get('laporanrequestbarang', [RequestbarangController::class, 'filterdaterequestbarang'])->name('laporanrequestbarang');
    Route::get('laporanrequestbarangpdf/filter={filter}', [RequestbarangController::class, 'laporanrequestbarangpdf'])->name('laporanrequestbarangpdf');

    // Rusak
    Route::get('laporannya/laporanrusak', [RusakController::class, 'cetakrusakpertanggal'])->name('laporanrusak');
    Route::get('laporanrusak', [RusakController::class, 'filterdaterusak'])->name('laporanrusak');
    Route::get('laporanrusakpdf/filter={filter}', [RusakController::class, 'laporanrusakpdf'])->name('laporanrusakpdf');

    // Kekurangan
    Route::get('laporannya/laporankekurangan', [KekuranganController::class, 'cetakkekuranganpertanggal'])->name('laporankekurangan');
    Route::get('laporankekurangan', [KekuranganController::class, 'filterdatekekurangan'])->name('laporankekurangan');
    Route::get('laporankekuranganpdf/filter={filter}', [KekuranganController::class, 'laporankekuranganpdf'])->name('laporankekuranganpdf');

    // kebutuhan Barang
    Route::get('laporannya/laporananalisisbarang', [AnalisisbarangController::class, 'cetakanalisisbarangpertanggal'])->name('laporananalisisbarang');
    Route::get('laporananalisisbarang', [AnalisisbarangController::class, 'filterdateanalisisbarang'])->name('laporananalisisbarang');
    Route::get('laporananalisisbarangpdf/filter={filter}', [AnalisisbarangController::class, 'laporananalisisbarangpdf'])->name('laporananalisisbarangpdf');

    // Barang Jarang
    Route::get('laporannya/laporanbarangjarang', [BarangjarangController::class, 'cetakbarangjarangpertanggal'])->name('laporanbarangjarang');
    Route::get('laporanbarangjarang', [BarangjarangController::class, 'filterdatebarangjarang'])->name('filterdatebarangjarang');
    Route::get('laporanbarangjarangpdf/filter={filter}', [BarangjarangController::class, 'laporanbarangjarangpdf'])->name('laporanbarangjarangpdf');

    // Status
    Route::put('/pengiriman/{id}/status', [PengirimanController::class, 'updateStatusPengiriman'])->name('updateStatusPengiriman');
    Route::put('/requestbarang/{id}/status', [RequestbarangController::class, 'updateStatusRequest'])->name('updateStatusRequest');
    Route::put('/analisisbarang/{id}/status', [AnalisisbarangController::class, 'updateStatusAnalisis'])->name('updateStatusAnalisis');
    Route::put('/barangjarang/{id}/status', [BarangjarangController::class, 'updateStatusBarangJarang'])->name('updateStatusBarangJarang');
    Route::put('/pengembalian/{id}/status', [PengembalianController::class, 'updateStatuspengembalian'])->name('updateStatuspengembalian');
    // penerima Daerah
        Route::get('laporannya/penerima', [PengirimanController::class, 'penerima'])->name('penerima');
        Route::get('/penerimapdf', [PengirimanController::class, 'cetakpenerimaPdf'])->name('penerimapdf');

});



// Login Register
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/loginuser', [LoginController::class, 'loginuser'])->name('loginuser');
