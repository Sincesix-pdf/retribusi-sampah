<x-layout>
    <div class="content-container">
        <h2>Edit Tagihan Tetap</h2>
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

            <label>Bulan/Tahun</label>
            <div class="form-control bg-light">{{ date('F', mktime(0, 0, 0, $tagihan->bulan, 1)) }} {{ $tagihan->tahun }}</div>
            <input type="hidden" name="bulan" value="{{ $tagihan->bulan }}">
            <input type="hidden" name="tahun" value="{{ $tagihan->tahun }}">

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('tagihan.index.tetap') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</x-layout>
