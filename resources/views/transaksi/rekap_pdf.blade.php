<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h2,
        h3 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .page-break {
            page-break-before: always;
        }

        table {
            page-break-inside: auto;
        }

        tr {
            page-break-inside: avoid;
        }

        .header {
            text-align: left;
            border-bottom: 2px solid #000;
            margin-bottom: 10px;
            padding-bottom: 10px;
        }

        .header img {
            float: left;
            width: 70px;
            margin-right: 10px;
        }

        .header .instansi {
            font-size: 16px;
            font-weight: bold;
        }

        .header .alamat {
            font-size: 11px;
        }

        .footer {
            width: 100%;
            text-align: right;
            position: fixed;
            bottom: 30px;
            left: 0;
        }

        .footer .ttd {
            display: inline-block;
            text-align: center;
            margin-right: 40px;
        }

        .footer img {
            width: 90px;
            margin-bottom: 2px;
        }

        .wrap-border {
            border: 2px solid #000;
            padding: 18px 18px 80px 18px;
            /* padding bawah lebih besar untuk footer */
            min-height: 95vh;
            box-sizing: border-box;
        }
    </style>
</head>

<body>
    <div class="wrap-border">
        {{-- HEADER RESMI --}}
        <div class="header">
            <img src="{{ public_path('gambar/logo_nota.png') }}" style="filter: grayscale(100%);"
                alt="Logo Kabupaten Malang">
            <div style="margin-left: 10px;">
                <div class="instansi">PEMERINTAH KABUPATEN MALANG<br>DINAS LINGKUNGAN HIDUP</div>
                <div class="alamat">
                    Jl. Panji No. 158 Lt.8 Kepanjen Telepon/Fax (0341) 392029<br>
                    E-mail: dinas.lh@malangkab.go.id – Website: http://www.malangkab.go.id<br>
                    KEPANJEN 65163
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>

        <hr style="border: 1px solid #000; margin-bottom: 20px;">

        <h2>
            Laporan Transaksi
            - {{ $bulan ? DateTime::createFromFormat('!m', $bulan)->format('F') : '' }} {{ $tahun }}
            @if (!empty($status))
                @php
                    $statusLabel = match ($status) {
                        'settlement' => 'Lunas',
                        'pending' => 'Belum Bayar',
                        'menunggak' => 'Menunggak',
                        default => ucfirst($status),
                    };
                @endphp
                - {{ $statusLabel }}
            @endif
        </h2>

        {{-- Info Laporan --}}
        <div
            style="margin-bottom: 18px; margin-top: 10px; display: flex; justify-content: space-between; align-items: flex-start;">
            <ul style="list-style: none; margin: 0; padding: 0; font-size: 13px;">
                @if (!empty($tanggalMulai) && !empty($tanggalSelesai))
                    <li>Rentang: {{ \Carbon\Carbon::parse($tanggalMulai)->format('d-m-Y') }} s/d
                        {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d-m-Y') }}</li>
                @endif
                @if (!empty($kecamatan_id))
                    <li>Kecamatan: {{ \App\Models\Kecamatan::find($kecamatan_id)?->nama ?? '-' }}</li>
                @endif
                @if (!empty($kelurahan_id))
                    <li>Kelurahan: {{ \App\Models\Kelurahan::find($kelurahan_id)?->nama ?? '-' }}</li>
                @endif
                <li>Dicetak: {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}</li>
            </ul>
            <div style="text-align: right; min-width: 180px;">
                <span style="font-weight: bold; font-size: 15px;">Total:
                    Rp{{ number_format($total_pembayaran, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Transaksi Tagihan Tetap --}}
        @php $no = 1; @endphp
        <h3>Transaksi Tagihan Tetap</h3>
        @if($transaksiTetap->count())
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Invoice</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Bulan</th>
                        <th>Tahun</th>
                        <th>Status</th>
                        <th>Tanggal Bayar</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksiTetap as $trx)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $trx->order_id }}</td>
                            <td>{{ $trx->tagihan->warga->NIK }}</td>
                            <td>{{ $trx->tagihan->warga->pengguna->nama ?? '-' }}</td>
                            <td>{{ date('F', mktime(0, 0, 0, $trx->tagihan->bulan, 1)) }}</td>
                            <td>{{ $trx->tagihan->tahun }}</td>
                            <td>
                                @if ($trx->status == 'settlement')
                                    Lunas
                                @elseif ($trx->status_menunggak)
                                    Menunggak
                                @else
                                    Belum Bayar
                                @endif
                            </td>
                            <td>
                                @if ($trx->status == 'settlement')
                                    {{ $trx->updated_at->format('d-m-Y H:i') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>Rp{{ number_format($trx->amount, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Tidak ada transaksi tagihan tetap yang sesuai.</p>
        @endif

        {{-- Halaman Baru --}}
        <div class="page-break"></div>

        {{-- Transaksi Tagihan Tidak Tetap --}}
        @php $no = 1; @endphp
        <h3>Transaksi Tagihan Retasi</h3>
        @if($transaksiTidakTetap->count())
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Invoice</th>
                        <th>Tanggal Tagihan</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Volume</th>
                        <th>Status</th>
                        <th>Tanggal Bayar</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksiTidakTetap as $trx)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $trx->order_id }}</td>
                            <td>{{ $trx->tagihan->tanggal_tagihan }}</td>
                            <td>{{ $trx->tagihan->warga->NIK ?? '-' }}</td>
                            <td>{{ $trx->tagihan->warga->pengguna->nama ?? '-' }}</td>
                            <td>{{$trx->tagihan->volume}}</td>
                            <td>
                                @if ($trx->status == 'settlement')
                                    Lunas
                                @elseif ($trx->status_menunggak)
                                    Menunggak
                                @else
                                    Belum Bayar
                                @endif
                            </td>
                            <td>
                                @if ($trx->status == 'settlement')
                                    {{ $trx->updated_at->format('d-m-Y H:i') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>Rp{{ number_format($trx->amount, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Tidak ada transaksi tagihan tidak tetap yang sesuai.</p>
        @endif

        {{-- FOOTER RESMI (hanya di halaman terakhir) --}}
        <div style="height: 60px;"></div>
        <div class="footer" style="position: static; width: 100%; text-align: right; margin-top: 40px;">
            <div class="ttd" style="margin-right: 40px;">
                <span>Ditandatangani secara elektronik oleh<br>
                    Kepala Dinas Lingkungan Hidup<br>
                    Kabupaten Malang</span><br>
                <img src="{{ public_path('gambar/qr_ttd.png') }}" alt="QR TTD"><br>
                <b>Dr. Ahmad Dzulfikar Nurrahman, S.T, M.T</b>
            </div>
        </div>
    </div>
</body>

</html>