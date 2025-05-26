<x-layout>
    <div class="content-container pb-3">
        <div class="card custom-card">
            <div class="card-header custom-card-header">
                <h5 class="mb-0">Laporan Daftar Tagihan</h5>
            </div>
            <div class="card-body">
                {{-- Form Filter Bulan, Tahun, Status --}}
                <form action="{{ route('laporan.tagihan') }}" method="GET" class="mb-3">
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
                <ul class="nav nav-tabs custom-tabs" id="tagihanTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tagihan-tetap-tab" data-bs-toggle="tab" data-bs-target="#tagihan-tetap" type="button" role="tab">
                            Tagihan Tetap
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tagihan-tidak-tetap-tab" data-bs-toggle="tab" data-bs-target="#tagihan-tidak-tetap" type="button" role="tab">
                            Tagihan Tidak Tetap
                        </button>
                    </li>
                </ul>

                <div class="tab-content mt-3" id="tagihanTabsContent">
                    <!-- Tab: Tagihan Tetap -->
                    <div class="tab-pane fade show active" id="tagihan-tetap" role="tabpanel">
                        <div class="card custom-card">
                            <div class="card-header bg-primary text-white">
                                <h4 class="mb-0">Tagihan Tetap</h4>
                            </div>
                            <div class="card-body p-2">
                                <div class="table-responsive custom-table-container">
                                    <table id="tabel-diajukan" class="table table-striped table-hover table-bordered mb-0 custom-table">
                                        <thead class="table-primary sticky-top">
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
                                        @foreach ($tagihanTetap as $t)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $t->NIK }}</td>
                                            <td>{{ $t->warga->pengguna->nama }}</td>
                                            <td>{{ date('F', mktime(0, 0, 0, $t->bulan, 1)) }} {{ $t->tahun }}</td>
                                            <td>Rp{{ number_format($t->tarif) }}</td>
                                            <td>
                                                @if ($t->status === 'diajukan')
                                                    <span class="badge bg-warning">Diajukan</span>
                                                @elseif ($t->status === 'disetujui')
                                                    <span class="badge bg-success">Disetujui</span>
                                                @endif
                                            </td>
                                            <td>
                                            @php
                                                $trxMatch = $transaksi->where('tagihan_id', $t->id);
                                            @endphp
                                                @php
                                                    $statusDisplayed = false;
                                                @endphp
                                                @foreach ($trxMatch as $trx)
                                                    @if ($trx->status === 'settlement')
                                                        <span class="badge bg-success">Lunas</span>
                                                        @php $statusDisplayed = true; @endphp
                                                        @break
                                                    @elseif ($trx->status_menunggak)
                                                        <span class="badge bg-danger">Menunggak</span>
                                                        @php $statusDisplayed = true; @endphp
                                                        @break
                                                    @elseif ($trx->status === 'pending')
                                                        <span class="badge bg-warning">Belum Bayar</span>
                                                        @php $statusDisplayed = true; @endphp
                                                        @break
                                                    @endif
                                                @endforeach
                                            </td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab: Tagihan Tidak Tetap -->
                    <div class="tab-pane fade" id="tagihan-tidak-tetap" role="tabpanel">
                        <div class="card custom-card">
                            <div class="card-header bg-success text-white">
                                <h4 class="mb-0">Tagihan Tidak Tetap</h4>
                            </div>
                            <div class="card-body p-2">
                                <div class="table-responsive custom-table-container">
                                    <table id="tabel-disetujui" class="table table-striped table-hover table-bordered mb-0 custom-table">
                                        <thead class="table-success">
                                            <tr>
                                                <th>No</th>
                                                <th>NIK</th>
                                                <th>Nama</th>
                                                <th>Tarif</th>
                                                <th>Volume</th>
                                                <th>Total Tagihan</th>
                                                <th>Tanggal Tagihan</th>
                                                <th>Status</th>
                                                <th>Status Transaksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($tagihanTidakTetap as $t)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $t->NIK }}</td>
                                            <td>{{ $t->warga->pengguna->nama }}</td>
                                            <td>Rp{{ number_format($t->tarif) }}</td>
                                            <td>{{ $t->volume }}</td>
                                            <td>{{ $t->total_tagihan }}</td>
                                            <td>{{ $t->tanggal_tagihan }}</td>
                                            <td>
                                            @if ($t->status === 'diajukan')
                                                <span class="badge bg-warning">Diajukan</span>
                                            @elseif ($t->status === 'disetujui')
                                                <span class="badge bg-success">Disetujui</span>
                                            @endif
                                            </td>
                                            <td>
                                            @php
                                                $trxMatch = $transaksi->where('tagihan_id', $t->id);
                                            @endphp

                                            @if ($trxMatch->isEmpty())
                                                <span class="badge bg-secondary">Belum Ada Transaksi</span>
                                            @else
                                            @php
                                                $statusDisplayed = false;
                                            @endphp
                                            @foreach ($trxMatch as $trx)
                                                @if ($trx->status === 'settlement')
                                                    <span class="badge bg-success">Lunas</span>
                                                    @php $statusDisplayed = true; @endphp
                                                    @break
                                                @elseif ($trx->status_menunggak)
                                                    <span class="badge bg-danger">Menunggak</span>
                                                    @php $statusDisplayed = true; @endphp
                                                    @break
                                                @elseif ($trx->status === 'pending')
                                                    <span class="badge bg-warning">Belum Bayar</span>
                                                    @php $statusDisplayed = true; @endphp
                                                    @break
                                                @endif
                                            @endforeach
                                            @if (! $statusDisplayed)
                                                <span class="badge bg-secondary">Belum Ada Transaksi</span>
                                            @endif
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
                </div> <!-- End of Tabs -->
                <div class="mt-3">
                    <!-- Tombol Cetak Laporan dengan Modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCetakLaporan">
                        <i class="fa-solid fa-print"></i> Cetak Laporan
                    </button>
                </div>

                <!-- Modal Filter Cetak Laporan -->
                <div class="modal fade" id="modalCetakLaporan" tabindex="-1" aria-labelledby="modalCetakLaporanLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('laporan.cetak') }}" method="GET" target="_blank" class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalCetakLaporanLabel">Filter Cetak Laporan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="tanggal_mulai" class="form-label">Dari Tanggal</label>
                                    <input type="date" class="form-control" name="tanggal_mulai" id="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="tanggal_selesai" class="form-label">Sampai Tanggal</label>
                                    <input type="date" class="form-control" name="tanggal_selesai" id="tanggal_selesai" value="{{ request('tanggal_selesai') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="bulan" class="form-label">Bulan</label>
                                    <select name="bulan" id="bulan" class="form-select">
                                        <option value="">Semua Bulan</option>
                                        @foreach (range(1, 12) as $m)
                                            <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="tahun" class="form-label">Tahun</label>
                                    <select name="tahun" id="tahun" class="form-select">
                                        @foreach (range(2023, date('Y')) as $y)
                                            <option value="{{ $y }}" {{ request('tahun', date('Y')) == $y ? 'selected' : '' }}>
                                                {{ $y }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status Pembayaran</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="">Semua</option>
                                        <option value="settlement" {{ request('status') == 'settlement' ? 'selected' : '' }}>Lunas</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Belum Bayar</option>
                                        <option value="menunggak" {{ request('status') == 'menunggak' ? 'selected' : '' }}>Menunggak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-print"></i> Cetak</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> <!-- End of Card Body -->
        </div> <!-- End of Card -->
    </div>
</x-layout>
