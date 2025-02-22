<x-layout>
    <div class="content-container">
        <h2>Buat Tagihan Tetap</h2>
        <form action="{{ route('tagihan.store.tetap') }}" method="POST">
            @csrf
            <label>Pilih Warga</label>
            <div class="col-md-6">
            <select name="NIK" id="wargaSelect" class="form-control">
                @foreach($warga as $w)
                    <option value="{{ $w->NIK }}" data-retribusi="{{ $w->jenis_retribusi }}">
                        {{ $w->NIK }} - {{ $w->pengguna->nama }}
                    </option>
                @endforeach
            </select>
            </div>

            <input type="hidden" name="jenis_retribusi" value="tetap">

            <div class="col-md-6">
            <label>Tarif</label>
            <input type="number" name="tarif" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label>Bulan/Tahun</label>
                <select name="bulan" id="bulan" class="form-select" required>
                    <option value="">Pilih Bulan</option>
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
            <label>Tanggal Tagihan</label>
            <input type="date" name="tanggal_tagihan" class="form-control" required>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Buat Tagihan</button>
                <a href="{{ route('tagihan.index.tetap') }}" class="btn btn-secondary">Kembali</a>
            </div>
            </div>
        </form>
    </div>
</x-layout>