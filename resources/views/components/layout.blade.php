<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0">
</head>

<body>
    <div class="main-container">
        <x-navbar></x-navbar> <!-- Sidebar tetap di posisi kiri -->

        <!-- Konten utama -->
        <main class="content">
            {{ $slot }}
        </main>
    </div>

    <script src="{{ asset('js/sidebar.js') }}"></script>
</body>

</html>