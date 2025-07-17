<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Masterbarang;
use Illuminate\Http\Request;
use App\Models\Requestbarang;
use App\Models\Mastersupplyment;

class RequestbarangController extends Controller
{
    public function index(Request $request)
{
    if ($request->has('search')) {
        $requestbarang = Requestbarang::whereHas('masterbarang', function($query) use ($request) {
            $query->where('nama', 'LIKE', '%' . $request->search . '%');
        })->paginate(10);
    } else {
        $requestbarang = Requestbarang::paginate(10);
    }

    return view('requestbarang.index', [
        'requestbarang' => $requestbarang
    ]);
}


    public function create()
{
    $masterbarang = Masterbarang::all();
    $mastersupplyment = Mastersupplyment::all();

    return view('requestbarang.create', [
        'masterbarang' => $masterbarang,
        'mastersupplyment' => $mastersupplyment,
    ]);
}

public function store(Request $request)
{
    $data = $request->all();

    $requestBarang = Requestbarang::create($data);

    $barang = Masterbarang::find($data['id_masterbarang']);

    if ($barang) {
        // Tambah qty ke master data barang
        $barang->qty += $data['qty'];
        $barang->save();
    }

    return redirect()->route('requestbarang.index')->with('success', 'Data telah ditambahkan');
}




    public function show($id)
    {

    }


    public function edit(requestbarang $requestbarang)
    {
        $masterbarang = Masterbarang::all();
        $mastersupplyment = Mastersupplyment::all();

        return view('requestbarang.edit', [
            'item' => $requestbarang,
            'masterbarang' => $masterbarang,
            'mastersupplyment' => $mastersupplyment,
        ]);
    }


    public function update(Request $request, Requestbarang $requestbarang)
    {
        $data = $request->all();

        $requestbarang->update($data);

        //dd($data);

        return redirect()->route('requestbarang.index')->with('success', 'Data Telah diupdate');

    }


    public function destroy(Requestbarang $requestbarang)
    {
        $requestbarang->delete();
        return redirect()->route('requestbarang.index')->with('success', 'Data Telah dihapus');
    }

    //Approval Status
    public function updateStatusRequest(Request $request, $id)
{
    $validated = $request->validate([
        'status' => 'required|in:Terverifikasi,Ditolak',
    ]);

    $requestbarang = Requestbarang::findOrFail($id);

    $requestbarang->status = $validated['status'];
    $requestbarang->save();

    return redirect()->route('requestbarang.index')->with('success', 'Status surat berhasil diperbarui.');
}













    //Report
    //  Laporan Buku requestbarang Filter
     public function cetakrequestbarangpertanggal()
     {
         $requestbarang = Requestbarang::Paginate(10);

         return view('laporannya.laporanrequestbarang', ['laporanrequestbarang' => $requestbarang]);
     }

     public function filterdaterequestbarang(Request $request)
     {
         $startDate = $request->input('dari');
         $endDate = $request->input('sampai');

          if ($startDate == '' && $endDate == '') {
             $laporanrequestbarang = Requestbarang::paginate(10);
         } else {
             $laporanrequestbarang = Requestbarang::whereDate('tanggal','>=',$startDate)
                                         ->whereDate('tanggal','<=',$endDate)
                                         ->paginate(10);
         }
         session(['filter_start_date' => $startDate]);
         session(['filter_end_date' => $endDate]);

         return view('laporannya.laporanrequestbarang', compact('laporanrequestbarang'));
     }


     public function laporanrequestbarangpdf(Request $request )
     {
         $startDate = session('filter_start_date');
         $endDate = session('filter_end_date');

         if ($startDate == '' && $endDate == '') {
             $laporanrequestbarang = Requestbarang::all();
         } else {
             $laporanrequestbarang = Requestbarang::whereDate('tanggal', '>=', $startDate)
                                             ->whereDate('tanggal', '<=', $endDate)
                                             ->get();
         }

         // Render view dengan menyertakan data laporan dan informasi filter
         $pdf = PDF::loadview('laporannya.laporanrequestbarangpdf', compact('laporanrequestbarang'));
         return $pdf->download('laporan_laporanrequestbarang.pdf');
     }
}
