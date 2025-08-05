<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Masterbarang;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use App\Models\Requestbarang;
use App\Models\Masterdinaspenerima;

class PengembalianController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('search')) {
            $pengembalian = Pengembalian::whereHas('masterbarang', function ($query) use ($request) {
                $query->where('nama', 'LIKE', '%' . $request->search . '%');
            })->paginate(10);
        } else {
            $pengembalian = Pengembalian::paginate(10);
        }

        return view('pengembalian.index', [
            'pengembalian' => $pengembalian,
        ]);
    }

    public function create()
    {
        $masterbarang = Masterbarang::all();
        $requestbarang = Requestbarang::all();
        $masterdinaspenerima = Masterdinaspenerima::all();

        return view('pengembalian.create', [
            'masterbarang' => $masterbarang,
            'masterdinaspenerima' => $masterdinaspenerima,
            'requestbarang' => $requestbarang,
        ]);
    }

    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'id_requestbarang' => 'required|exists:requestbarangs,id',
            'qty' => 'required|numeric|min:1',
            'buktikembali' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Ambil data requestbarang
        $requestbarang = Requestbarang::find($request->id_requestbarang);

        // Tambahkan stok (bukan kurangi)
        $requestbarang->qty += $request->qty;
        $requestbarang->save();

        // Simpan data pengembalian
        $data = Pengembalian::create($request->all());

        // Upload file bukti jika ada
        if ($request->hasFile('buktikembali')) {
            $filename = $request->file('buktikembali')->getClientOriginalName();
            $request->file('buktikembali')->move('buktikembali/', $filename);
            $data->buktikembali = $filename;
            $data->save();
        }

        return redirect()->route('pengembalian.index')->with('success', 'Data telah ditambahkan dan stok barang telah ditambah.');
    }

    public function updateStatuspengembalian(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Terverifikasi,Ditolak',
        ]);

        // Find the rawatrumahkaca entry by ID
        $pengembalian = Pengembalian::findOrFail($id);

        // Update the status based on the form input
        $pengembalian->status = $validated['status'];
        $pengembalian->save();

        // Redirect back to the suratmasuk page with a success message
        return redirect()->route('pengembalian.index')->with('success', 'Status Berhasil Diperbarui.');
    }

    public function show($id) {}

    public function edit(Pengembalian $pengembalian)
    {
        $masterbarang = Masterbarang::all();
        $requestbarang = Requestbarang::all();
        $masterdinaspenerima = Masterdinaspenerima::all();

        return view('pengembalian.edit', [
            'item' => $pengembalian,
            'masterbarang' => $masterbarang,
            'masterdinaspenerima' => $masterdinaspenerima,
            'requestbarang' => $requestbarang,
        ]);
    }

    public function update(Request $request, Pengembalian $pengembalian)
    {
        $data = $request->all();

        $pengembalian->update($data);

        //dd($data);

        return redirect()->route('pengembalian.index')->with('success', 'Data Telah diupdate');
    }

    public function destroy(Pengembalian $pengembalian)
    {
        // Ambil data requestbarang terkait
        $requestbarang = Requestbarang::find($pengembalian->id_requestbarang);

        // Jika data requestbarang ditemukan, kurangi qty-nya
        if ($requestbarang) {
            $requestbarang->qty -= $pengembalian->qty;

            // Pastikan qty tidak negatif
            if ($requestbarang->qty < 0) {
                $requestbarang->qty = 0;
            }

            $requestbarang->save();
        }

        // Hapus data pengembalian
        $pengembalian->delete();

        return redirect()->route('pengembalian.index')->with('success', 'Data telah dihapus dan stok telah dikurangi.');
    }

    //Approval Status
    //     public function updateStatusLokasi(Request $request, $id)
    // {
    //     $validated = $request->validate([
    //         'status' => 'required|in:Terverifikasi,Ditolak',
    //     ]);

    //     $pengembalian = pengembalian::findOrFail($id);

    //     $pengembalian->status = $validated['status'];
    //     $pengembalian->save();

    //     return redirect()->route('pengembalian.index')->with('success', 'Status surat berhasil diperbarui.');
    // }

    //Report
    //  Laporan Buku pengembalian Filter
    public function cetakpengembalianpertanggal()
    {
        $pengembalian = Pengembalian::Paginate(10);

        return view('laporannya.laporanpengembalian', ['laporanpengembalian' => $pengembalian]);
    }

    public function filterdatepengembalian(Request $request)
    {
        $startDate = $request->input('dari');
        $endDate = $request->input('sampai');

        if ($startDate == '' && $endDate == '') {
            $laporanpengembalian = Pengembalian::paginate(10);
        } else {
            $laporanpengembalian = Pengembalian::whereDate('tanggal', '>=', $startDate)->whereDate('tanggal', '<=', $endDate)->paginate(10);
        }
        session(['filter_start_date' => $startDate]);
        session(['filter_end_date' => $endDate]);

        return view('laporannya.laporanpengembalian', compact('laporanpengembalian'));
    }

    public function laporanpengembalianpdf(Request $request)
    {
        $startDate = session('filter_start_date');
        $endDate = session('filter_end_date');

        if ($startDate == '' && $endDate == '') {
            $laporanpengembalian = Pengembalian::all();
        } else {
            $laporanpengembalian = Pengembalian::whereDate('tanggal', '>=', $startDate)->whereDate('tanggal', '<=', $endDate)->get();
        }

        // Render view dengan menyertakan data laporan dan informasi filter
        $pdf = PDF::loadview('laporannya.laporanpengembalianpdf', compact('laporanpengembalian'));
        return $pdf->download('laporan_laporanpengembalian.pdf');
    }
}
