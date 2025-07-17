<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <style type="text/css">
        table tr td,
        table tr th {
            font-size: 9pt;
        }

        body {
            font-family: arial;

        }

        table {
            border-bottom: 4px solid #000;
            /* padding: 2px */
        }

        .tengah {
            text-align: center;
            line-height: 5px;
        }

        #warnatable th {
            padding-top: 12px;
            padding-bottom: 12px;
            /* text-align: left; */
            background-color: #feed00;
            color: rgb(0, 0, 0);
            /* text-align: center; */
        }

        #warnatable tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #warnatable tr:hover {
            background-color: #ddd;
        }

        .textmid {
            /* text-align: center; */
        }

        .signature {
            position: absolute;
            margin-top: 20px;
            text-align: right;
            right: 50px;
            font-size: 14px;
        }

        .signaturesewa {
            position: absolute;
            margin-top: 20px;
            text-align: left;
            left: 50px;
            /* Mengubah dari right ke left untuk menempatkan di kiri */
            font-size: 14px;
        }

        .date-container {
            font-family: arial;
            text-align: left;
            font-size: 14px;
        }
    </style>

    <div class="rangkasurat">
        <table width="100%">
            <tr>
                <td><img src="{{ public_path('assets/logokesehatan.png') }}" alt="logo" width="140px"></td>
                <td class="tengah">
                    <h4> DINAS KESEHATAN BANJARBARU </h4>
                    <p>Gedung Berintan Lantai 1, Jl. A Yani KM. 40, Martapura, Cindai Alus, Martapura, Cindai Alus, Kec. Martapura, Kabupaten Banjar, Kalimantan Selatan</p>

                </td>
            </tr>
        </table>
    </div>

    <center>
        <h5 class="mt-4">Rekap Laporan Barang Masuk Alat Tulis Kedinasan</h5>
    </center>

    <br>

    <table class='table table-bordered' id="warnatable">
        <thead>
            <tr>
                <th class="px-6 py-2">No</th>
                <th class="px-6 py-2">Tanggal</th>
                <th class="px-6 py-2">Nama Barang</th>
                <th class="px-6 py-2">Qty</th>
                <th class="px-6 py-2">Supplyment</th>
                <th class="px-6 py-2">Kebutuhan</th>
                <th class="px-6 py-2">Status</th>
            </tr>
        </thead>
        <tbody>
            {{-- @php
            $grandTotal = 0;
            @endphp --}}

            @foreach ($laporanrequestbarang as $item)
                <tr>
                    <td class="px-6 py-6">{{ $loop->iteration }}</td>
                    <td class="px-6 py-2">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                    <td class="px-6 py-2">{{ $item->masterbarang->nama }}</td>
                    <td class="px-6 py-2">{{ $item->qty }} PCS</td>
                    <td class="px-6 py-2">{{ $item->mastersupplyment->nama }}</td>
                    <td class="px-6 py-2">{{ $item->kebutuhan }}</td>
                    <td class="px-6 py-2">
                        @if($item->status == 'Terverifikasi')
                            <span class="p-2 mb-2 bg-success text-black rounded">Terverifikasi</span> <!-- Green for verified -->
                        @elseif($item->status == 'Ditolak')
                            <span class="p-2 mb-2 bg-danger text-black rounded">Ditolak</span> <!-- Red/orange for rejected -->
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="date-container">
        Banjarmasin, <span class="formatted-date">{{ now()->format('d-m-Y') }}</span>
    </div>
    <p class="signature">(Pimpinan)</p>
</body>

</html>
