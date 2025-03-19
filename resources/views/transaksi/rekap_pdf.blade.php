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
    <h2 class="text-center">Laporan Keuangan - {{ date('F Y', mktime(0, 0, 0, $bulan, 1, $tahun)) }}</h2>
    <p><strong>Total Pemasukan:</strong> Rp {{ number_format($total_pembayaran, 0, ',', '.') }}</p>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Periode</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Jenis Retribusi</th>
                <th>Jumlah Bayar</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi as $t)
                <tr>
                    <td>{{ $t->order_id }}</td>
                    <td>{{ date('F', mktime(0, 0, 0, $t->tagihan->bulan, 1)) }} {{ $t->tagihan->tahun }}
                    </td>
                    <td>{{ $t->tagihan->NIK }}</td>
                    <td>{{ $t->tagihan->warga->pengguna->nama }}</td>
                    <td>{{ $t->tagihan->jenis_retribusi }}</td>
                    <td>Rp {{ number_format($t->amount, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge bg-success">Lunas</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>