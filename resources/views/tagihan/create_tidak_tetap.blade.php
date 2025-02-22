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

            <button type="submit" class="btn btn-primary mt-3">Buat Tagihan</button>
        </form>
    </div>
</x-layout>
