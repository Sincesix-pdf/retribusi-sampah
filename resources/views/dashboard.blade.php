<x-layout>
    <div class="content-container">
        <div class="dashboard-header-card">
            <h1>Dashboard</h1>
            <p>Selamat datang! Anda login sebagai <strong>{{ ucwords(str_replace('_', ' ', $role)) }}</strong></p>
        </div>
        <div class="dashboard-cards">
            <div class="cards">
                <h3>Jumlah Warga</h3>
                <p>{{ $jumlahWarga }} Orang</p>
            </div>
            <div class="cards">
                <h3>Retribusi Tetap</h3>
                <p>{{ $jumlahRetribusiTetap }} Warga</p>
            </div>
            <div class="cards">
                <h3>Retribusi Tidak Tetap</h3>
                <p>{{ $jumlahRetribusiTidakTetap }} Warga</p>
            </div>
            <div class="cards">
                <h3>Jumlah Petugas</h3>
                <p>{{ $jumlahPetugas }} Petugas</p>
            </div>
        </div>
    </div>
</x-layout>