<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Pengiriman;
use App\Models\Distribarang;
use App\Models\Requestbarang;
use App\Models\Masterbarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Masterdinaspenerima;
use Illuminate\Support\Facades\Mail;
use App\Mail\LowStockNotification;

class PengirimanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('search')) {
            $pengiriman = Pengiriman::whereHas('requestbarang', function ($query) use ($request) {
                $query->where('nama', 'LIKE', '%' . $request->search . '%');
            })->paginate(10);
        } else {
            $pengiriman = Pengiriman::paginate(10);
        }

        return view('pengiriman.index', [
            'pengiriman' => $pengiriman,
        ]);
    }

    public function detail($id)
{
    $pengiriman = Pengiriman::with([
        'distribarang.requestbarang',
        'distribarang.masterbarang' // opsional, jika kamu butuh
    ])->findOrFail($id);

    return view('pengiriman.detail', compact('pengiriman'));
}

    public function create()
    {
        $requestbarang = Requestbarang::with(['masterbarang'])->get();
        // $masterbarang = Masterbarang::all();
        $masterdinaspenerima = Masterdinaspenerima::all();

        return view('pengiriman.create', [
            'requestbarang' => $requestbarang,
            'masterdinaspenerima' => $masterdinaspenerima,
            // 'masterbarang' => $masterbarang,
        ]);
    }

    public function store(Request $request)
    {
        // return $request->all();
        $request->validate([
            'id_masterdinaspenerima' => 'required|exists:masterdinaspenerimas,id',
            'tanggal' => 'required|date',
            'distribarang' => 'required|array|min:1',
            'distribarang.*.id_requestbarang' => 'required|exists:requestbarangs,id',
            'distribarang.*.qty' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // Data utama pengiriman
            $data = $request->only(['id_masterdinaspenerima', 'tanggal']);
            $data['nokirim'] = $this->generatenokirim(); // generate no kirim
            $pengiriman = Pengiriman::create($data);

            // Loop distribusi barang
            foreach ($request->distribarang as $item) {
                // $requestbarang = Requestbarang::where('id', $item['id_requestbarang'])->where('qty', '>=', $item['qty'])->first();

                $requestbarang = Requestbarang::where('id', $item['id_requestbarang'])->lockForUpdate()->first();

                if (!$requestbarang || $requestbarang->qty < $item['qty']) {
                    DB::rollBack();
                    return back()
                        ->withErrors(['stok' => 'Stok tidak cukup atau ID requestbarang tidak valid.'])
                        ->withInput();
                }

                // Kurangi stok
                $requestbarang->qty -= $item['qty'];
                $requestbarang->save();

                // Cek stok rendah untuk notifikasi email
                if ($requestbarang->qty < 5) {
                    try {
                        Mail::to('ferdyar5@gmail.com')->send(new LowStockNotification($requestbarang));
                    } catch (\Exception $e) {
                        // Log error atau abaikan agar transaksi utama tetap berjalan
                        \Illuminate\Support\Facades\Log::error("Gagal mengirim email stok rendah: " . $e->getMessage());
                    }
                }

                // Simpan distribusi barang
                Distribarang::create([
                    'id_requestbarang' => $requestbarang->id,
                    'id_masterbarang' => $requestbarang->id_masterbarang,
                    'qty' => $item['qty'],
                    'id_pengiriman' => $pengiriman->id,
                ]);
            }

            DB::commit();
            return redirect()->route('pengiriman.index')->with('success', 'Data telah ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            // return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan pengiriman.',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function generatenokirim()
    {
        // Mendapatkan surat terakhir berdasarkan tanggal
        $latestSurat = Pengiriman::orderBy('created_at', 'desc')->first();

        // Menangani kasus jika belum ada surat yang tersimpan
        if (!$latestSurat) {
            return 'SRT-DIST-001';
        }

        // Mendapatkan nomor surat terakhir dan increment
        $lastKode = $latestSurat->nokirim;
        $lastNumber = (int) substr($lastKode, -3);
        $newNumber = $lastNumber + 1;

        // Generate kode surat baru
        $newKode = 'SRT-DIST-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        return $newKode;
    }

    public function show($id) {}

    public function edit(Pengiriman $pengiriman)
    {
        $requestbarang = Requestbarang::all();
        $masterdinaspenerima = Masterdinaspenerima::all();

        return view('pengiriman.edit', [
            'item' => $pengiriman,
            'requestbarang' => $requestbarang,
            'masterdinaspenerima' => $masterdinaspenerima,
        ]);
    }

    public function update(Request $request, Pengiriman $pengiriman)
    {
        $data = $request->all();

        $pengiriman->update($data);

        //dd($data);

        return redirect()->route('pengiriman.index')->with('success', 'Data Telah diupdate');
    }

    public function destroy(Pengiriman $pengiriman)
    {
        DB::beginTransaction();

        try {
            // Ambil semua distribusi barang yang terkait
            $distribusi = $pengiriman->distribarang;

            foreach ($distribusi as $d) {
                $barang = Requestbarang::find($d->id_requestbarang);

                // Kembalikan stok
                if ($barang) {
                    $barang->qty += $d->qty;
                    $barang->save();
                }
            }

            // Hapus distribusi dan pengiriman
            $pengiriman->distribarang()->delete();
            $pengiriman->delete();

            DB::commit();
            return redirect()->route('pengiriman.index')->with('success', 'Data Telah dihapus dan stok dikembalikan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    //Approval Status
    public function updateStatusPengiriman(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Terkirim,Batal Dikirim',
        ]);

        $pengiriman = Pengiriman::findOrFail($id);

        $pengiriman->status = $validated['status'];
        $pengiriman->save();

        return redirect()->route('pengiriman.index')->with('success', 'Status surat berhasil diperbarui.');
    }

    //Report
    //  Laporan Buku pengiriman Filter
    public function cetakpengirimanpertanggal()
    {
        $pengiriman = Pengiriman::Paginate(10);

        return view('laporannya.laporanpengiriman', ['laporanpengiriman' => $pengiriman]);
    }

    public function filterdatepengiriman(Request $request)
    {
        $startDate = $request->input('dari');
        $endDate = $request->input('sampai');

        if ($startDate == '' && $endDate == '') {
            $laporanpengiriman = Pengiriman::paginate(10);
        } else {
            $laporanpengiriman = Pengiriman::whereDate('tanggal', '>=', $startDate)->whereDate('tanggal', '<=', $endDate)->paginate(10);
        }
        session(['filter_start_date' => $startDate]);
        session(['filter_end_date' => $endDate]);

        return view('laporannya.laporanpengiriman', compact('laporanpengiriman'));
    }

    public function laporanpengirimanpdf(Request $request)
    {
        $startDate = session('filter_start_date');
        $endDate = session('filter_end_date');

        if ($startDate == '' && $endDate == '') {
            $laporanpengiriman = Pengiriman::all();
        } else {
            $laporanpengiriman = Pengiriman::whereDate('tanggal', '>=', $startDate)->whereDate('tanggal', '<=', $endDate)->get();
        }

        // Render view dengan menyertakan data laporan dan informasi filter
        $pdf = PDF::loadview('laporannya.laporanpengirimanpdf', compact('laporanpengiriman'));
        return $pdf->download('laporan_laporanpengiriman.pdf');
    }

    // Report dinaspenerima
    public function penerima(Request $request)
    {
        // Ambil filter dari request, defaultnya adalah null
        $filter = $request->query('filter', null);

        // Ambil data pengiriman berdasarkan filter
        if ($filter === 'all' || empty($filter)) {
            $pengiriman = Pengiriman::paginate(10);
        } else {
            $pengiriman = Pengiriman::where('id_masterdinaspenerima', $filter)->paginate(10);
        }

        // Ambil data agregat
        $idDinasCounts = Pengiriman::select('id_masterdinaspenerima', DB::raw('count(*) as count'))->groupBy('id_masterdinaspenerima')->orderBy('id_masterdinaspenerima')->get();

        // Ambil data master anggota
        $masterdinaspenerima = Masterdinaspenerima::all();

        return view('laporannya.penerima', [
            'pengiriman' => $pengiriman,
            'idDinasCounts' => $idDinasCounts,
            'filter' => $filter,
            'masterdinaspenerima' => $masterdinaspenerima,
        ]);
    }

    // Fungsi untuk mencetak PDF
    public function cetakpenerimaPdf(Request $request)
    {
        $filter = $request->query('filter', null);

        // Handle filtering
        if ($filter === 'all' || empty($filter)) {
            $pengiriman = Pengiriman::all();
        } else {
            $pengiriman = Pengiriman::where('id_masterdinaspenerima', $filter)->get();
        }

        // Get aggregated data
        $idDinasCounts = Pengiriman::groupBy('id_masterdinaspenerima')->orderBy('id_masterdinaspenerima')->select(DB::raw('count(*) as count, id_masterdinaspenerima'))->get();

        // Load view and convert to PDF
        $pdf = PDF::loadView('laporannya.penerimapdf', [
            'pengiriman' => $pengiriman,
            'idDinasCounts' => $idDinasCounts,
            'filter' => $filter,
        ]);

        // Return the generated PDF as a download
        return $pdf->download('laporan_penerima.pdf');
    }
}
