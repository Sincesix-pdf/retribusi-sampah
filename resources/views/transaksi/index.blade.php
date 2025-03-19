<x-layout>
    <div class="content-container pb-3">
        <div class="card custom-card">
            <div class="card-header custom-card-header">
                <h5 class="mb-0">Kelola Transaksi</h5>
            </div>
            <div class="card-body">

                {{-- Form Filter Bulan --}}
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
                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
                            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
                                <i class="fas fa-sync"></i> Reset
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
                        @if ($transaksi->isEmpty())
                            <div class="alert alert-warning">Belum ada transaksi.</div>
                        @else
                            <div class="table-responsive">
                                <table id="ViewTable" class="table table-striped table-bordered">
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transaksi as $key => $t)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $t->order_id }}</td>
                                                <td>{{ date('F', mktime(0, 0, 0, $t->tagihan->bulan, 1)) }}
                                                    {{ $t->tagihan->tahun }}
                                                </td>
                                                <td>{{ $t->tagihan->warga->NIK ?? '-' }}</td>
                                                <td>{{ $t->tagihan->warga->pengguna->nama ?? '-' }}</td>
                                                <td>Rp{{ number_format($t->amount, 0, ',', '.') }}</td>
                                                <td>
                                                    @if ($t->status == 'settlement')
                                                        <span class="badge bg-success">Lunas</span>
                                                    @elseif ($t->status == 'pending')
                                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                                    @else
                                                        <span class="badge bg-danger">Gagal</span>
                                                    @endif
                                                </td>
                                                <td>{{ $t->updated_at->format('d-m-Y H:i') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layout>
