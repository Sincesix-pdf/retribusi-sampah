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
                        <button class="nav-link active" id="tagihan-tetap-tab" data-bs-toggle="tab"
                            data-bs-target="#tagihan-tetap" type="button" role="tab">
                            Tagihan Tetap
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tagihan-tidak-tetap-tab" data-bs-toggle="tab"
                            data-bs-target="#tagihan-tidak-tetap" type="button" role="tab">
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
                                <div class="card-body p-2">
                                    <div class="table-responsive custom-table-container">
                                        <table id="tabel-warga"
                                            class="table table-striped table-sm table-hover table-bordered w-100">
                                            <thead class="table-primary">
                                                <tr>
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
                                                        <input type="hidden" name="tagihan_id[]" value="{{ $t->id }}">
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $t->NIK }}</td>
                                                        <td>{{ $t->warga->pengguna->nama }}</td>
                                                        <td>{{ date('F', mktime(0, 0, 0, $t->bulan, 1)) }} {{ $t->tahun }}
                                                        </td>
                                                        <td>Rp{{ number_format($t->tarif) }}</td>
                                                        <td><span class="badge bg-warning">{{ ucfirst($t->status) }}</span>
                                                        </td>
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
                            <button type="submit" class="btn btn-primary mt-3" id="setujuiTetap>
                                <i class=" fas fa-check"></i> Setujui Tagihan Tetap
                            </button>
                        </form>
                    </div>

                    <!-- Tab: Tagihan Tidak Tetap -->
                    <div class="tab-pane fade" id="tagihan-tidak-tetap" role="tabpanel">
                        <form action="{{ route('kepala_dinas.tagihan.setujui') }}" method="POST">
                            @csrf
                            <div class="card custom-card">
                                <div class="card-header bg-primary text-white">
                                    <h4 class="mb-0">Tagihan Tidak Tetap</h4>
                                </div>
                                <div class="card-body p-2">
                                    <div class="table-responsive custom-table-container">
                                        <table class="table table-striped table-hover table-bordered mb-0 custom-table">
                                            <thead class="table-success">
                                                <tr>
                                                    <th><input type="checkbox" id="checkAll" data-target="tidak-tetap">
                                                    </th>
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
                                                        <td>
                                                            <input type="checkbox" name="tagihan_id[]" value="{{ $t->id }}"
                                                                data-group="tidak-tetap">
                                                        </td>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $t->NIK }}</td>
                                                        <td>{{ $t->warga->pengguna->nama }}</td>
                                                        <td>Rp{{ number_format($t->tarif) }}</td>
                                                        <td>{{ $t->volume }}</td>
                                                        <td>Rp{{ number_format($t->total_tagihan) }}</td>
                                                        <td>{{ $t->tanggal_tagihan }}</td>
                                                        <td>
                                                            @if ($t->status == 'ditolak')
                                                                <span
                                                                    class="badge bg-danger">{{ ucfirst($t->status) }}</span><br>
                                                                <small><i>Alasan: {{ $t->keterangan }}</i></small>
                                                            @else
                                                                <span class="badge bg-warning">{{ ucfirst($t->status) }}</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="9" class="text-center">Tidak ada tagihan tidak tetap.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Setujui -->
                            <button type="submit" class="btn btn-success mt-3" id="setujuiTidakTetap">
                                <i class="fas fa-check"></i> Setujui Tagihan
                            </button>
                        </form>
                        <!-- Tombol Tolak Tagihan -->
                        <button type="button" class="btn btn-danger mt-2" id="tolakTidakTetapBtn"
                            data-bs-toggle="modal" data-bs-target="#modalTolakTidakTetap">
                            <i class="fas fa-times"></i> Tolak Tagihan
                        </button>
                    </div>

                    <!-- Modal Tolak Tagihan -->
                    <div class="modal fade" id="modalTolakTidakTetap" tabindex="-1" aria-labelledby="modalTolakLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="{{ route('kepala_dinas.tagihan.tolak') }}" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title" id="modalTolakLabel">Alasan Penolakan Tagihan Tidak
                                            Tetap</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="tagihan_ids" id="tagihanIdsTolak">
                                        <div class="mb-3">
                                            <label for="alasan" class="form-label">Alasan Penolakan</label>
                                            <textarea name="alasan" id="alasan" class="form-control" rows="3"
                                                required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-danger">Tolak Tagihan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> <!-- End of Tabs -->
        </div> <!-- End of Card Body -->
    </div> <!-- End of Card -->
    </div>
</x-layout>