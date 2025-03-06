<x-layout>
    <div class="content-container">
        <h1 class="mb-4">Daftar Tagihan yang Diajukan</h1>

        <form action="{{ route('kepala_dinas.tagihan.setujui') }}" method="POST">
            @csrf
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="checkAll"></th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Bulan/Tahun</th>
                        <th>Jumlah Tagihan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tagihan as $t)
                        <tr>
                            <td><input type="checkbox" name="tagihan_id[]" value="{{ $t->id }}"></td>
                            <td>{{ $t->NIK }}</td>
                            <td>{{ $t->warga->pengguna->nama }}</td>
                            <td>{{ date('F', mktime(0, 0, 0, $t->bulan, 1)) }} {{ $t->tahun }}</td>
                            <td class="text-end">{{ number_format($t->tarif) }}</td>
                            <td>{{ ucfirst($t->status) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-check"></i> Setujui Tagihan
            </button>
        </form>
    </div>
</x-layout>