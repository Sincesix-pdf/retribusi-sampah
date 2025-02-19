<x-layout>
    <div class="content-container">
        <h1>Halaman Dashboard</h1>
        <p>Ini halaman dashboard.</p>
        <p>Anda login sebagai  {{ ucwords(str_replace('_', ' ', $warga)) }}</p>
    </div>
</x-layout>