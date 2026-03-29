<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $pengirimanCount = \App\Models\Pengiriman::count();
        $pengembalianCount = \App\Models\Pengembalian::count();
        $requestbarangCount = \App\Models\Requestbarang::count();
        $masterbarangCount = \App\Models\Masterbarang::count();

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
    }
}
