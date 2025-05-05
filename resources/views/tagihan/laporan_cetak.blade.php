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
        
        /* Menambahkan aturan untuk pemisahan halaman */
        .page-break { page-break-before: always; }

        /* Agar tabel tidak terpotong ketika dicetak */
        table { page-break-inside: auto; }
        tr { page-break-inside: avoid; }
        
    </style>
</head>
<body>
    <h2>
        Laporan Tagihan 
        - {{ $bulan ? DateTime::createFromFormat('!m', $bulan)->format('F') : '' }} {{ $tahun }}
        @if (!empty($status))
            - {{ ucfirst($status) }}
        @endif
    </h2>

    {{-- Tagihan Tetap --}}
    @if($tagihanTetap->count())
        <h3>Tagihan Tetap</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Bulan/Tahun</th>
                    <th>Jumlah Tagihan</th>
                    <th>Status</th>
                    <th>Status Transaksi</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($tagihanTetap as $t)
                    @php
                        $trxMatch = $transaksi->where('tagihan_id', $t->id);
                        $statusTrx = $trxMatch->first();
                        $statusTransaksi = $statusTrx
                            ? ($statusTrx->status === 'settlement'
                                ? 'lunas'
                                : ($statusTrx->status_menunggak
                                    ? 'menunggak'
                                    : 'belum bayar'))
                            : 'belum bayar';
                    @endphp
                    @if (!$status || strtolower($statusTransaksi) === strtolower($status))
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $t->NIK ?? '??' }}</td>
                            <td>{{ $t->warga->pengguna->nama ?? '??' }}</td>
                            <td>{{ DateTime::createFromFormat('!m', $t->bulan)->format('F') }} {{ $t->tahun }}</td>
                            <td>Rp{{ number_format($t->tarif ?? 0, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($t->status ?? '??') }}</td>
                            <td>{{ ucfirst($statusTransaksi) }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @else
        <p>Tagihan Tetap: Tidak ada data yang sesuai.</p>
    @endif

    <!-- Halaman Baru -->
    <div class="page-break"></div>

    {{-- Tagihan Tidak Tetap --}}
    @if($tagihanTidakTetap->count())
        <h3>Tagihan Tidak Tetap</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Tarif</th>
                    <th>Volume</th>
                    <th>Total Tagihan</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Status Transaksi</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($tagihanTidakTetap as $tt)
                    @php
                        $trxMatch = $transaksi->where('tagihan_id', $tt->id);
                        $statusTrx = $trxMatch->first();
                        $statusTransaksi = $statusTrx
                            ? ($statusTrx->status === 'settlement'
                                ? 'lunas'
                                : ($statusTrx->status_menunggak
                                    ? 'menunggak'
                                    : 'belum bayar'))
                            : 'belum bayar';
                    @endphp
                    @if (!$status || strtolower($statusTransaksi) === strtolower($status))
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $tt->NIK ?? '??' }}</td>
                            <td>{{ $tt->warga->pengguna->nama ?? '??' }}</td>
                            <td>Rp{{ number_format($tt->tarif ?? 0, 0, ',', '.') }}</td>
                            <td>{{ $tt->volume ?? '??' }}</td>
                            <td>Rp{{ number_format($tt->total_tagihan ?? 0, 0, ',', '.') }}</td>
                            <td>{{ $tt->tanggal_tagihan ?? '??' }}</td>
                            <td>{{ ucfirst($tt->status ?? '??') }}</td>
                            <td>{{ ucfirst($statusTransaksi) }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @else
        <p>Tagihan Tidak Tetap: Tidak ada data yang sesuai.</p>
    @endif
</body>
</html>
