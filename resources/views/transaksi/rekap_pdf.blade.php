<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <h2 class="text-center">
        Laporan Keuangan -
        @if(!empty($bulan))
            {{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }}
        @else
            Tahun {{ $tahun }}
        @endif
        @if(!empty($status))
            - {{ $status == 'settlement' ? 'Lunas' : ($status == 'pending' ? 'Belum Bayar' : ucfirst($status)) }}
        @endif
    </h2>


    <p><strong>Total Pemasukan:</strong> Rp {{ number_format($total_pembayaran, 0, ',', '.') }}</p>

    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Warga</th>
                <th>Bulan</th>
                <th>Tahun</th>
                <th>Jumlah</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi as $key => $trx)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $trx->tagihan->warga->pengguna->nama ?? '-' }}</td>
                    <td>{{ date('F', mktime(0, 0, 0, $trx->tagihan->bulan, 1)) }}
                    </td>
                    <td>{{ $trx->tagihan->tahun }}</td>
                    <td>Rp {{ number_format($trx->amount, 0, ',', '.') }}</td>
                    <td>
                        @if($trx->status == 'settlement')
                            Lunas
                        @elseif($trx->status == 'pending')
                            Belum Bayar
                        @else
                            {{ ucfirst($trx->status) }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>