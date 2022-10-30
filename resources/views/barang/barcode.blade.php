<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Barcode</title>

    <style>
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <table width="100%">
    @for ($i = 0; $i < $jumlahcetak; $i++)
        <tr>
            @foreach ($databarang as $barang)
                <td class="text-left" >
                   <p>{{ $barang->kode_barang }}</p> 
                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barang->kode_barang, 'C39') }}" 
                        alt="{{ $barang->kode_barang }}"
                        width="110"
                        height="50">
                    <br class="text-center" >{{ $barang->nama_barang }}
                   <br> Rp. {{ format_uang($barang->harga_jual) }}

                </td>
                @if ($no++ % 1 == 0)
                    </tr><tr>
                @endif
            @endforeach
        </tr>
    @endfor
    </table>
</body>
</html>