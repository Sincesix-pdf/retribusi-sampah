<x-layout>
    <div class="content-container pb-3">
        <div class="card custom-card">
            <div class="card-header custom-card-header">
                <h5 class="mb-0">Daftar Tagihan Tetap</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Filter Form -->
                <form action="{{ route('tagihan.index.tetap') }}" method="GET" class="mb-3">
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
                            <a href="{{ route('tagihan.index.tetap') }}" class="btn btn-secondary"><i
                                    class="fas fa-sync"></i> Reset</a>
                        </div>
                    </div>
                </form>

                <!-- Button Generate Tagihan -->
                <div class="d-inline-block mb-3">
                    <label class="form-label">Buat tagihan bedasarkan bulan dan tahun</label>
                    <form action="{{ route('tagihan.generate.tetap') }}" method="POST">
                        @csrf
                        <div class="row g-2">
                            <div class="col-auto">
                                <select name="bulan" class="form-select">
                                    @foreach (range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ now()->month == $m ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <select name="tahun" class="form-select">
                                    @foreach (range(now()->year - 5, now()->year + 1) as $y)
                                        <option value="{{ $y }}" {{ now()->year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-file-invoice-dollar"></i> Buat Tagihan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>


                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs custom-tabs" id="tagihanTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="diajukan-tab" data-bs-toggle="tab"
                            data-bs-target="#diajukan" type="button" role="tab">Tagihan Diajukan</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="disetujui-tab" data-bs-toggle="tab" data-bs-target="#disetujui"
                            type="button" role="tab">Tagihan Disetujui</button>
                    </li>
                </ul>

                <div class="tab-content mt-3" id="tagihanTabsContent">
                    <!-- Tab: Diajukan -->
                    <div class="tab-pane fade show active" id="diajukan" role="tabpanel">
                        <div class="card custom-card">
                            <div class="card-header bg-warning text-white">
                                <h4 class="mb-0">Draft Tagihan Diajukan</h4>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive custom-table-container">
                                    <table class="table table-striped table-hover table-bordered mb-0 custom-table">
                                        <thead class="table-warning sticky-top">
                                            <tr>
                                                <th>No</th>
                                                <th>NIK</th>
                                                <th>Nama</th>
                                                <th>Bulan/Tahun</th>
                                                <th>Jumlah Tagihan</th>
                                                <th>Jenis Retribusi</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($tagihanDiajukan as $t)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $t->NIK }}</td>
                                                    <td>{{ $t->warga->pengguna->nama }}</td>
                                                    <td>{{ date('F', mktime(0, 0, 0, $t->bulan, 1)) }} {{ $t->tahun }}</td>
                                                    <td class="text-end">{{ number_format($t->tarif) }}</td>
                                                    <td>{{ $t->warga->jenisLayanan->nama_paket }}</td>
                                                    <td><span class="badge bg-warning">Diajukan</span></td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">Tidak ada tagihan diajukan.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab: Disetujui -->
                    <div class="tab-pane fade" id="disetujui" role="tabpanel">
                        <div class="card custom-card">
                            <div class="card-header bg-success text-white">
                                <h4 class="mb-0">Draft Tagihan Disetujui</h4>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive custom-table-container">
                                    <table class="table table-striped table-hover table-bordered mb-0 custom-table">
                                        <thead class="table-success sticky-top">
                                            <tr>
                                                <th>No</th>
                                                <th>NIK</th>
                                                <th>Nama</th>
                                                <th>Bulan/Tahun</th>
                                                <th>Jumlah Tagihan</th>
                                                <th>Jenis Retribusi</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($tagihanDisetujui as $t)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $t->NIK }}</td>
                                                    <td>{{ $t->warga->pengguna->nama }}</td>
                                                    <td>{{ date('F', mktime(0, 0, 0, $t->bulan, 1)) }} {{ $t->tahun }}</td>
                                                    <td class="text-end">{{ number_format($t->tarif) }}</td>
                                                    <td>{{ $t->warga->jenisLayanan->nama_paket }}</td>
                                                    <td><span class="badge bg-success">Disetujui</span></td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">Tidak ada tagihan disetujui.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- End of Tabs -->
            </div> <!-- End of Card Body -->
        </div> <!-- End of Card -->
    </div>
</x-layout>