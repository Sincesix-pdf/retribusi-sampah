<x-layout>
    <div class="content-container pb-3">
        <div class="card custom-card">
            <div class="card-header custom-card-header">
                <h5 class="mb-0">Dashboard Warga</h5>
            </div>
            <div class="card-body">
                <p>Halo {{ $nama }}</p>
                <hr>
                <p>Anda login sebagai Retribusi {{ ucwords(str_replace('_', ' ', $warga)) }}</p>
            </div>
        </div>
    </div>
</x-layout>
