<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>
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
    </style>
</head>

<body>
    <h2>
        Laporan Keuangan
        - {{ $bulan ? DateTime::createFromFormat('!m', $bulan)->format('F') : '' }} {{ $tahun }}
        @if (!empty($status))
            - {{ ucfirst($status) }}
        @endif
    </h2>

    <p><strong>Total Pemasukan:</strong> Rp{{ number_format($total_pembayaran, 0, ',', '.') }}</p>

    {{-- Transaksi Tagihan Tetap --}}
    @php $no = 1; @endphp
    <h3>Transaksi Tagihan Tetap</h3>
    @if($transaksiTetap->count())
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Order ID</th>
                    <th>Nama Warga</th>
                    <th>Bulan</th>
                    <th>Tahun</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksiTetap as $trx)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $trx->order_id }}</td>
                        <td>{{ $trx->tagihan->warga->pengguna->nama ?? '-' }}</td>
                        <td>{{ date('F', mktime(0, 0, 0, $trx->tagihan->bulan, 1)) }}</td>
                        <td>{{ $trx->tagihan->tahun }}</td>
                        <td>Rp{{ number_format($trx->amount, 0, ',', '.') }}</td>
                        <td>
                            @if ($trx->status == 'settlement')
                                Lunas
                            @elseif ($trx->status_menunggak)
                                Menunggak
                            @else
                                Belum Bayar
                            @endif
                        </td>
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
    <h3>Transaksi Tagihan Tidak Tetap</h3>
    @if($transaksiTidakTetap->count())
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Order ID</th>
                    <th>Tanggal Tagihan</th>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Volume</th>
                    <th>Jumlah</th>
                    <th>Status</th>
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
                        <td>Rp{{ number_format($trx->amount, 0, ',', '.') }}</td>
                        <td>
                            @if ($trx->status == 'settlement')
                                Lunas
                            @elseif ($trx->status_menunggak)
                                Menunggak
                            @else
                                Belum Bayar
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Tidak ada transaksi tagihan tidak tetap yang sesuai.</p>
    @endif

</body>

</html>