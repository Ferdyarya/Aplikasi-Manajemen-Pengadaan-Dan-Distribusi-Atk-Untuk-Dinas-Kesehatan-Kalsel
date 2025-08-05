@extends('layout.admin')
@push('css')
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="content-wrapper">
                    <!-- Content Header (Page header) -->
                    <div class="content-header">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">Data Pendistribusian</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Data Pendistribusian</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="container">
                        {{-- search --}}
                        <div class="row g-3 align-items-center mb-4">
                            <div class="col-auto">
                                <form action="pengiriman" method="GET">
                                    <input type="text" id="search" name="search" class="form-control"
                                        placeholder="Search">
                                </form>
                            </div>
                            @if (Auth::user()->hakakses('petugas') || Auth::user()->hakakses('admin'))
                                <div class="col-auto">
                                    <a href="{{ route('pengiriman.create') }}" class="btn btn-primary">
                                        Tambah Data
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-2">No</th>
                                        <th class="px-6 py-2">No Kirim</th>
                                        <th class="px-6 py-2">Tanggal</th>
                                        {{-- <th class="px-6 py-2">Nama Barang</th> --}}
                                        {{-- <th class="px-6 py-2">Qty</th> --}}
                                        <th class="px-6 py-2">Dinas Pengirim</th>
                                        <th class="px-6 py-2">Status</th>
                                        @if (Auth::user()->hakakses('petugas') || Auth::user()->hakakses('admin'))
                                            <th class="px-6 py-2">Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($pengiriman as $index => $item)
                                        <tr>
                                            <th class="px-6 py-2">{{ $index + $pengiriman->firstItem() }}</th>
                                            <td class="px-6 py-2">{{ $item->nokirim }}</td>
                                            <td class="px-6 py-2">
                                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                            {{-- <td class="px-6 py-2">{{ $item->masterbarang->nama }}</td> --}}
                                            {{-- <td class="px-6 py-2">{{ $item->qty }}</td> --}}
                                            <td class="px-6 py-2">{{ $item->masterdinaspenerima->namadinas }}</td>
                                            <td class="px-6 py-2">
                                                @if ($item->status == 'Terkirim')
                                                    <span class="p-2 mb-2 bg-success text-black rounded">Terkirim</span>
                                                    <!-- Green for sent -->
                                                @elseif($item->status == 'Batal Dikirim')
                                                    <span class="p-2 mb-2 bg-danger text-black rounded">Batal Dikirim</span>
                                                    <!-- Red for canceled -->
                                                @else
                                                    @if (!Auth::user()->hakakses('pimpinan'))
                                                        <span class="p-2 mb-2 bg-warning text-black rounded">Belum
                                                            Dikirim</span> <!-- Waiting status -->
                                                    @endif
                                                    @if (Auth::user()->hakakses('pimpinan'))
                                                        <form action="{{ route('updateStatusPengiriman', $item->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <select name="status" class="form-control form-control-sm">
                                                                <option value="Terkirim"
                                                                    {{ $item->status == 'Terkirim' ? 'selected' : '' }}
                                                                    style="background-color: #28a745; color: white;">
                                                                    Terkirim</option>
                                                                <option value="Batal Dikirim"
                                                                    {{ $item->status == 'Batal Dikirim' ? 'selected' : '' }}
                                                                    style="background-color: #dc3545; color: white;">Batal
                                                                    Dikirim</option>
                                                                <option value="Belum Dikirim"
                                                                    {{ $item->status == 'Belum Dikirim' ? 'selected' : '' }}
                                                                    style="background-color: #ffc107; color: black;">Belum
                                                                    Dikirim</option>
                                                            </select>
                                                            <button type="submit"
                                                                class="btn btn-primary btn-sm mt-2">Update Status</button>
                                                        </form>
                                                    @endif
                                                @endif
                                            </td>
                                            @if (Auth::user()->hakakses('petugas') || Auth::user()->hakakses('admin'))
                                                <td class="px-6 py-2">
                                                    {{-- <a href="{{ route('pengiriman.edit', $item->id) }}"
                                                        class="btn btn-primary">Edit</a> --}}
                                                    <form action="{{ route('pengiriman.destroy', $item->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                                    </form>
                                                    @if (Auth::user()->hakakses('petugas') || Auth::user()->hakakses('admin') || Auth::user()->hakakses('pimpinan'))
                                                        <a href="{{ route('pengiriman.detail', $item->id) }}"
                                                            class="mt-1 btn btn-primary">
                                                            Detail
                                                        </a>
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $pengiriman->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script>
        @if (Session::has('success'))
            toastr.success("{{ Session::get('success') }}")
        @endif
    </script>
@endpush
