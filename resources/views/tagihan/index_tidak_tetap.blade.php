<x-layout>
    <div class="content-container pb-3">
        <div class="card custom-card">
            <div class="card-header custom-card-header">
                <h5 class="mb-0">Daftar Tagihan Tidak Tetap</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Filter Form -->
                <form action="{{ route('tagihan.index.tidak_tetap') }}" method="GET" class="mb-3">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label for="tanggal_tagihan" class="form-label">Pilih Tanggal:</label>
                            <input type="date" name="tanggal_tagihan" id="tanggal_tagihan" class="form-control">
                        </div>
                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
                            <a href="{{ route('tagihan.index.tidak_tetap') }}" class="btn btn-secondary"><i
                                    class="fas fa-sync"></i> Reset</a>
                        </div>
                    </div>
                </form>
                <div class="d-inline-block me-3">
                    <a class="btn btn-success mb-3" href="{{ route('tagihan.create.tidak_tetap') }}">
                        <i class="fas fa-plus"></i> Tambah Tagihan Tidak Tetap
                    </a>
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
                            <div class="card-body p-2">
                                <div class="table-responsive custom-table-container">
                                    <table id="tabel-diajukan"
                                        class="table table-hover table-striped table-bordered table w-100">
                                        <thead class="table-warning">
                                            <tr>
                                                <th>No</th>
                                                <th>NIK</th>
                                                <th>Nama</th>
                                                <th>Tarif</th>
                                                <th>Volume</th>
                                                <th>Total Tagihan</th>
                                                <th>Tanggal Tagihan</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($tagihanDiajukan as $t)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $t->NIK }}</td>
                                                    <td>{{ $t->warga->pengguna->nama }}</td>
                                                    <td>Rp{{ number_format($t->tarif) }}</td>
                                                    <td>{{ $t->volume }}</td>
                                                    <td>{{ $t->total_tagihan }}</td>
                                                    <td>{{ $t->tanggal_tagihan }}</td>
                                                    <td><span class="badge bg-warning">Diajukan</span></td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center">Tidak ada tagihan diajukan.</td>
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
                            <div class="card-body p-2">
                                <div class="table-responsive custom-table-container">
                                    <table id="tabel-disetujui"
                                        class="table table-hover table-striped table-bordered table w-100">
                                        <thead class="table-success sticky-top">
                                            <tr>
                                                <th>No</th>
                                                <th>NIK</th>
                                                <th>Nama</th>
                                                <th>Tarif</th>
                                                <th>Volume</th>
                                                <th>Tanggal Tagihan</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($tagihanDisetujui as $t)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $t->NIK }}</td>
                                                    <td>{{ $t->warga->pengguna->nama }}</td>
                                                    <td>Rp{{ number_format($t->tarif) }}</td>
                                                    <td>{{ $t->volume }}</td>
                                                    <td>{{ $t->tanggal_tagihan }}</td>
                                                    <td><span class="badge bg-success">Disetujui</span></td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center">Tidak ada tagihan disetujui.</td>
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