<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Tagihan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2, h3 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .page-break { page-break-before: always; }
        table { page-break-inside: auto; }
        tr { page-break-inside: avoid; }
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
            min-height: 95vh;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <div class="wrap-border">
        {{-- HEADER RESMI --}}
        <div class="header">
            <img src="{{ public_path('gambar/logo_nota.png') }}" style="filter: grayscale(100%);" alt="Logo Kabupaten Malang">
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
            Laporan Tagihan
            - {{ $bulan ? DateTime::createFromFormat('!m', $bulan)->format('F') : '' }} {{ $tahun }}
            @if (!empty($status))
                @php
                    $statusLabel = match($status) {
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
        <div style="margin-bottom: 18px; margin-top: 10px;">
            @if (!empty($tanggalMulai) && !empty($tanggalSelesai))
                <div><strong>Periode:</strong> {{ \Carbon\Carbon::parse($tanggalMulai)->format('d-m-Y') }} s/d
                    {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d-m-Y') }}
                </div>
            @endif

            {{-- Tampilkan kecamatan dan kelurahan jika ada --}}
            @if (!empty($kecamatan_id))
                <div><strong>Kecamatan:</strong>
                    {{ \App\Models\Kecamatan::find($kecamatan_id)?->nama ?? '-' }}
                </div>
            @endif

            @if (!empty($kelurahan_id))
                <div><strong>Kelurahan:</strong>
                    {{ \App\Models\Kelurahan::find($kelurahan_id)?->nama ?? '-' }}
                </div>
            @endif

            <div><strong>Dicetak pada:</strong> {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}</div>
            <div><strong>Total Pemasukan:</strong> Rp{{ number_format($total_pembayaran, 0, ',', '.') }}</div>
        </div>

        {{-- Tagihan Tetap --}}
        <h3>Tagihan Tetap</h3>
        @if($tagihanTetap->count())
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Bulan</th>
                        <th>Tahun</th>
                        <th>Status</th>
                        <th>Status Transaksi</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach($tagihanTetap as $t)
                        @php
                            $trx = $transaksi->first(fn($tr) => $tr->tagihan_id == $t->id);
                        @endphp
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $t->warga->NIK ?? '-' }}</td>
                            <td>{{ $t->warga->pengguna->nama ?? '-' }}</td>
                            <td>{{ $t->bulan ? date('F', mktime(0, 0, 0, $t->bulan, 1)) : '-' }}</td>
                            <td>{{ $t->tahun ?? '-' }}</td>
                            <td>{{ $t->status }}</td>
                            <td>
                                @if ($trx && $trx->status == 'settlement')
                                    Lunas
                                @elseif ($trx && $trx->status_menunggak)
                                    Menunggak
                                @elseif ($trx)
                                    Belum Bayar
                                @else
                                    -
                                @endif
                            </td>
                            <td>Rp{{ number_format($trx->amount ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Tagihan Tetap: Tidak ada data yang sesuai.</p>
        @endif

        <div class="page-break"></div>

        {{-- Tagihan Tidak Tetap --}}
        <h3>Tagihan Retasi</h3>
        @if($tagihanTidakTetap->count())
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Tanggal Tagihan</th>
                        <th>Volume</th>
                        <th>Status</th>
                        <th>Status Transaksi</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach($tagihanTidakTetap as $t)
                        @php
                            $trx = $transaksi->first(fn($tr) => $tr->tagihan_id == $t->id);
                        @endphp
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $t->warga->NIK ?? '-' }}</td>
                            <td>{{ $t->warga->pengguna->nama ?? '-' }}</td>
                            <td>{{ $t->tanggal_tagihan ? \Carbon\Carbon::parse($t->tanggal_tagihan)->format('d-m-Y') : '-' }}</td>
                            <td>{{ $t->volume ?? '-' }}</td>
                            <td>Rp{{ number_format($trx->amount ?? 0, 0, ',', '.') }}</td>
                            <td>{{ $t->status }}</td>
                            <td>
                                @if ($trx && $trx->status == 'settlement')
                                    Lunas
                                @elseif ($trx && $trx->status_menunggak)
                                    Menunggak
                                @elseif ($trx)
                                    Belum Bayar
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Tidak ada tagihan retasi yang sesuai.</p>
        @endif

        {{-- FOOTER RESMI --}}
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
