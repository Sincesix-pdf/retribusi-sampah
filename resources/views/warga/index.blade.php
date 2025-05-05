<x-layout>
    <div class="content-container">
        <h1>Halaman Dashboard</h1>
        <p></p>
        <!-- <p>Ini halaman dashboard.</p> -->
        <p>Halo {{ $nama }} <p> Anda login sebagai Retribusi {{ ucwords(str_replace('_', ' ', $warga)) }}</p>
    </div>
</x-layout>