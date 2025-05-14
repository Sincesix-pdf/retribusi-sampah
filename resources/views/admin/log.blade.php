<x-layout>
    <div class="content-container pb-3">
        <div class="card custom-card">
            <div class="card-header custom-card-header">
                <h5 class="mb-0">Riwayat Log Aktivitas</h5>
            </div>
            <div class="card-body p-2">
                <div class="tabel-responsive custom-table-container">
                    <table id="tabel-warga" class="table table-hover table-striped table-bordered table w-100">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Waktu</th>
                                <th>Nama Pengguna</th>
                                <th>Role</th>
                                <th>Aksi</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logAktivitas as $log)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $log->pengguna->nama ?? '-' }}</td>
                                    <td>{{ ucwords(str_replace('_', ' ', $log->pengguna->role->nama_role ?? '-')) }}</td>
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
            </div>
        </div>
    </div>
</x-layout>