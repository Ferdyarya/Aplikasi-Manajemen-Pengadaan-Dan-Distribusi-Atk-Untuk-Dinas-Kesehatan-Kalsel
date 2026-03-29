<x-mail::message>
# Peringatan Stok Rendah

Stok untuk barang **{{ $item->masterbarang->nama }}** saat ini kurang dari 5 (Sisa: **{{ $item->qty }}**).

Mohon segera lakukan pengadaan atau pengecekan stok.

<x-mail::button :url="config('app.url')">
Lihat Dashboard
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
