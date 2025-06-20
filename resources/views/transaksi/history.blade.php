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
                    @if ($jumlahTunggakan > 0)
                        <div class="alert alert-danger">
                            <strong>Perhatian!</strong> Anda memiliki {{ $jumlahTunggakan }} bulan tunggakan
                            dengan total <strong>Rp{{ number_format($totalTunggakan, 0, ',', '.') }}</strong>. Segera melakukan
                            pembayaran.
                            <a href="#"
                                onclick="event.preventDefault(); document.getElementById('rincian-tunggakan').classList.toggle('d-none');"
                                style="text-decoration: underline;">lihat rincian</a>
                            <ul id="rincian-tunggakan" class="mt-2 d-none mb-0">
                                @foreach ($rincianTunggakan as $r)
                                    <li>{{ $r }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Tabel Desktop --}}
                    @if ($transaksiTetap->isNotEmpty())
                        <div class="table-responsive d-none d-md-block">
                            <table class="table table-hover table-striped table-bordered w-100">
                                <thead class="table-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Order ID</th>
                                        <th>Jumlah</th>
                                        <th>Periode</th>
                                        <th>Status</th>
                                        <th>Tanggal Bayar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transaksiTetap as $key => $t)
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
                                                    <span class="badge bg-danger">
                                                        Menunggak
                                                        @if (!empty($t->akumulasi_tunggakan) && $t->akumulasi_tunggakan > 1)
                                                            ({{ $t->akumulasi_tunggakan }} bulan)
                                                        @endif
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">Belum Bayar</span>
                                                @endif
                                            </td>
                                            <td>{{ $t->updated_at->format('d-m-Y H:i') }}</td>
                                            <td>
                                                @if ($t->status == 'pending')
                                                    <form action="{{ route('transaksi.bayarLangsung', $t->id) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            <i class="fa-solid fa-dollar-sign"></i> Bayar
                                                        </button>
                                                    </form>
                                                @elseif ($t->status == 'settlement')
                                                    <a href="{{ route('transaksi.cetakBukti', $t->id) }}" class="btn btn-sm btn-primary"
                                                        target="_blank">
                                                        <i class="fa-solid fa-print"></i> Nota
                                                    </a>
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

                    @if ($transaksiTidakTetap->isNotEmpty())
                        <div class="table-responsive d-none d-md-block">
                            <table class="table table-hover table-striped table-bordered w-100">
                                <thead class="table-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Order ID</th>
                                        <th>Jumlah</th>
                                        <th>Volume</th>
                                        <th>Status</th>
                                        <th>Tanggal Bayar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transaksiTidakTetap as $key => $t)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $t->order_id }}</td>
                                            <td class="text-end">Rp{{ number_format($t->amount, 0, ',', '.') }}</td>
                                            <td>{{ $t->tagihan->volume ?? '-' }}</td>
                                            <td>
                                                @if ($t->status == 'settlement')
                                                    <span class="badge bg-success">Lunas</span>
                                                @elseif ($t->status_menunggak)
                                                    <span class="badge bg-danger">
                                                        Menunggak
                                                        @if (!empty($t->akumulasi_tunggakan) && $t->akumulasi_tunggakan > 1)
                                                            ({{ $t->akumulasi_tunggakan }} bulan)
                                                        @endif
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">Belum Bayar</span>
                                                @endif
                                            </td>
                                            <td>{{ $t->updated_at->format('d-m-Y H:i') }}</td>
                                            <td>
                                                @if ($t->status == 'pending')
                                                    <form action="{{ route('transaksi.bayarLangsung', $t->id) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            <i class="fa-solid fa-dollar-sign"></i> Bayar
                                                        </button>
                                                    </form>
                                                @elseif ($t->status == 'settlement')
                                                    <a href="{{ route('transaksi.cetakBukti', $t->id) }}" class="btn btn-sm btn-primary"
                                                        target="_blank">
                                                        <i class="fa-solid fa-print"></i> Nota
                                                    </a>
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

                    {{-- Tampilan Mobile --}}
                    <div class="d-md-none">
                        @if ($transaksiTetap->isNotEmpty())
                            @foreach ($transaksiTetap as $t)
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

                                    <div class="bayar-btn">
                                        @if ($t->status == 'pending')
                                            <form action="{{ route('transaksi.bayarLangsung', $t->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fa-solid fa-dollar-sign"></i> Bayar
                                                </button>
                                            </form>
                                        @elseif ($t->status == 'settlement')
                                            <a href="{{ route('transaksi.cetakBukti', $t->id) }}" class="btn btn-sm btn-primary"
                                                target="_blank">
                                                <i class="fa-solid fa-print"></i> Nota
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        @if ($transaksiTidakTetap->isNotEmpty())
                            @foreach ($transaksiTidakTetap as $t)
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
                                        Volume: {{ $t->tagihan->volume ?? '-' }}
                                    </div>

                                    <div class="bayar-btn">
                                        @if ($t->status == 'pending')
                                            <form action="{{ route('transaksi.bayarLangsung', $t->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fa-solid fa-dollar-sign"></i> Bayar
                                                </button>
                                            </form>
                                        @elseif ($t->status == 'settlement')
                                            <a href="{{ route('transaksi.cetakBukti', $t->id) }}" class="btn btn-sm btn-primary"
                                                target="_blank">
                                                <i class="fa-solid fa-print"></i> Nota
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layout>