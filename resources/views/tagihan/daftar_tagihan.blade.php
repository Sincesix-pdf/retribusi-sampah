<x-layout>
    <div class="content-container pb-3">
        <div class="card custom-card">
            <div class="card-header custom-card-header">
                <h5 class="mb-0">Daftar Tagihan</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Tabs Navigation -->
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
                        <form action="{{ route('kepala_dinas.tagihan.setujui') }}" method="POST">
                            @csrf
                            <div class="card custom-card">
                                <div class="card-header bg-primary text-white">
                                    <h4 class="mb-0">Tagihan Tetap</h4>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive custom-table-container">
                                        <table class="table table-striped table-hover table-bordered mb-0 custom-table">
                                            <thead class="table-primary sticky-top">
                                                <tr>
                                                    <th><input type="checkbox" id="checkAll" data-target="tetap"></th>
                                                    <th>No</th>
                                                    <th>NIK</th>
                                                    <th>Nama</th>
                                                    <th>Bulan/Tahun</th>
                                                    <th>Jumlah Tagihan</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($tagihanTetap as $t)
                                                    <tr>
                                                        <td><input type="checkbox" name="tagihan_id[]" value="{{ $t->id }}" data-group="tetap"></td>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $t->NIK }}</td>
                                                        <td>{{ $t->warga->pengguna->nama }}</td>
                                                        <td>{{ date('F', mktime(0, 0, 0, $t->bulan, 1)) }} {{ $t->tahun }}</td>
                                                        <td>Rp{{ number_format($t->tarif) }}</td>
                                                        <td><span class="badge bg-warning">{{ ucfirst($t->status) }}</span></td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center">Tidak ada tagihan tetap.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">
                                <i class="fas fa-check"></i> Setujui Tagihan Tetap
                            </button>
                        </form>
                    </div>

                    <!-- Tab: Tagihan Tidak Tetap -->
                    <div class="tab-pane fade" id="tagihan-tidak-tetap" role="tabpanel">
                        <form action="{{ route('kepala_dinas.tagihan.setujui') }}" method="POST">
                            @csrf
                            <div class="card custom-card">
                                <div class="card-header bg-success text-white">
                                    <h4 class="mb-0">Tagihan Tidak Tetap</h4>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive custom-table-container">
                                        <table class="table table-striped table-hover table-bordered mb-0 custom-table">
                                            <thead class="table-success sticky-top">
                                                <tr>
                                                    <th><input type="checkbox" id="checkAll" data-target="tidak-tetap"></th>
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
                                                @forelse ($tagihanTidakTetap as $t)
                                                    <tr>
                                                        <td><input type="checkbox" name="tagihan_id[]" value="{{ $t->id }}" data-group="tidak-tetap"></td>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $t->NIK }}</td>
                                                        <td>{{ $t->warga->pengguna->nama }}</td>
                                                        <td>Rp{{ number_format($t->tarif) }}</td>
                                                        <td>{{ $t->volume }}</td>
                                                        <td>{{ $t->total_tagihan }}</td>
                                                        <td>{{ $t->tanggal_tagihan }}</td>
                                                        <td><span class="badge bg-success">{{ ucfirst($t->status) }}</span></td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="8" class="text-center">Tidak ada tagihan tidak tetap.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success mt-3">
                                <i class="fas fa-check"></i> Setujui Tagihan Tidak Tetap
                            </button>
                        </form>
                    </div>
                </div> <!-- End of Tabs -->
            </div> <!-- End of Card Body -->
        </div> <!-- End of Card -->
    </div>
</x-layout>
