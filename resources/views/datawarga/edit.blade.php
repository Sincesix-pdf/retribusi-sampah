<x-layout>
    <div class="content-container pb-3">
        <div class="card custom-card">
            <div class="card-header custom-card-header">
                <h5 class="mb-0">Edit Warga</h5>
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

                <form action="{{ route('datawarga.update', ['NIK' => $warga->NIK]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <label>Nama:</label>
                    <input type="text" name="nama" class="form-control" value="{{ $warga->pengguna->nama }}" required>

                    <label>Email:</label>
                    <input type="email" name="email" class="form-control" value="{{ $warga->pengguna->email }}" required>

                    <label>Password:</label>
                    <div class="input-group">
                        <input type="password" id="password" name="password" class="form-control">
                        <span class="input-group-text" onclick="togglePassword('password', 'togglePasswordIcon1')" style="cursor: pointer;">
                            <span id="togglePasswordIcon1" class="material-symbols-rounded">visibility</span>
                        </span>
                    </div>

                    <label>Ulangi Password:</label>
                    <div class="input-group">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                        <span class="input-group-text" onclick="togglePassword('password_confirmation', 'togglePasswordIcon2')" style="cursor: pointer;">
                            <span id="togglePasswordIcon2" class="material-symbols-rounded">visibility</span>
                        </span>
                    </div>

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
                    <select name="kelurahan_id" id="kelurahan_id" class="form-select" data-old="{{ $warga->kelurahan_id }}">
                        <option value="">Semua Kelurahan</option>
                    </select>

                    <label>No HP:</label>
                    <input type="text" name="no_hp" class="form-control" value="{{ $warga->pengguna->no_hp }}" required>

                    <label>Jenis Kelamin:</label>
                    <select name="jenis_kelamin" class="form-control">
                        <option value="Laki-laki" {{ $warga->pengguna->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ $warga->pengguna->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>

                    <label>Tanggal Lahir:</label>
                    <input type="date" name="tanggal_lahir" class="form-control" value="{{ $warga->pengguna->tanggal_lahir }}" required>

                    <label>NIK:</label>
                    <input type="text" name="NIK" class="form-control" value="{{ $warga->NIK }}" readonly>

                    <label>Jenis Retribusi:</label>
                    <select name="jenis_retribusi" id="jenis_retribusi" class="form-control">
                        <option value="tetap" {{ $warga->jenis_retribusi == 'tetap' ? 'selected' : '' }}>Tetap</option>
                        <option value="tidak_tetap" {{ $warga->jenis_retribusi == 'tidak_tetap' ? 'selected' : '' }}>Tidak Tetap</option>
                    </select>

                    <label>Jenis Layanan:</label>
                    <select name="jenis_layanan_id" id="jenis_layanan_id" class="form-control">
                        <option value="">Pilih Jenis Layanan</option>
                        @foreach($jenis_layanan as $layanan)
                            <option value="{{ $layanan->id }}" {{ $warga->jenis_layanan_id == $layanan->id ? 'selected' : '' }}>
                                {{ $layanan->nama_paket }}
                            </option>
                        @endforeach
                    </select>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Ubah</button>
                        <a href="{{ route('datawarga.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
