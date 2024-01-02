<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>barcode</title>

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
                size: 58mm;
            }
            html, body {
                width: 70mm;
            }
            .btn-print {
                display: none;
            }
        }

        @media screen {
            body {
                height: 100vh;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <button class="btn-print" style="position: absolute; right: 1rem; top: 1rem;" onclick="window.print()">Print</button>
    <table width="100%" style="border: 0;">
    @foreach ($result as $barang)
        @for ($i = 0; $i < $barang['jumlah']; $i++)
            <tr>
                <td class="text-center">
                    <p>{{ $barang['kode_barang'] }}</p>
                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barang['kode_barang'], 'C39') }}" 
                        alt="{{ $barang['kode_barang'] }}"
                        width="210"
                        height="50">
                    <p class="text-center">{{ $barang['nama_barang'] }}</p>
                    <p>Rp. {{ format_uang($barang['harga_jual']) }}</p>
                </td>
            </tr>
        @endfor
    @endforeach
    </table>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let body = document.body;
            let html = document.documentElement;
            let height = Math.max(
                body.scrollHeight, body.offsetHeight,
                html.clientHeight, html.scrollHeight, html.offsetHeight
            );

            // Round to the nearest integer
            height = Math.round(height);

            // Set body height in mm
            body.style.height = height + 'mm';
        });
    </script>

</body>
</html>
