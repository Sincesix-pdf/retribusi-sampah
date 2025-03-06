<x-layout>
    <div class="content-container">
        <h1 class="mb-4">Daftar Tagihan Tetap</h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <!-- Filter Form -->
        <form action="{{ route('tagihan.index.tetap') }}" method="GET" class="mb-3">
            <div class="row g-3">
                <div class="col-md-4">
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
                    <a href="{{ route('tagihan.index.tetap') }}" class="btn btn-secondary"><i class="fas fa-sync"></i>
                        Reset</a>
                </div>
            </div>
        </form>

        <!-- Button Generate Tagihan -->
        <div class="d-inline-block me-3">
            <form action="{{ route('tagihan.generate.tetap') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-warning mb-3">
                    <i class="fas fa-file-invoice-dollar"></i> Buat Tagihan Bulan Ini
                </button>
            </form>
        </div>

        

        <!-- Card untuk Draft Tagihan Diajukan -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                <h4 class="card-title mb-0">Draft Tagihan Diajukan</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-striped table-hover table-bordered mb-0">
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

        <!-- Card untuk Draft Tagihan Disetujui -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h4 class="card-title mb-0">Draft Tagihan Disetujui</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-striped table-hover table-bordered mb-0">
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
</x-layout>