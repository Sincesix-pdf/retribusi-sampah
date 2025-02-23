<x-layout>
    <div class="content-container">
        <h2>Edit Tagihan Tidak Tetap</h2>
        <form action="{{ route('tagihan.update', $tagihan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <label>Pilih Warga</label>
            <select name="NIK" class="form-control">
                @foreach($warga as $w)
                    <option value="{{ $w->NIK }}" {{ $tagihan->NIK == $w->NIK ? 'selected' : '' }}>
                        {{ $w->NIK }} - {{ $w->pengguna->nama }}
                    </option>
                @endforeach
            </select>

            <label>Tarif</label>
            <input type="number" name="tarif" class="form-control" value="{{ $tagihan->tarif }}" required>

            <label>Volume</label>
            <input type="number" name="volume" class="form-control" value="{{ $tagihan->volume }}" required>

            <label>Tanggal Tagihan</label>
            <input type="date" name="tanggal_tagihan" class="form-control" value="{{ $tagihan->tanggal_tagihan }}"
                required>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('tagihan.index.tidak_tetap') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</x-layout>