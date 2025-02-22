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
    <!-- Sidebar header -->
    <header class="sidebar-header">
        <a href="{{ $dashboardRoute }}" class="header-logo">
            <img src="/gambar/GEH.png" alt="logo">
        </a>
        <button class="toggler sidebar-toggler">
            <span class="material-symbols-rounded">chevron_left</span>
        </button>
        <button class="toggler menu-toggler">
            <span class="material-symbols-rounded">menu</span>
        </button>
    </header>

    <nav class="sidebar-nav">
        <!-- Primary top nav -->
        <ul class="nav-list primary-nav">
            <li class="nav-item">
                <a href="{{ $dashboardRoute }}" class="nav-link">
                    <span class="nav-icon material-symbols-rounded">dashboard</span>
                    <span class="nav-label">Dashboard</span>
                </a>
                <span class="nav-tooltip">Dashboard</span>
            </li>

            @if(Auth::user()->role->nama_role == 'pendataan')
                <li class="nav-item">
                    <a href="{{ route(name: 'datawarga.index') }}" class="nav-link">
                        <span class="material-symbols-rounded">patient_list</span>
                        <span class="nav-label">Kelola WR</span>
                    </a>
                    <span class="nav-tooltip">Kelola WR</span>
                </li>
            @endif

            @if(Auth::user()->role->nama_role == 'pendataan')
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" id="tagihanDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="material-symbols-rounded">credit_card_gear</span>
                        <span class="nav-label">Kelola Tagihan</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="tagihanDropdown">
                        <li><a class="dropdown-item" href="{{ route('tagihan.index.tetap') }}">Tagihan Tetap</a></li>
                        <li><a class="dropdown-item" href="{{ route('tagihan.index.tidak_tetap') }}">Tagihan Tidak Tetap</a>
                        </li>
                    </ul>
                </li>
            @endif

            @if(Auth::user()->role->nama_role == 'pendataan')
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <span class="material-symbols-rounded">attach_money</span>
                        <span class="nav-label">Transaksi</span>
                    </a>
                    <span class="nav-tooltip">Transaksi</span>
                </li>
            @endif

            @if(Auth::user()->role->nama_role == 'keuangan')
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <span class="material-symbols-rounded">receipt</span>
                        <span class="nav-label">Laporan Keuangan</span>
                    </a>
                    <span class="nav-tooltip">Laporan Keuangan</span>
                </li>
            @endif

            @if(Auth::user()->role->nama_role == 'kepala_dinas')
                <li class="nav-item">
                    <a href="{{ route(name: 'tagihan.daftartagihan') }}" class="nav-link">
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
                    <a href="{{ route(name: 'log-aktivitas.index') }}" class="nav-link">
                        <span class="nav-icon material-symbols-rounded">history</span>
                        <span class="nav-label">Log Aktivitas</span>
                    </a>
                    <span class="nav-tooltip">Log Aktivitas</span>
                </li>
            @endif
        </ul>


        <!-- Secondary bottom nav -->
        <!-- <ul class="nav-list secondary-nav">
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <span class="nav-icon material-symbols-rounded">account_circle</span>
                    <span class="nav-label">Profile</span>
                </a>
                <span class="nav-tooltip">Profile</span>
            </li> -->
        <li class="nav-list secondary-nav">
            <a href="#" class="nav-link"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <span class="nav-icon material-symbols-rounded">logout</span>
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