<x-layout>
    <div class="content-container">
        <h1>Halaman Kelola WR</h1>
        <div class="">
            <h2>Daftar Warga</h2>

            <!-- Filter Form -->
            <form action="{{ route('datawarga.index') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <label for="kecamatan_id">Pilih Kecamatan:</label>
                        <select name="kecamatan_id" id="kecamatan_id" class="form-control">
                            <option value="">Semua Kecamatan</option>
                            @foreach ($kecamatan as $kec)
                                <option value="{{ $kec->id }}" {{ $kec->id == request('kecamatan_id') ? 'selected' : '' }}>
                                    {{ $kec->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="kelurahan_id">Pilih Kelurahan:</label>
                        <select name="kelurahan_id" id="kelurahan_id" class="form-control">
                            <option value="">Semua Kelurahan</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('datawarga.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>

            <a class="btn btn-success mb-3" href="{{ route('datawarga.create') }}">Tambah Warga</a>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table id="wargaTable" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Alamat</th>
                        <th>Kelurahan</th>
                        <th>Kecamatan</th>
                        <th>No HP</th>
                        <th>Jenis Kelamin</th>
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
                            <td>{{ $w->jenis_retribusi }}</td>
                            <td class="text-center d-flex justify-content-center align-items-center gap-1">
                                <a href="{{ route('datawarga.edit', $w->NIK) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('datawarga.destroy', $w->NIK) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">
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