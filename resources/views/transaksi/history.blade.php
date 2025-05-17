<x-layout>
    <div class="content-container pb-3">
        <div class="card custom-card">
            <div class="card-header custom-card-header">
                <h5 class="mb-0">Riwayat Transaksi</h5>
            </div>
            <div class="card-body">
                {{-- Konten Tabel Transaksi --}}
                @if ($transaksi->isEmpty())
                    <div class="alert alert-warning">Belum ada transaksi.</div>
                @else
                    <div class="card-body p-2">
                        <div class="table-responsive custom-table-container">
                            <table id="tabel-warga" class="table table-hover table-striped table-bordered table w-100">
                                <thead class="table-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Order ID</th>
                                        <th>Jumlah</th>
                                        <th>Periode</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transaksi as $key => $t)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $t->order_id }}</td>
                                            <td class="text-end">Rp{{ number_format($t->amount, 0, ',', '.') }}</td>
                                            <td>{{ date('F', mktime(0, 0, 0, $t->tagihan->bulan, 1)) }} {{ $t->tagihan->tahun }}
                                            </td>
                                            <td>
                                                @if ($t->status == 'settlement')
                                                    <span class="badge bg-success">Lunas</span>
                                                @elseif ($t->status_menunggak)
                                                    <span class="badge bg-danger">Menunggak</span>
                                                @else
                                                    <span class="badge bg-warning">Belum Bayar</span>
                                                @endif
                                            </td>
                                            <td>{{ $t->updated_at->format('d-m-Y H:i') }}</td>
                                            <td>
                                                @if ($t->status == 'pending')
                                                    <form action="{{ route('transaksi.sendReminder', $t->id) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            <i class="fa-solid fa-dollar-sign"></i> Bayar
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                @endif
                </div>
            </div>
        </div>
</x-layout>