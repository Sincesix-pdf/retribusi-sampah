<x-layout>
    <div class="content-container pb-3">
        <div class="card custom-card">
            <div class="card-header custom-card-header">
                <h5 class="mb-0">Riwayat Log Aktivitas</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="LogTable" class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Waktu</th>
                                <th>Nama Pengguna</th>
                                <th>Role</th>
                                <th>Aksi</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logAktivitas as $key => $log)
                                <tr>
                                    <td>{{ $key + $logAktivitas->firstItem() }}</td>
                                    <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $log->pengguna->nama ?? '-' }}</td>
                                    <td>{{ $log->pengguna->role->nama_role ?? '-' }}</td>
                                    <td>{{ $log->aksi }}</td>
                                    <td>{{ $log->deskripsi ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">Belum ada log aktivitas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $logAktivitas->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layout>
