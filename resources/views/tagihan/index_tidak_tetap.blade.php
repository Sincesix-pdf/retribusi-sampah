<x-layout>
    <div class="content-container">
        <h1 class="mb-4">Daftar Tagihan Tidak Tetap</h1>

        <!-- Filter Form -->
        <form action="{{ route('tagihan.index.tidak_tetap') }}" method="GET" class="mb-3">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="tanggal_tagihan" class="form-label">Pilih Tanggal:</label>
                    <input type="date" name="tanggal_tagihan" id="tanggal_tagihan" class="form-control"
                        value="{{ request('tanggal_tagihan') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
                    <a href="{{ route('tagihan.index.tidak_tetap') }}" class="btn btn-secondary"><i
                            class="fas fa-sync"></i> Reset</a>
                </div>
            </div>
        </form>

        <a class="btn btn-success mb-3" href="{{ route('tagihan.create.tidak_tetap') }}">
            <i class="fas fa-plus"></i> Tambah Tagihan Tidak Tetap
        </a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Tabel Data -->
        <div class="table-responsive">
            <table id="ViewTable" class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Tarif</th>
                        <th>Volume</th>
                        <th>Total Tagihan</th>
                        <th>Tanggal Tagihan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tagihan as $t)
                        <tr>
                            <td>{{ $t->NIK }}</td>
                            <td>{{ $t->warga->pengguna->nama }}</td>
                            <td>Rp{{ number_format($t->tarif) }}</td>
                            <td>{{ $t->volume }}</td>
                            <td>Rp{{ number_format($t->total_tagihan) }}</td>
                            <td>{{ $t->tanggal_tagihan }}</td>
                            <td class="text-center">
                                <a href="{{ route('tagihan.edit', $t->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('tagihan.destroy', $t->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Yakin ingin menghapus?');">
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