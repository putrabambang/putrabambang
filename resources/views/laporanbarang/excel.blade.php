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
    <p><strong>Periode:</strong> {{ date('d M Y', strtotime($tanggalAwal)) }} - {{ date('d M Y', strtotime($tanggalAkhir)) }}</p>

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
            @foreach ($barangData as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item['kode_barang'] }}</td>
                    <td>{{ $item['nama_barang'] }}</td>
                    <td>{{ 'Rp. ' . format_uang($item['harga_jual']) }}</td>
                    <td>{{ format_uang($item['jumlah_penjualan']) }}</td>
                    <td>{{ 'Rp. ' . format_uang($item['subtotal']) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
