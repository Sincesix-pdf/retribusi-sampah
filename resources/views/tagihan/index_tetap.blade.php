<x-layout>
    <div class="content-container">
        <h1 class="mb-4">Daftar Tagihan Tetap</h1>

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

        <a class="btn btn-success mb-3" href="{{ route('tagihan.create.tetap') }}">
            <i class="fas fa-plus"></i> Tambah Tagihan Tetap
        </a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Tabel Data -->
        <div class="table-responsive">
            <table id="ViewTable" class="table table-striped table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Bulan/Tahun</th>
                        <th>Jumlah Tagihan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tagihan as $t)
                        <tr>
                            <td>{{ $t->NIK }}</td>
                            <td>{{ $t->warga->pengguna->nama }}</td>
                            <td>{{ date('F', mktime(0, 0, 0, $t->bulan, 1))}} {{ $t->tahun }}</td>
                            <td>Rp{{ number_format($t->total_tagihan) }}</td>
                            <td class="text-center">
                                <a href="{{ route('tagihan.edit', $t->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <a href="https://wa.me/?text=Tagihan%20Rp{{ number_format($t->total_tagihan) }}%20untuk%20{{ $t->warga->pengguna->nama }}"
                                    class="btn btn-sm btn-success" target="_blank">
                                    <i class="fab fa-whatsapp"></i>
                                </a>

                                <form action="{{ route('tagihan.destroy', $t->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Yakin ingin menghapus tagihan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layout>