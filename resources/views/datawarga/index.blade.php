<x-layout>
    <div class="content-container pb-3">
        <div class="card custom-card">
            <div class="card-header custom-card-header">
                <h5 class="mb-0">Halaman Kelola Wajib Retribusi</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <!-- Filter Form -->
                <form action="{{ route('datawarga.index') }}" method="GET" class="mb-3">
                    <div class="row g-3">
                        <!-- Pilih Kecamatan -->
                        <div class="col-md-4">
                            <label for="kecamatan_id" class="form-label">Pilih Kecamatan:</label>
                            <select name="kecamatan_id" id="kecamatan_id" class="form-select">
                                <option value="">Semua Kecamatan</option>
                                @foreach ($kecamatan as $kec)
                                    <option value="{{ $kec->id }}" {{ $kec->id == request('kecamatan_id') ? 'selected' : '' }}>
                                        {{ $kec->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Pilih Kelurahan -->
                        <div class="col-md-4">
                            <label for="kelurahan_id" class="form-label">Pilih Kelurahan:</label>
                            <select name="kelurahan_id" id="kelurahan_id" class="form-select">
                                <option value="">Semua Kelurahan</option>
                            </select>
                        </div>

                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
                            <a href="{{ route('datawarga.index') }}" class="btn btn-secondary"><i
                                    class="fas fa-sync"></i> Reset</a>
                        </div>
                    </div>
                </form>

                <a class="btn btn-success mb-3" href="{{ route('datawarga.create') }}">
                    <i class="fas fa-plus-circle"></i> Tambah WR
                </a>

                <!-- Tabel Data -->
                <div style="overflow-x: auto; width: 100%;" class="table-responsive custom-table-container">
                    <table id="tabel-warga" class="table table-striped table-sm table-hover table-bordered w-100">
                        <thead class="table-primary">
                            <tr>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Alamat</th>
                                <th>Kelurahan</th>
                                <th>Kecamatan</th>
                                <th>No HP</th>
                                <th>Jenis Kelamin</th>
                                <th>Kategori</th>
                                <th>Jenis Retribusi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($warga as $w)
                                <tr>
                                    <td>{{ $w->NIK }}</td>
                                    <td>{{ $w->pengguna->nama }}</td>
                                    <td>{{ $w->pengguna->email }}</td>
                                    <td>{{ $w->pengguna->alamat }}</td>
                                    <td>{{ $w->kelurahan->nama ?? '-' }}</td>
                                    <td>{{ $w->kelurahan->kecamatan->nama ?? '-' }}</td>
                                    <td>{{ $w->pengguna->no_hp }}</td>
                                    <td>{{ $w->pengguna->jenis_kelamin }}</td>
                                    <td>{{ ucwords(str_replace('_', ' ', $w->kategori_retribusi)) }}</td>
                                    <td>{{ ucwords(str_replace('_', ' ', $w->jenis_retribusi)) }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('datawarga.edit', $w->NIK) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('datawarga.destroy', $w->NIK) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?');">
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
                </div> <!-- end tabel -->
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div>
</x-layout>