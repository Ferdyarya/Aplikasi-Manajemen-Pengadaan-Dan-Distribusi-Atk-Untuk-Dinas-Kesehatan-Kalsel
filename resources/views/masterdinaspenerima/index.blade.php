@extends('layout.admin')
@push('css')
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />

    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"
            integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g=="
            crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
@endpush
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="content-wrapper">
                    <div class="content-header">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">Master Data Bidang Penerima</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">

                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="container">
                        {{-- search --}}
                        <div class="row g-3 align-items-center mb-4">
                            <div class="col-auto">
                                <form action="masterdinaspenerima" method="GET">
                                    <input type="text" id="search" name="search" class="form-control"
                                        placeholder="Search">
                                </form>
                            </div>

                            @if (Auth::user()->hakakses('petugas') || Auth::user()->hakakses('admin'))
                                <div class="col-auto">
                                    <a href="{{ route('masterdinaspenerima.create') }}" class="btn btn-primary">
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
                                        <th class="px-6 py-2">Nama Bidang</th>
                                        {{-- <th class="px-6 py-2">Alamat</th>
                                  <th class="px-6 py-2">Daerah</th>
                                  <th class="px-6 py-2">Pimpinan</th> --}}
                                         @if (Auth::user()->hakakses('petugas') || Auth::user()->hakakses('admin'))
<th class="px-6 py-2">Action</th>
@endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($masterdinaspenerima as $index => $item)
                                        <tr>
                                            <th class="px-6 py-2">{{ $index + $masterdinaspenerima->firstItem() }}</th>
                                            <td class="px-6 py-2">{{ $item->namadinas }}</td>
                                            {{-- <td class="px-6 py-2">{{ $item->alamat }}</td>
                                  <td class="px-6 py-2">{{ $item->daerah }}</td>
                                  <td class="px-6 py-2">{{ $item->pimpinan }}</td> --}}
                                            @if (Auth::user()->hakakses('petugas') || Auth::user()->hakakses('admin'))
                                                <td>
                                                    <a href="{{ route('masterdinaspenerima.edit', $item->id) }}"
                                                        class="btn btn-primary">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('masterdinaspenerima.destroy', $item->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                                    </form>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $masterdinaspenerima->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Optional JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <!-- <script>
        @if (Session::has('success'))
            toastr.success("{{ Session::get('success') }}")
        @endif
    </script> -->
@endpush
