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

                    <label>Alamat:</label>
                    <input type="text" name="alamat" class="form-control" value="{{ old('alamat') }}" required>

                    <label>Kecamatan:</label>
                    <select name="kecamatan_id" id="kecamatan_id" class="form-control">
                        <option value="">Pilih Kecamatan</option>
                        @foreach($kecamatan as $kec)
                            <option value="{{ $kec->id }}" {{ old('kecamatan_id') == $kec->id ? 'selected' : '' }}>
                                {{ $kec->nama }}
                            </option>
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
                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                        </option>
                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan
                        </option>
                    </select>

                    <label>Tanggal Lahir:</label>
                    <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}"
                        required>

                    <label>NIK:</label>
                    <input type="text" name="NIK" class="form-control @error('NIK') is-invalid @enderror"
                        value="{{ old('NIK') }}">
                    @error('NIK')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                    <label>Kategori Retribusi:</label>
                    <select name="kategori_retribusi" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        <option value="warga" {{ old('kategori_retribusi') == 'warga' ? 'selected' : '' }}>Warga</option>
                        <option value="industri" {{ old('kategori_retribusi') == 'industri' ? 'selected' : '' }}>Industri
                        </option>
                        <option value="umkm" {{ old('kategori_retribusi') == 'umkm' ? 'selected' : '' }}>UMKM</option>
                        <option value="event" {{ old('kategori_retribusi') == 'event' ? 'selected' : '' }}>Event</option>
                    </select>

                    <label>Jenis Retribusi:</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="jenis_retribusi" id="jenis_tetap"
                            value="tetap" {{ old('jenis_retribusi', 'tetap') == 'tetap' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="jenis_tetap">Tetap</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="jenis_retribusi" id="jenis_retasi"
                            value="retasi" {{ old('jenis_retribusi') == 'retasi' ? 'checked' : '' }}>
                        <label class="form-check-label" for="jenis_retasi">Retasi</label>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('datawarga.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-layout>