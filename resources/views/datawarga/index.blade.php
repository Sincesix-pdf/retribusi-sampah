<x-layout>
    <div class="content-container">
        <h1>Halaman Kelola WR</h1>
        <div class="container">
            <h2>Daftar Warga</h2>
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
        </div>
    </div>

</x-layout>