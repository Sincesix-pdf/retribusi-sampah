<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Pembayaran</title>
    <style>
        @page { margin: 0; }
        body {
            margin: 0;
            padding: 0;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 13px;
        }
        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: -1;
        }
        .content, .content2, .content3, .judul, .no-skrd {
            position: absolute;
            color: #98878A;
        }
        .no-skrd { top: 25px; right: 140px;}
        .judul { top: 125px; left: 200px; }
        .content { top: 180px; left: 60px; right: 60px; }
        .content2 { top: 180px; left: 300px; right: 60px; }
        .content3 { top: 280px; left: 60px; right: 60px; }
        
        .no-skrd {
            color: #31302E;
            text-align: center;
            font-weight: bold;
            line-height: 1.5;
        }
        .judul{
            text-align: center;
            font-weight: bolder;
            line-height: 1.5;
        }
        .judul p {
            margin: 0;
        }
        .content p, .content2 p {
            margin: 5px 0;
        }
        .content3 p {
            margin: 5px 0;
            font-style: italic;
            font-weight: bolder;
        }
    </style>
</head>
<body>
    <img src="{{ public_path('gambar/template_nota.png') }}" class="background" />

    <div class="no-skrd">
        <!-- <p>{{  $transaksi->tagihan_id }}</p> -->
    </div>

    <div class="judul">
        <p>BUKTI PEMBAYARAN RETRIBUSI</p>
        <p>PELAYANAN PERSAMPAHAN/KEBERSIHAN LINGKUNGAN</p>
    </div>

    <div class="content">
        <p>Nomor Invoice: {{ $transaksi->order_id }}</p>
        <p>Nama: {{ $transaksi->tagihan->warga->pengguna->nama }}</p>
        <p>Periode: {{ $bulanTagihan }} {{ $tahunTagihan }}</p>
    </div>

    <div class="content2">
        <p>Jumlah Pembayaran: Rp{{ number_format($transaksi->amount, 0, ',', '.') }}</p>
        <p>Tanggal Pembayaran: {{ $transaksi->updated_at->format('d-m-Y') }}</p>
        <p>Status: Berhasil</p>
    </div>

    <div class="content3">
        <p>*Terima kasih telah melakukan pembayaran. Harap simpan bukti pembayaran ini.</p>
    </div>
</body>
</html>
