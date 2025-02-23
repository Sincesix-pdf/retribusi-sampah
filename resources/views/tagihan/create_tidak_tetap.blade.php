<x-layout>
    <div class="container">
        <h2>Buat Tagihan Tidak Tetap</h2>
        <form action="{{ route('tagihan.store.tidak_tetap') }}" method="POST">
            @csrf
            <label>Pilih Warga</label>
            <select name="NIK" id="wargaSelect" class="form-control">
                @foreach($warga as $w)
                    <option value="{{ $w->NIK }}" data-retribusi="{{ $w->jenis_retribusi }}">
                        {{ $w->NIK }} - {{ $w->pengguna->nama }}
                    </option>
                @endforeach
            </select>

            <input type="hidden" name="jenis_retribusi" value="tidak_tetap">

            <label>Volume</label>
            <input type="number" name="volume" class="form-control" required>

            <label>Tarif</label>
            <input type="number" name="tarif" class="form-control" required>

            <label>Tanggal Tagihan</label>
            <input type="date" name="tanggal_tagihan" class="form-control" required>

            <div class="col-md-6">
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Buat Tagihan</button>
                    <a href="{{ route('tagihan.index.tidak_tetap') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</x-layout>