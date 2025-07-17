@extends('layout.admin')

@section('content')
    <!-- Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">

        <div class="container-fluid">
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
                                    <h3>{{ $masterbarangCount }}</h3>
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
        </div>
    </div>
@endsection
