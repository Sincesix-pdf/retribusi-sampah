<x-layout>
    <div class="content-container pb-3">
        <div class="card custom-card">
            <div class="card-header custom-card-header">
                <h5 class="mb-0">Kelola Transaksi & Laporan Keuangan</h5>
            </div>
            <div class="card-body">
                {{-- Form Filter Bulan, Tahun, Status --}}
                <form action="{{ route('transaksi.index') }}" method="GET" class="mb-3">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label for="bulan" class="form-label">Pilih Bulan:</label>
                            <select name="bulan" id="bulan" class="form-select">
                                <option value="">Semua Bulan</option>
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
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
                        <div class="col-md-2">
                            <label for="status" class="form-label">Status Pembayaran:</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">Semua</option>
                                <option value="settlement" {{ request('status') == 'settlement' ? 'selected' : '' }}>Lunas
                                </option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Belum Bayar
                                </option>
                                <option value="menunggak" {{ request('status') == 'menunggak' ? 'selected' : '' }}>
                                    Menunggak</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-file-invoice-dollar"></i> Buat Laporan
                            </button>
                            <a href="{{ route('transaksi.cetak', ['bulan' => request('bulan'), 'tahun' => request('tahun'), 'status' => request('status')]) }}"
                                target="_blank" class="btn btn-danger">
                                <i class="fas fa-file-pdf"></i> Cetak PDF
                            </a>
                        </div>
                    </div>
                </form>

                {{-- Statistik Pembayaran --}}
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-white bg-success bg-gradient">
                            <div class="card-body">
                                <h5 class="card-title">Sudah Bayar</h5>
                                <h2>{{ $sudahBayar }} Warga</h2>
                                <p class="mb-0">Status: Lunas</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-warning bg-gradient">
                            <div class="card-body">
                                <h5 class="card-title">Belum Bayar</h5>
                                <h2>{{ $belumBayar }} Warga</h2>
                                <p class="mb-0">Status: Belum Bayar</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-primary bg-gradient">
                            <div class="card-body">
                                <h5 class="card-title">Total Pendapatan</h5>
                                <h2>Rp{{ number_format($totalPembayaran, 0, ',', '.') }}</h2>
                                <p class="mb-0">Dari {{ $totalTransaksi }} Transaksi</p>
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
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaksi as $key => $t)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $t->order_id }}</td>
                                        <td>{{ date('F', mktime(0, 0, 0, $t->tagihan->bulan, 1)) }} {{ $t->tagihan->tahun }}
                                        </td>
                                        <td>{{ $t->tagihan->warga->NIK ?? '-' }}</td>
                                        <td>{{ $t->tagihan->warga->pengguna->nama ?? '-' }}</td>
                                        <td>Rp{{ number_format($t->amount, 0, ',', '.') }}</td>
                                        <td>
                                            @if ($t->status == 'settlement')
                                                <span class="badge bg-success">Lunas</span>
                                            @elseif ($t->status_menunggak)
                                                <span class="badge bg-danger">Menunggak</span>
                                            @else
                                                <span class="badge bg-warning">Belum Bayar</span>
                                            @endif
                                        </td>

                                        <td>{{ $t->updated_at->format('d-m-Y H:i') }}</td>
                                        <td>
                                            @if ($t->status == 'pending')
                                                <form action="{{ route('transaksi.sendReminder', $t->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-bell"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layout>