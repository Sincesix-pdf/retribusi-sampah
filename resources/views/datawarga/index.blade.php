<x-layout>
<div class="content-container">
        <h1>Halaman Kelola WR</h1>
        <div class="container">
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

                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('datawarga.index') }}" class="btn btn-secondary ml-2">Reset</a>
                    </div>
                </div>
            </form>

            <a class="btn btn-success mb-3" href="{{ route('datawarga.create') }}">Tambah Warga</a>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table">
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
                            <td>
                                <a href="{{ route('datawarga.edit', $w->NIK) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('datawarga.destroy', $w->NIK) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Yakin hapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Custom Pagination -->
            <ul class="pagination modal-1">
                @if ($warga->onFirstPage())
                    <li class="disabled"><span>&laquo;</span></li>
                @else
                    <li><a href="{{ $warga->previousPageUrl() }}" class="prev">&laquo;</a></li>
                @endif

                @for ($i = 1; $i <= $warga->lastPage(); $i++)
                    <li class="{{ ($warga->currentPage() == $i) ? 'active' : '' }}">
                        <a href="{{ $warga->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor

                @if ($warga->hasMorePages())
                    <li><a href="{{ $warga->nextPageUrl() }}" class="next">&raquo;</a></li>
                @else
                    <li class="disabled"><span>&raquo;</span></li>
                @endif
            </ul>

        </div>
    </div>
</x-layout>
