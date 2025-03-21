<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retribusi Online</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    <nav class="navbar" id="navbar">
        <div class="left-content">
            <img src="/gambar/GEH.png" alt="logo">
            <div class="title">Retribusi Online</div>
        </div>
        <div class="right-content">
            <a href="#">
                <span class="contact">Contact us</span>
            </a>
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
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
