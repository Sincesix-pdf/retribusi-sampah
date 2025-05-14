<x-layout>
    <div class="content-container pb-3">
        <div class="card custom-card">
            <div class="card-header custom-card-header">
                <h5 class="mb-0">Tambah Warga</h5>
            </div>
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('datawarga.store') }}" method="POST">
                    @csrf

                    <label>Nama:</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>

                    <label>Email:</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>

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
                    <input type="text" name="alamat" class="form-control" value="{{ old('alamat') }}" required>

                    <label>Kecamatan:</label>
                    <select name="kecamatan_id" id="kecamatan_id" class="form-control">
                        <option value="">Pilih Kecamatan</option>
                        @foreach($kecamatan as $kec)
                            <option value="{{ $kec->id }}" {{ old('kecamatan_id') == $kec->id ? 'selected' : '' }}>{{ $kec->nama }}</option>
                        @endforeach
                    </select>

                    <div class="mb-3">
                        <label for="kelurahan_id" class="form-label">Kelurahan</label>
                        <select name="kelurahan_id" id="kelurahan_id" class="form-select">
                            <option value="">Pilih Kelurahan</option>
                        </select>
                        @error('kelurahan_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <label>No HP:</label>
                    <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}" required>

                    <label>Jenis Kelamin:</label>
                    <select name="jenis_kelamin" class="form-control">
                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>

                    <label>Tanggal Lahir:</label>
                    <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}" required>

                    <label>NIK:</label>
                    <input type="text" name="NIK" class="form-control @error('NIK') is-invalid @enderror" value="{{ old('NIK') }}">
                    @error('NIK')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                    <label>Jenis Retribusi:</label>
                    <select name="jenis_retribusi" id="jenis_retribusi" class="form-control">
                        <option value="tetap" {{ old('jenis_retribusi') == 'tetap' ? 'selected' : '' }}>Tetap</option>
                        <option value="tidak_tetap" {{ old('jenis_retribusi') == 'tidak_tetap' ? 'selected' : '' }}>Tidak Tetap</option>
                    </select>

                    <label>Jenis Layanan:</label>
                    <select name="jenis_layanan_id" id="jenis_layanan_id" class="form-control">
                        <option value="">Pilih Jenis Layanan</option>
                        @foreach($jenis_layanan as $jl)
                            <option value="{{ $jl->id }}" {{ old('jenis_layanan_id') == $jl->id ? 'selected' : '' }}>
                                {{ $jl->nama_paket }}
                            </option>
                        @endforeach
                    </select>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('datawarga.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-layout>
