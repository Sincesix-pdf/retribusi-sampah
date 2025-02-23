<x-layout>
    <div class="container">
        <h2>Edit Warga</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('datawarga.update', ['NIK' => $warga->NIK]) }}" method="POST">
            @csrf
            @method('PUT')

            <label>NIK:</label>
            <input type="text" name="NIK" class="form-control" value="{{ $warga->NIK }}" readonly>

            <label>Nama:</label>
            <input type="text" name="nama" class="form-control" value="{{ $warga->pengguna->nama }}" required>

            <label>Email:</label>
            <input type="email" name="email" class="form-control" value="{{ $warga->pengguna->email }}" required>

            <label>Alamat:</label>
            <input type="text" name="alamat" class="form-control" value="{{ $warga->pengguna->alamat }}" required>

            <label>Kecamatan:</label>
            <select name="kecamatan_id" id="kecamatan_id" class="form-control">
                @foreach($kecamatan as $kec)
                    <option value="{{ $kec->id }}" {{ ($warga->kelurahan->kecamatan_id ?? $warga->kecamatan_id) == $kec->id ? 'selected' : '' }}>
                        {{ $kec->nama }}
                    </option>
                @endforeach
            </select>

            <label>Kelurahan:</label>
            <select name="kelurahan_id" id="kelurahan_id" class="form-control">
                @foreach($kelurahan as $kel)
                    <option value="kelurahan_id"{{ $warga->kelurahan_id ? 'selected' : '' }}>
                        {{ $kel->nama }}
                    </option>
                @endforeach
            </select>


            <label>No HP:</label>
            <input type="text" name="no_hp" class="form-control" value="{{ $warga->pengguna->no_hp }}" required>

            <label>Jenis Kelamin:</label>
            <select name="jenis_kelamin" class="form-control">
                <option value="Laki-laki" {{ $warga->pengguna->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                </option>
                <option value="Perempuan" {{ $warga->pengguna->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan
                </option>
            </select>

            <label>Tanggal Lahir:</label>
            <input type="date" name="tanggal_lahir" class="form-control" value="{{ $warga->pengguna->tanggal_lahir }}"
                required>

            <label>Jenis Retribusi:</label>
            <select name="jenis_retribusi" class="form-control">
                <option value="tetap" {{ $warga->jenis_retribusi == 'tetap' ? 'selected' : '' }}>Tetap</option>
                <option value="tidak_tetap" {{ $warga->jenis_retribusi == 'tidak_tetap' ? 'selected' : '' }}>Tidak Tetap
                </option>
            </select>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('datawarga.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>

    </div>
</x-layout>