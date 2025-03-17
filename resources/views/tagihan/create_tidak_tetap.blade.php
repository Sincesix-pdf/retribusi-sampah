<x-layout>
    <div class="container">
        <h2>Buat Tagihan Tidak Tetap</h2>
        <form action="{{ route('tagihan.store.tidak_tetap') }}" method="POST">
            @csrf
            <label>Pilih Warga</label>
            <select name="NIK" id="wargaSelect" class="form-control">
                @foreach($warga as $w)
                    <option value="{{ $w->NIK }}">
                        {{ $w->NIK }} - {{ $w->pengguna->nama }}
                    </option>
                @endforeach
            </select>

            <label>Pilih Jenis Tarif</label>
            <select name="jenis_tarif" id="jenisTarifSelect" class="form-control">
                @foreach($tarif as $t)
                    <option value="{{ $t->jenis_tarif }}" data-tarif="{{ $t->tarif_per_kubik }}">
                        {{ ucfirst(str_replace('_', ' ', $t->jenis_tarif)) }} - Rp
                        {{ number_format($t->tarif_per_kubik, 0, ',', '.') }} / m³
                    </option>
                @endforeach
            </select>

            <label>Volume (m³)</label>
            <input type="number" name="volume" id="volumeInput" class="form-control" required>

            <label>Tarif (per m³)</label>
            <input type="number" name="tarif" id="tarifInput" class="form-control" readonly>

            <label>Total</label>
            <input type="number" id="totalInput" class="form-control" readonly>


            <div class="col-md-6">
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Buat dan Ajukan Tagihan</button>

                    <a href="{{ route('tagihan.index.tidak_tetap') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</x-layout>