<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>barcode</title>

    <?php
    $style = '
    <style>
        * {
            font-family: "consolas", sans-serif;
        }
        p {
            display: block;
            margin: 3px;
            font-size: 15pt;
        }
        table td {
            font-size: 10pt;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }

        @media print {
            @page {
                margin: 0;
                size: 58mm 
    ';
    ?>
    <?php 
    $style .= 
        ! empty($_COOKIE['innerHeight'])
            ? $_COOKIE['innerHeight'] .'mm; }'
            : '}';
    ?>
    <?php
    $style .= '
            html, body {
                width: 70mm;
            }
            .btn-print {
                display: none;
            }
        }
    </style>
    ';
    ?>

    {!! $style !!}
</head>
<body onload="window.print()">
    <button class="btn-print" style="position: absolute; right: 1rem; top: rem;" onclick="window.print()">Print</button>
    <table width="100%" style="border: 0;">
    @for ($i = 0; $i < $jumlahcetak; $i++)
        <tr>
            @foreach ($databarang as $barang)
                <td class="text-center" >
                   <p>{{ $barang->kode_barang }}</p> 
                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barang->kode_barang, 'C39') }}" 
                        alt="{{ $barang->kode_barang }}"
                        width="210"
                        height="80">
                    <p class="text-center" >{{ $barang->nama_barang }}</p>
                   <p> Rp. {{ format_uang($barang->harga_jual) }}</p>

                </td>
                @if ($no++ % 1 == 0)
                    </tr><tr>
                @endif
            @endforeach
        </tr>
    @endfor
    </table>
    <script>
        let body = document.body;
        let html = document.documentElement;
        let height = Math.max(
                body.scrollHeight, body.offsetHeight,
                html.clientHeight, html.scrollHeight, html.offsetHeight
            );

        document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "innerHeight="+ ((height + 50) * 0.264583);
    </script>
</body>
</html>