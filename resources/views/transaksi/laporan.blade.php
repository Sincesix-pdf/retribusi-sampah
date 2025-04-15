<x-layout>
    <div class="content-container pb-3">
        <div class="card custom-card">
            <div class="card-header custom-card-header">
                <h5 class="mb-0">Laporan Keuangan</h5>
            </div>
            <div class="card-body">
                {{-- Form Filter --}}
                <form method="GET" action="{{ route('transaksi.laporan') }}" class="mb-3">
                    <div class="row g-3">
                        {{-- Dropdown Bulan --}}
                        <div class="col-md-2">
                            <label for="bulan" class="form-label">Pilih Bulan:</label>
                            <select name="bulan" id="bulan" class="form-select">
                                <option value="">Semua Bulan</option> {{-- Tambahkan opsi "Semua Bulan" --}}
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ request('bulan', now()->month) == $m ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Dropdown Tahun --}}
                        <div class="col-md-2">
                            <label for="tahun" class="form-label">Pilih Tahun:</label>
                            <select name="tahun" id="tahun" class="form-select">
                                @foreach (range(2023, date('Y')) as $y)
                                    <option value="{{ $y }}" {{ request('tahun', date('Y')) == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Baris baru untuk tombol --}}
                    <div class="row mt-3">
                        <div class="col-md-4 d-flex gap-2">
                            {{-- Tombol Buat Laporan Keuangan --}}
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-file-invoice-dollar"></i> Buat Laporan
                            </button>

                            {{-- Tombol Cetak PDF --}}
                            <a href="{{ route('transaksi.cetak', ['bulan' => request('bulan', now()->month), 'tahun' => request('tahun', now()->year)]) }}"
                                target="_blank" class="btn btn-danger">
                                <i class="fas fa-file-pdf"></i> Cetak PDF
                            </a>
                        </div>
                    </div>
                </form>

                {{-- Ringkasan Keuangan --}}
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary bg-gradient">
                            <div class="card-body">
                                <h5>Total Pemasukan</h5>
                                <p class="h4">Rp {{ number_format($total_pembayaran, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabel Transaksi --}}
                <div class="card">
                    <div class="card-body">
                        <table id="ViewTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
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
                                @foreach ($transaksi as $key => $t)
                                    @if ($t->status == 'settlement')
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
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
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layout>