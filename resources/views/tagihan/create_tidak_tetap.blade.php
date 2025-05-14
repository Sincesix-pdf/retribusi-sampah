<x-layout>
    <div class="content-container pb-3">
        <div class="card custom-card">
            <div class="card-header custom-card-header">
                <h5 class="mb-0">Buat Tagihan Tidak Tetap</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('tagihan.store.tidak_tetap') }}" method="POST">
                    @csrf

                    <label for="wargaSelect">Pilih Warga</label>
                    <select name="NIK" id="wargaSelect" class="form-control">
                        @foreach($warga as $w)
                            <option value="{{ $w->NIK }}">
                                {{ $w->NIK }} - {{ $w->pengguna->nama }}
                            </option>
                        @endforeach
                    </select>

                    <label for="jenisTarifSelect">Pilih Jenis Tarif</label>
                    <select name="jenis_tarif" id="jenisTarifSelect" class="form-control">
                        @foreach($tarif as $t)
                            <option value="{{ $t->jenis_tarif }}" data-tarif="{{ $t->tarif_per_kubik }}">
                                {{ ucfirst(str_replace('_', ' ', $t->jenis_tarif)) }} - Rp
                                {{ number_format($t->tarif_per_kubik, 0, ',', '.') }} / m³
                            </option>
                        @endforeach
                    </select>

                    <label for="volumeInput">Volume (m³)</label>
                    <input type="number" name="volume" id="volumeInput" class="form-control" required>

                    <label for="tarifInput">Tarif (per m³)</label>
                    <input type="number" name="tarif" id="tarifInput" class="form-control" readonly>

                    <label for="totalInput">Total</label>
                    <input type="number" id="totalInput" class="form-control" readonly>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Buat Tagihan</button>
                        <a href="{{ route('tagihan.index.tidak_tetap') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
