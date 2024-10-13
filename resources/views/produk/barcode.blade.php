<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Barcode</title>

    <style>
        .text-center {
            text-align: center;
        }
        img {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <table width="100%">
        <tr>
            @foreach ($dataproduk as $produk)
                <td class="text-center">
                    <p>{{$produk->nama_produk}} - Rp. {{ format_uang($produk->harga_diskon) }}</p>
                    @php
                        $barcode = DNS1D::getBarcodePNG($produk->kode_produk, 'C39');
                    @endphp
                    <img src="data:image/png;base64, {{$barcode}}" 
                         alt="{{$produk->kode_produk}}" 
                         width="180" 
                         height="60">
                </td>
            @endforeach
        </tr>
    </table>
</body>
</html>
