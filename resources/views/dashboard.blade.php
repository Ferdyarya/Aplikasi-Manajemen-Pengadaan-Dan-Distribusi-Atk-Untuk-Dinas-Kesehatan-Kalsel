@extends('layout.admin')

@section('content')
    <!-- Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">

        <div class="container-fluid">
            <!-- Modal Low Stock -->
            @if($lowStockList->count() > 0)
            <div class="modal fade" id="lowStockModal" tabindex="-1" aria-labelledby="lowStockModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="lowStockModalLabel"><i class="ti ti-alert-triangle"></i> Peringatan Stok Rendah!</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-3">Daftar barang dengan stok kritis (di bawah 6):</p>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Kode</th>
                                            <th>Nama Barang</th>
                                            <th class="text-center">Stok</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($lowStockList as $item)
                                            <tr class="table-danger">
                                                <td>{{ $item->masterbarang->kodebarang ?? '-' }}</td>
                                                <td>{{ $item->masterbarang->nama }}</td>
                                                <td class="text-center"><strong>{{ $item->qty }}</strong></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <a href="{{ route('requestbarang.index') }}" class="btn btn-primary">Kelola Stok</a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Row 1: Dashboard Cards -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h3 class="card-title"><b>Laporan Hari Ini</b></h3>
                            {{-- {{ $dateNow->format('d F Y') }} --}}
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 col-md-3">
                                    <h4 class="text-black"><b>Jumlah Pengiriman Barang</b></h4>
                                    <h3>{{ $pengirimanCount }}</h3>
                                </div>
                                <div class="col-6 col-md-3">
                                    <h4 class="text-black"><b>Jumlah Pengembalian</b></h4>
                                    <h3>{{ $pengembalianCount }}</h3>
                                </div>
                                <div class="col-6 col-md-3">
                                    <h4 class="text-black"><b>Jumlah Request</b></h4>
                                    <h3>{{ $requestbarangCount }}</h3>
                                </div>
                                <div class="col-6 col-md-3">
                                    <h4 class="text-black"><b>Jumlah Stock Barang</b></h4>
                                    <h3 class="{{ $masterbarangCount < 6 ? 'low-stock-flash' : '' }}">{{ $masterbarangCount }}</h3>
                                </div>
                            </div>
                            {{-- <div class="row text-center mt-4">

                            <div class="col-6 col-md-3">
                                <h4 class="text-dark"><b>Permohonan Surat</b></h4>
                                <h3>/</h3>
                            </div>
                            <div class="col-6 col-md-3">
                                <h4 class="text-primary"><b>Surat Ditolak</b></h4>
                                <h3>/</h3>
                            </div>
                            <div class="col-6 col-md-3">
                                <h4 class="text-secondary"><b>Surat Terverifikasi</b></h4>
                                <h3>/</h3>
                            </div>
                        </div> --}}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 2: Charts -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title m-0"><b>Diagram Stock Barang</b></h4>
                        </div>
                        <div class="card-body">
                            <canvas id="stockChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title m-0"><b>Diagram Pengiriman Barang</b></h4>
                        </div>
                        <div class="card-body">
                            <canvas id="pengirimanChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 3: Pie Charts -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title m-0"><b>Persentase Stock Barang</b></h4>
                        </div>
                        <div class="card-body">
                            <canvas id="stockPieChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title m-0"><b>Persentase Pengiriman Barang</b></h4>
                        </div>
                        <div class="card-body">
                            <canvas id="pengirimanPieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 4: Pending Deliveries Table -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title m-0"><b>Pengiriman Belum Terkirim</b></h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tablePending" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No Kirim</th>
                                            <th>Penerima</th>
                                            <th>Tanggal</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pendingPengiriman as $p)
                                            <tr>
                                                <td>{{ $p->nokirim }}</td>
                                                <td>{{ $p->masterdinaspenerima->nama ?? '-' }}</td>
                                                <td>{{ $p->tanggal }}</td>
                                                <td>
                                                    <span class="badge bg-warning text-dark">{{ $p->status ?? 'Menunggu' }}</span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('pengiriman.detail', $p->id) }}" class="btn btn-sm btn-primary">Detail</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Semua barang telah terkirim.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        console.log("Dashboard script ready");
        var table = $('#tablePending').DataTable({
            "dom": 'rtip', 
            "language": {
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "paginate": {
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                },
                "emptyTable": "Tidak ada data pengiriman yang menunggu",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
            }
        });

        $('#searchInput').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Tampilkan Modal Stok Rendah jika ada data
        console.log("Checking for low stock modal...");
        if ($('#lowStockModal').length > 0) {
            console.log("Low stock modal found, showing...");
            var modalEl = document.getElementById('lowStockModal');
            var myModal = new bootstrap.Modal(modalEl);
            myModal.show();
        } else {
            console.log("No low stock modal found in DOM");
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
        const backgroundColors = [
            'rgba(255, 99, 132, 0.7)',
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 206, 86, 0.7)',
            'rgba(75, 192, 192, 0.7)',
            'rgba(153, 102, 255, 0.7)',
            'rgba(255, 159, 64, 0.7)',
            'rgba(199, 199, 199, 0.7)',
            'rgba(83, 102, 255, 0.7)',
            'rgba(40, 167, 69, 0.7)',
            'rgba(220, 53, 69, 0.7)'
        ];

        // Bar Charts
        const stokData = @json($stokData ?? []);
        const stockLabels = stokData.map(item => item.nama);
        const stockValues = stokData.map(item => item.total);

        new Chart(document.getElementById('stockChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: stockLabels,
                datasets: [{
                    label: 'Jumlah Stock',
                    data: stockValues,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
        });

        const pengirimanData = @json($pengirimanData ?? []);
        const pengirimanLabels = pengirimanData.map(item => item.nama);
        const pengirimanValues = pengirimanData.map(item => item.total);

        new Chart(document.getElementById('pengirimanChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: pengirimanLabels,
                datasets: [{
                    label: 'Jumlah Pengiriman',
                    data: pengirimanValues,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
        });

        // Pie Charts
        new Chart(document.getElementById('stockPieChart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: stockLabels,
                datasets: [{
                    data: stockValues,
                    backgroundColor: backgroundColors,
                }]
            },
            options: { responsive: true }
        });

        new Chart(document.getElementById('pengirimanPieChart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: pengirimanLabels,
                datasets: [{
                    data: pengirimanValues,
                    backgroundColor: backgroundColors,
                }]
            },
            options: { responsive: true }
        });
    });
</script>
@endpush

@push('css')
<style>
    @keyframes flash-red {
        0% { color: black; transform: scale(1); }
        50% { color: red; transform: scale(1.1); text-shadow: 0 0 10px rgba(255, 0, 0, 0.5); }
        100% { color: black; transform: scale(1); }
    }
    .low-stock-flash {
        animation: flash-red 0.8s infinite;
        font-weight: bold;
        display: inline-block;
    }
</style>
@endpush

@endsection
