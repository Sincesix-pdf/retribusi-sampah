<x-layout>
    <div class="container">
        <h2>Tambah Warga</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('datawarga.store') }}" method="POST">
            @csrf
            <label>Nama:</label>
            <input type="text" name="nama" class="form-control" required>

            <label>Email:</label>
            <input type="email" name="email" class="form-control" required>

            <label>Password:</label>
            <div class="input-group">
                <input type="password" id="password" name="password" class="form-control" required>
                <span class="input-group-text" onclick="togglePassword('password', 'togglePasswordIcon1')" style="cursor: pointer;">
                    <span id="togglePasswordIcon1" class="material-symbols-rounded">visibility</span>
                </span>
            </div>

            <label>Ulangi Password:</label>
            <div class="input-group">
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                <span class="input-group-text" onclick="togglePassword('password_confirmation', 'togglePasswordIcon2')" style="cursor: pointer;">
                    <span id="togglePasswordIcon2" class="material-symbols-rounded">visibility</span>
                </span>
            </div>

            <label>Alamat:</label>
            <input type="text" name="alamat" class="form-control" required>

            <label>No HP:</label>
            <input type="text" name="no_hp" class="form-control" required>

            <label>Jenis Kelamin:</label>
            <select name="jenis_kelamin" class="form-control">
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>

            <label>Tanggal Lahir:</label>
            <input type="date" name="tanggal_lahir" class="form-control" required>

            <label>NIK:</label>
            <input type="text" name="NIK" class="form-control @error('NIK') is-invalid @enderror" value="{{ old('NIK') }}">
            @error('NIK')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <!-- Dropdown Kecamatan -->
            <label>Kecamatan:</label>
            <select name="kecamatan_id" id="kecamatan_id" class="form-control">
                <option value="">Pilih Kecamatan</option>
                @foreach($kecamatan as $kec)
                    <option value="{{ $kec->id }}">{{ $kec->nama }}</option>
                @endforeach
            </select>

            <!-- Dropdown Kelurahan (Akan diisi berdasarkan Kecamatan) -->
            <label>Kelurahan:</label>
            <select name="kelurahan_id" id="kelurahan_id" class="form-control">
                <option value="">Pilih Kelurahan</option>
            </select>

            <label>Jenis Retribusi:</label>
            <select name="jenis_retribusi" class="form-control">
                <option value="Tetap">Tetap</option>
                <option value="Tidak Tetap">Tidak Tetap</option>
            </select>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('datawarga.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</x-layout>
