<!DOCTYPE html>
<html>
<head>
    <title>Laporan Barang</title>
    <style>
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

        .label-success {
            background-color: #4CAF50;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
        }

    </style>
</head>
<body>
    <h2>Laporan Barang</h2>
    <p><strong>Periode:</strong> {{ date('d M Y', strtotime($awal)) }} - {{ date('d M Y', strtotime($akhir)) }}</p>

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
        @foreach ($barangArray as $key => $item)
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>{!! $item['barang']['kode_barang'] !!}</td>
        <td>{{ $item['barang']['nama_barang'] }}</td>
        <td>{{ format_uang($item['barang']['harga_jual']) }}</td>
        <td>{{ format_uang($item['jumlah_penjualan']) }}</td>
        <td>{{ format_uang($item['jumlah_penjualan'] * $item['barang']['harga_jual']) }}</td>
    </tr>
@endforeach
</tbody>
    </table>
</body>
</html>
