<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retribusi Online</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<body>
    <nav class="navbar" id="navbar">
        <div class="left-content" style="display: flex; align-items: center;">
            <img src="/gambar/Logo.png" alt="logo" style="height: 50px;">
            <div class="title">Retribusi Online</div>
        </div>
        <div class="right-content">
            <a href="/login" class="login-btn">Login</a>
        </div>
    </nav>

    <header class="hero">
        <div class="hero-text">
            <h3>Selamat Datang di</h3>
            <h1>Retribusi <br> Online Sistem</h1>
            <p>Dishub Kab. Malang</p>
        </div>
        <div class="hero-image">
            <img src="/gambar/dashb.jpg" alt="gambar">
        </div>
    </header>
    <x-waves></x-waves>
    <section class="how-it-works">
        <h2>Bagaimana Cara Menggunakan?</h2>
        <div class="steps">
            <div class="step-card">
                <h4>1. Login</h4>
                <p>Masuk menggunakan akun yang terdaftar, menggunakan Email dan Password.</p>
            </div>
            <div class="step-card">
                <h4>2. Cek Tagihan</h4>
                <p>Lihat daftar tagihan yang tersedia.</p>
            </div>
            <div class="step-card">
                <h4>3. Bayar Online</h4>
                <p>Klik tombol Bayar maka Link pembayaran akan dikirim melalui WhatsApp, Selanjutnya Pilih metode pembayaran dan lakukan transaksi.</p>
            </div>
            <div class="step-card">
                <h4>4. Dapat Struk</h4>
                <p>Struk dan notifikasi akan dikirim secara otomatis setelah melakukan pembayaran.</p>
            </div>
        </div>
    </section>
    <footer>
        <p>&copy; 2025 Dinas Perhubungan Kabupaten Malang</p>
        <p>Email: dishub@malangkab.go.id | Telp: 03413901300</p>
    </footer>

    
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
