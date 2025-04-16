<x-layout>
    <div class="content-container pb-3">
        <div class="card custom-card">
            <div class="card-header custom-card-header">
                <h5 class="mb-0">Grafik Persebaran Warga</h5>
            </div>
            <div class="card-body">
                <!-- Tabs -->
                <ul class="nav nav-tabs custom-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('kepala_dinas.grafikpendapatan') }}">Grafik Pendapatan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('kepala_dinas.grafikpersebaran') }}">Grafik
                            Persebaran</a>
                    </li>
                </ul>

                <!-- Filter Kecamatan -->
                <form method="GET" action="{{ route('kepala_dinas.grafikpersebaran') }}" class="row g-3 mt-3 mb-4">
                    <div class="col-md-6">
                        <label for="kecamatan" class="form-label">Pilih Kecamatan</label>
                        <select name="kecamatan" id="kecamatan" class="form-select">
                            <option value="">-- Semua Kecamatan --</option>
                            @foreach ($daftarKecamatan as $kec)
                                <option value="{{ $kec->id }}" {{ $kecamatanId == $kec->id ? 'selected' : '' }}>
                                    {{ $kec->nama }}
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

                <!-- Chart -->
                @if ($kelurahans && $kelurahans->count())
                    <h6 class="fw-bold">Kecamatan: {{ $namaKecamatan }}</h6>
                    <div class="chart-container w-100" style="position: relative;">
                        <canvas id="chartKelurahan" style="width: 100%; height: 520px;"></canvas>
                    </div>
                @elseif ($kecamatanId)
                    <div class="alert alert-warning mt-3">Tidak ada data warga di kecamatan ini.</div>
                @else
                    <div class="alert alert-warning mt-3">Tidak ada data kelurahan untuk kecamatan yang dipilih.</div>
                @endif

            </div>
        </div>
    </div>

    @if ($kelurahans && $kelurahans->count())
        <script>
            window.kelurahanLabels = {!! json_encode($kelurahans->pluck('nama')->toArray()) !!};
            window.kelurahanData = {!! json_encode($kelurahans->pluck('warga_count')->toArray()) !!};
        </script>
        <script src="{{ asset('js/chart.js') }}"></script>
    @endif
</x-layout>