<x-layout>
    <div class="content-container pb-3">
        <div class="card custom-card">
            <div class="card-header custom-card-header">
                <h5 class="mb-0">Grafik Laporan</h5>
            </div>
            <div class="card-body">
                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs custom-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ request()->routeIs('kepala_dinas.grafikpendapatan') ? 'active' : '' }}"
                            href="{{ route('kepala_dinas.grafikpendapatan') }}">Grafik Pendapatan</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ request()->routeIs('kepala_dinas.grafikpersebaran') ? 'active' : '' }}"
                            href="{{ route('kepala_dinas.grafikpersebaran') }}">Grafik Persebaran</a>
                    </li>
                </ul>


                <!-- Filter Bulan dan Tahun -->
                <form method="GET" action="{{ route('kepala_dinas.grafikpendapatan') }}" class="row g-3 mt-3 mb-4">
                    <div class="col-md-4">
                        <label for="bulan" class="form-label">Pilih Bulan</label>
                        <select name="bulan" id="bulan" class="form-select">
                            <option value="">Semua Bulan</option>
                            @foreach(range(1, 12) as $b)
                                <option value="{{ $b }}" {{ request('bulan') == $b ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="tahun" class="form-label">Pilih Tahun:</label>
                        <select name="tahun" id="tahun" class="form-select">
                            @foreach (range(2024, date('Y')) as $y)
                                <option value="{{ $y }}" {{ request('tahun', date('Y')) == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-md-4 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fa-solid fa-eye"></i> Tampilkan
                        </button>
                    </div>
                </form>

                <div class="tab-content mt-2">
                    <div class="row">
                        <!-- Chart 1: Pendapatan per Bulan -->
                        <div class="col-md-6 mb-4">
                            <div class="card custom-card h-100">
                                <div class="card-header bg-white text-secondary">
                                    <h5 class="mb-0">Pendapatan per Bulan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container" style="width: 100%; height: 100%;">
                                        <canvas id="chartPendapatan"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chart 2: Pendapatan per Jenis -->
                        <div class="col-md-6 mb-4">
                            <div class="card custom-card h-100">
                                <div class="card-header bg-white text-secondary">
                                    <h5 class="mb-0">Pendapatan per Jenis Retribusi</h5>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container" style="width: 100%; height: 100%;">
                                        <canvas id="chartJenisRetribusi"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chart 3: Jumlah Warga Membayar -->
                        <div class="col-md-6 mb-4">
                            <div class="card custom-card h-100">
                                <div class="card-header bg-white text-secondary">
                                    <h5 class="mb-0">Jumlah Warga Membayar per Bulan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container" style="position: relative; height: 320px;">
                                        <canvas id="chartWargaBayar"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chart 4: Total Tagihan per Kategori Warga -->
                        <div class="col-md-6 mb-4">
                            <div class="card custom-card h-100">
                                <div class="card-header bg-white text-secondary">
                                    <h5 class="mb-0">Kategori Warga Membayar</h5>
                                </div>
                                <div class="card-body table-responsive">
                                    <table class="table table-bordered align-middle text-center">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Kategori</th>
                                                <th>Jumlah Membayar</th>
                                                <th>Total Bayar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($perKategori as $kategori => $data)
                                                <tr>
                                                    <td>{{ ucfirst($kategori) }}</td>
                                                    <td>{{ $data['jumlah_bayar'] }}</td>
                                                    <td>Rp{{ number_format($data['total_bayar'], 0, ',', '.') }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3">Tidak ada data</td>
                                                </tr>
                                            @endforelse
                                        </tbody>

                                        <tfoot>
                                            <tr class="table-light fw-bold">
                                                <td colspan="2">Total Semua</td>
                                                <td>Rp{{ number_format($totalSemuaBayar, 0, ',', '.') }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Script untuk passing data -->
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    window.labelsBulan = {!! json_encode($perBulan->keys()) !!};
                    window.dataBulan = {!! json_encode($perBulan->values()) !!};
                    window.labelsJenis = {!! json_encode($perJenis->keys()) !!};
                    window.dataJenis = {!! json_encode($perJenis->values()) !!};
                    window.labelsWarga = {!! json_encode($perWargaBayar->keys()) !!};
                    window.dataWarga = {!! json_encode($perWargaBayar->values()) !!};
                </script>
                <script src="{{ asset('js/chart.js') }}"></script>
            </div>
        </div>
    </div>
</x-layout>