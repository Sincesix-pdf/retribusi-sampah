<x-layout>
<div class="container">
    <h2 class="mb-4">Riwayat Transaksi</h2>
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@elseif(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif


    @if ($transaksi->isEmpty())
        <div class="alert alert-warning">Belum ada transaksi.</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Order ID</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksi as $key => $t)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $t->order_id }}</td>
                        <td class="text-end">{{ number_format($t->amount) }}</td>
                        <td>
                            @if ($t->status == 'settlement')
                                <span class="badge bg-success">Lunas</span>
                            @elseif ($t->status == 'pending')
                                <span class="badge bg-warning">Menunggu</span>
                            @else
                                <span class="badge bg-danger">Gagal</span>
                            @endif
                        </td>
                        <td>{{ $t->updated_at->format('d-m-Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
</x-layout>
