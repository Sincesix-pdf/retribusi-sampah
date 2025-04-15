<aside class="sidebar">
    @php
        $dashboardRoute = match (Auth::user()->role->nama_role ?? null) {
            'admin' => route('admin.index'),
            'pendataan' => route('pendataan.index'),
            'keuangan' => route('keuangan.index'),
            'kepala_dinas' => route('kepala_dinas.index'),
            'warga' => route('warga.index'),
            default => '#',
        };
    @endphp

    <!-- Sidebar Header -->
    <header class="sidebar-header">
        <a href="{{ $dashboardRoute }}" class="header-logo">
            <img src="/gambar/GEH.png" alt="logo">
        </a>
        <button class="toggler sidebar-toggler">
            <span class="material-symbols-rounded">chevron_left</span>
        </button>
    </header>

    <nav class="sidebar-nav">
        <!-- Primary Navigation -->
        <ul class="nav-list primary-nav">
            <li class="nav-item">
                <a href="{{ $dashboardRoute }}" class="nav-link">
                    <span class="material-symbols-rounded">dashboard</span>
                    <span class="nav-label">Dashboard</span>
                </a>
                <span class="nav-tooltip">Dashboard</span>
            </li>

            @if(Auth::user()->role->nama_role == 'pendataan')
                <li class="nav-item">
                    <a href="{{ route('datawarga.index') }}" class="nav-link">
                        <span class="material-symbols-rounded">patient_list</span>
                        <span class="nav-label">Kelola WR</span>
                    </a>
                    <span class="nav-tooltip">Kelola WR</span>
                </li>
            @endif

            @if(Auth::user()->role->nama_role == 'pendataan')
                <li class="nav-item dropdown-container1">
                    <a href="#" class="nav-link dropdown-toggle1">
                        <span class="material-symbols-rounded">credit_card_gear</span>
                        <span class="nav-label">Kelola Tagihan</span>
                        <span class="dropdown-icon material-symbols-rounded">keyboard_arrow_down</span>
                    </a>
                    <ul class="dropdown-menu1">
                        <li class="nav-item"><a href="{{ route('tagihan.index.tetap') }}" class="nav-link dropdown-link1">Tagihan Tetap</a></li>
                        <li class="nav-item"><a href="{{ route('tagihan.index.tidak_tetap') }}" class="nav-link dropdown-link1">Tagihan Tidak Tetap</a></li>
                    </ul>
                </li>
                <span class="nav-tooltip">Kelola Tagihan</span>
            @endif

            @if(Auth::user()->role->nama_role == 'keuangan')
                <li class="nav-item">
                    <a href="{{ route('transaksi.index') }}" class="nav-link">
                        <span class="material-symbols-rounded">attach_money</span>
                        <span class="nav-label">Transaksi</span>
                    </a>
                    <span class="nav-tooltip">Transaksi</span>
                </li>
                <!-- <li class="nav-item">
                    <a href="{{ route('transaksi.laporan') }}" class="nav-link">
                        <span class="material-symbols-rounded">receipt</span>
                        <span class="nav-label">Laporan Keuangan</span>
                    </a>
                    <span class="nav-tooltip">Laporan Keuangan</span>
                </li> -->
            @endif

            @if(Auth::user()->role->nama_role == 'kepala_dinas')
                <li class="nav-item">
                    <a href="{{ route('kepala_dinas.tagihan') }}" class="nav-link">
                        <span class="material-symbols-rounded">credit_card_clock</span>
                        <span class="nav-label">Daftar Tagihan</span>
                    </a>
                    <span class="nav-tooltip">Daftar Tagihan</span>
                </li>
            @endif

            @if(in_array(Auth::user()->role->nama_role, ['admin', 'kepala_dinas']))
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <span class="material-symbols-rounded">monitoring</span>
                        <span class="nav-label">Grafik Pendapatan</span>
                    </a>
                    <span class="nav-tooltip">Grafik Pendapatan</span>
                </li>
            @endif

            @if(Auth::user()->role->nama_role == 'admin')
                <li class="nav-item">
                    <a href="{{ route('log-aktivitas.index') }}" class="nav-link">
                        <span class="material-symbols-rounded">history</span>
                        <span class="nav-label">Log Aktivitas</span>
                    </a>
                    <span class="nav-tooltip">Log Aktivitas</span>
                </li>
            @endif

            @if(Auth::user()->role->nama_role == 'warga')
                <li class="nav-item">
                    <a href="{{ route('transaksi.history') }}" class="nav-link">
                        <span class="material-symbols-rounded">history</span>
                        <span class="nav-label">Riwayat Transaksi</span>
                    </a>
                    <span class="nav-tooltip">Riwayat Transaksi</span>
                </li>
            @endif
        </ul>

        <!-- Logout -->
        <ul class="nav-list secondary-nav">
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <span class="material-symbols-rounded">logout</span>
                    <span class="nav-label">Logout</span>
                </a>
                <span class="nav-tooltip">Logout</span>
            </li>
        </ul>

        <!-- Hidden Logout Form -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </nav>
</aside>
