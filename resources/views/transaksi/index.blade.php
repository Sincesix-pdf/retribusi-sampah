<x-layout>
    <div class="content-container pb-3">
        <div class="card custom-card">
            <div class="card-header custom-card-header">
                <h5 class="mb-0">Laporan Daftar Transaksi</h5>
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
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Statistik Pembayaran --}}
                <div class="row mb-4">
                    <div class="col-12 col-sm-6 col-md-3 mb-3">
                        <div class="card text-white shadow-sm bg-primary">
                            <div class="card-body">
                                <h5 class="card-title">Jumlah Tagihan</h5>
                                <h2>{{ $totalTransaksi }} Tagihan</h2>
                                <p class="mb-0">Total tagihan diterbitkan</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 mb-3">
                        <div class="card text-white card-custom shadow-sm" style="background-color: #4cae4f;">
                            <div class="card-body">
                                <h5 class="card-title">Sudah Bayar</h5>
                                <h2>{{ $sudahBayar }} Warga</h2>
                                <p class="mb-0">Status: Lunas</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 mb-3">
                        <div class="card text-white card-custom shadow-sm" style="background-color: #F5A623;">
                            <div class="card-body">
                                <h5 class="card-title">Belum Bayar</h5>
                                <h2>{{ $belumBayar }} Warga</h2>
                                <p class="mb-0">Status: Belum Bayar</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 mb-3">
                        <div class="card text-white card-custom shadow-sm bg-danger">
                            <div class="card-body">
                                <h5 class="card-title">Menunggak</h5>
                                <h2>{{ $menunggak }} Warga</h2>
                                <p class="mb-0">Status: Menunggak</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 mb-3">
                        <div class="card text-white card-custom shadow-sm" style="background-color: #8bc34b;">
                            <div class="card-body">
                                <h5 class="card-title">Total Pendapatan</h5>
                                <h2>Rp{{ number_format($totalPembayaran, 0, ',', '.') }}</h2>
                                <p class="mb-0">Dari {{ $sudahBayar }} Pembayaran</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tombol Kirim Pengingat Tunggakan dan Belum Bayar --}}
                <div class="d-flex justify-content-end gap-3 mb-3">
                    <form action="{{ route('transaksi.sendReminderMenunggak') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-bell"></i> Kirim Pengingat Tunggakan
                        </button>
                    </form>
                    <form action="{{ route('transaksi.sendReminderPending') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-clock"></i> Kirim Pengingat Belum Bayar
                        </button>
                    </form>
                </div>

                {{-- Tab Navigasi Transaksi --}}
                <ul class="nav nav-tabs custom-tabs" id="transaksiTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="transaksi-tetap-tab" data-bs-toggle="tab"
                            data-bs-target="#transaksi-tetap" type="button" role="tab">
                            Transaksi Tetap
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="transaksi-tidak-tetap-tab" data-bs-toggle="tab"
                            data-bs-target="#transaksi-tidak-tetap" type="button" role="tab">
                            Transaksi Tidak Tetap
                        </button>
                    </li>
                </ul>

                <div class="tab-content mt-3" id="transaksiTabsContent">
                    <!-- Transaksi  Tetap -->
                    <div class="tab-pane fade show active" id="transaksi-tetap" role="tabpanel">
                        <div class="card custom-card">
                            <div class="card-header bg-primary text-white">
                                <h4 class="mb-0">Transaksi Tetap</h4>
                            </div>
                            <div class="card-body p-2">
                                <div class="table-responsive custom-table-container">
                                    <table id="tabel-diajukan"
                                        class="table table-striped table-hover table-bordered mb-0 custom-table">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>No</th>
                                                <th>Invoice</th>
                                                <th>Periode</th>
                                                <th>NIK</th>
                                                <th>Nama</th>
                                                <th>Jumlah</th>
                                                <th>Status</th>
                                                <th>Tanggal Bayar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($transaksiTetap as $key => $t)
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
                                                        @elseif ($t->status_menunggak)
                                                            <span class="badge bg-danger">
                                                                Menunggak
                                                                @if ($t->akumulasi_tunggakan > 1)
                                                                    ({{ $t->akumulasi_tunggakan }} bulan)
                                                                @endif
                                                            </span>
                                                        @else
                                                            <span class="badge bg-warning">Belum Bayar</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($t->status == 'settlement')
                                                            {{ $t->updated_at->format('d-m-Y H:i') }}
                                                        @else
                                                            -
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

                    <!-- Transaksi Tidak Tetap -->
                    <div class="tab-pane fade" id="transaksi-tidak-tetap" role="tabpanel">
                        <div class="card custom-card">
                            <div class="card-header bg-success text-white">
                                <h4 class="mb-0">Transaksi Tidak Tetap</h4>
                            </div>
                            <div class="card-body p-2">
                                <div class="table-responsive custom-table-container">
                                    <table id="tabel-disetujui"
                                        class="table table-striped table-hover table-bordered mb-0 custom-table">
                                        <thead class="table-success">
                                            <tr>
                                                <th>No</th>
                                                <th>Invoice</th>
                                                <th>Tanggal Tagihan</th>
                                                <th>NIK</th>
                                                <th>Nama</th>
                                                <th>Volume</th>
                                                <th>Jumlah</th>
                                                <th>Status</th>
                                                <th>Tanggal Bayar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($transaksiTidakTetap as $key => $t)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $t->order_id }}</td>
                                                    <td>{{ $t->tagihan->tanggal_tagihan }}</td>
                                                    <td>{{ $t->tagihan->warga->NIK ?? '-' }}</td>
                                                    <td>{{ $t->tagihan->warga->pengguna->nama ?? '-' }}</td>
                                                    <td>{{$t->tagihan->volume}}</td>
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
                                                    <td>
                                                        @if ($t->status == 'settlement')
                                                            {{ $t->updated_at->format('d-m-Y H:i') }}
                                                        @else
                                                            -
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
                <div class="mt-3">
                    <!-- Tombol Cetak Laporan dengan Modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#modalCetakLaporan">
                        <i class="fa-solid fa-print"></i> Cetak Laporan
                    </button>
                </div>

                <!-- Modal Filter Cetak Laporan -->
                <div class="modal fade" id="modalCetakLaporan" tabindex="-1" aria-labelledby="modalCetakLaporanLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('transaksi.cetak') }}" method="GET" target="_blank"
                            class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalCetakLaporanLabel">Filter Cetak Laporan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <fieldset class="mb-3 pb-2 border rounded px-3">
                                    <legend class="float-none w-auto px-2" style="">
                                        Rentang Pembayaran
                                    </legend>
                                    <div class="row">
                                        <div class="col-6 mb-2">
                                            <input type="date" class="form-control" name="tanggal_mulai"
                                                placeholder="Dari Tanggal">
                                        </div>
                                        <div class="col-6 mb-2">
                                            <input type="date" class="form-control" name="tanggal_selesai"
                                                placeholder="Sampai Tanggal">
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="mb-3 pb-2 border rounded px-3">
                                    <legend class="float-none w-auto px-2" style="">
                                        Periode Pembayaran
                                    </legend>
                                    <div class="row">
                                        <div class="col-6 mb-2">
                                            <select name="bulan" class="form-select">
                                                <option value="">Semua Bulan</option>
                                                @foreach (range(1, 12) as $m)
                                                    <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <select name="tahun" class="form-select">
                                                @foreach (range(2023, date('Y')) as $y)
                                                    <option value="{{ $y }}" {{ request('tahun', date('Y')) == $y ? 'selected' : '' }}>
                                                        {{ $y }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status Pembayaran</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="">Semua</option>
                                        <option value="settlement">Lunas</option>
                                        <option value="pending">Belum Bayar</option>
                                        <option value="menunggak">Menunggak</option>
                                    </select>
                                </div>
                                <fieldset class="mb-3 pb-2 border rounded px-3">
                                    <legend class="float-none w-auto px-2">
                                        Wilayah
                                    </legend>
                                    <div class="mb-3">
                                        <label for="kecamatan_id" class="form-label">Kecamatan</label>
                                        <select name="kecamatan_id" id="kecamatan_id" class="form-control">
                                            <option value="">Pilih Kecamatan</option>
                                            @foreach($kecamatan as $kec)
                                                <option value="{{ $kec->id }}" {{ old('kecamatan_id') == $kec->id ? 'selected' : '' }}>
                                                    {{ $kec->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="kelurahan_id" class="form-label">Kelurahan</label>
                                        <select name="kelurahan_id" id="kelurahan_id" class="form-select">
                                            <option value="">Pilih Kelurahan</option>
                                        </select>
                                        @error('kelurahan_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </fieldset>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-print"></i>
                                        Cetak</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</x-layout>