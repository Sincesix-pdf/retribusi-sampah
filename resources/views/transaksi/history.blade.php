<x-layout>
    <div class="content-container pb-3">
        <div class="card custom-card">
            <div class="card-header custom-card-header">
                <h5 class="mb-0">Riwayat Transaksi</h5>
            </div>
            <div class="card-body">
                @if ($transaksi->isEmpty())
                    <div class="alert alert-warning">Belum ada transaksi.</div>
                @else
                    {{-- Tabel Desktop --}}
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-hover table-striped table-bordered w-100">
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

                    {{-- Tampilan Mobile --}}
                    <div class="d-md-none">
                        @foreach ($transaksi as $t)
                            <div class="border rounded p-3 mb-4 shadow-sm transaksi-card">
                                <div class="d-flex justify-content-between mb-2">
                                    <strong>{{ $t->order_id }}</strong>
                                    <span class="status-badge">
                                        @if ($t->status == 'settlement')
                                            <span class="badge bg-success">Lunas</span>
                                        @elseif ($t->status_menunggak)
                                            <span class="badge bg-danger">Menunggak</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Belum Bayar</span>
                                        @endif
                                    </span>
                                </div>

                                <div class="amount mb-2">
                                    Rp{{ number_format($t->amount, 0, ',', '.') }}
                                </div>

                                <div class="periode mb-3">
                                    {{ date('F', mktime(0, 0, 0, $t->tagihan->bulan, 1)) }} {{ $t->tagihan->tahun }}
                                </div>

                                @if ($t->status == 'pending')
                                    <form action="{{ route('transaksi.sendReminder', $t->id) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-success bayar-btn">Bayar Sekarang</button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layout>