<x-layout>
    <div class="content-container pb-3">
        <div class="card custom-card">
            <div class="card-header custom-card-header">
                <h5 class="mb-0">Edit WR</h5>
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
                    <input type="email" name="email" class="form-control" value="{{ $warga->pengguna->email }}"
                        required>

                    <label>Alamat:</label>
                    <input type="text" name="alamat" class="form-control" value="{{ $warga->pengguna->alamat }}"
                        required>

                    <label>Kecamatan:</label>
                    <select name="kecamatan_id" id="kecamatan_id" class="form-control">
                        @foreach($kecamatan as $kec)
                            <option value="{{ $kec->id }}" {{ ($warga->kelurahan->kecamatan_id ?? $warga->kecamatan_id) == $kec->id ? 'selected' : '' }}>
                                {{ $kec->nama }}
                            </option>
                        @endforeach
                    </select>

                    <label>Kelurahan:</label>
                    <select name="kelurahan_id" id="kelurahan_id" class="form-select"
                        data-old="{{ $warga->kelurahan_id }}">
                        <option value="">Semua Kelurahan</option>
                    </select>

                    <label>No HP:</label>
                    <input type="text" name="no_hp" class="form-control" value="{{ $warga->pengguna->no_hp }}" required>

                    <label>Jenis Kelamin:</label>
                    <select name="jenis_kelamin" class="form-control">
                        <option value="Laki-laki" {{ $warga->pengguna->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>
                            Laki-laki</option>
                        <option value="Perempuan" {{ $warga->pengguna->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>
                            Perempuan</option>
                    </select>

                    <label>Tanggal Lahir:</label>
                    <input type="date" name="tanggal_lahir" class="form-control"
                        value="{{ $warga->pengguna->tanggal_lahir }}" required>

                    <label>NIK:</label>
                    <input type="text" name="NIK" class="form-control" value="{{ $warga->NIK }}" readonly>

                    <label>Kategori Retribusi Sampah:</label>
                    <select name="kategori_retribusi" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        <option value="warga" {{ $warga->kategori_retribusi == 'warga' ? 'selected' : '' }}>Warga</option>
                        <option value="industri" {{ $warga->kategori_retribusi == 'industri' ? 'selected' : '' }}>Industri
                        </option>
                        <option value="umkm" {{ $warga->kategori_retribusi == 'umkm' ? 'selected' : '' }}>UMKM</option>
                        <option value="event" {{ $warga->kategori_retribusi == 'event' ? 'selected' : '' }}>Event</option>
                    </select><br>

                    <label>Jenis Retribusi:</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="jenis_retribusi" id="jenis_tetap"
                            value="tetap" {{ $warga->jenis_retribusi == 'tetap' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="jenis_tetap">Tetap</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="jenis_retribusi" id="jenis_retasi"
                            value="retasi" {{ $warga->jenis_retribusi == 'retasi' ? 'checked' : '' }}>
                        <label class="form-check-label" for="jenis_retasi">Retasi</label>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Ubah</button>
                        <a href="{{ route('datawarga.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>