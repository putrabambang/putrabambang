<!-- resources/views/laporanbarang/excel.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <style>
        /* CSS styling for the Excel output */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Laporan Barang</h2>
    <p><strong>Periode:</strong> {{ date('d M Y', strtotime($tanggal)) }} - {{ date('d M Y', strtotime($tanggalAkhir)) }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Harga Jual</th>
                <th>Jumlah Terjual</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @foreach ($barang as $item)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $barangData[$item->id_barang]->kode_barang }}</td>
                    <td>{{ $barangData[$item->id_barang]->nama_barang }}</td>
                    <td>{{ 'Rp. ' . format_uang($barangData[$item->id_barang]->harga_jual) }}</td>
                    <td>{{ format_uang($item->jumlah_penjualan) }}</td>
                    <td>{{ 'Rp. ' . format_uang($item->jumlah_penjualan * $barangData[$item->id_barang]->harga_jual) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
