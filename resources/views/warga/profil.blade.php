<x-layout>
    <div class="content-container pb-3">
        {{-- Card Profil --}}
        <div class="card custom-card mb-4">
            <div class="card-header custom-card-header">
                <h5 class="mb-0">Profil Warga</h5>
            </div>
            <div class="card-body">
                <p>Nama: {{ Auth::user()->nama }}</p>
                <hr>
                <p>Email: {{ Auth::user()->email }}</p>
                <hr>
                <p>No HP: {{ Auth::user()->no_hp }}</p>
                <hr>
                <p>Kategori Retribusi: {{ ucwords(str_replace('_', ' ', Auth::user()->warga->kategori_retribusi)) }}</p>
                <hr>
                <p>Jenis retribusi: {{ ucwords(str_replace('_', ' ', Auth::user()->warga->jenis_retribusi)) }}</p>
            </div>
        </div>

        {{-- Card Keamanan --}}
        <div class="card custom-card">
            <div class="card-header custom-card-header">
                <h5 class="mb-0">Keamanan Akun</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <form method="POST" action="{{ route('warga.updatePassword') }}">
                    @csrf
                    <label for="password_lama" class="form-label">Password Lama</label>
                    <div class="input-group mb-3">
                        <input type="password" id="password_lama" name="password_lama" class="form-control" required>
                        <span class="input-group-text" onclick="togglePassword('password_lama', 'togglePasswordIcon1')"
                            style="cursor: pointer;">
                            <span id="togglePasswordIcon1" class="material-symbols-rounded">visibility</span>
                        </span>
                    </div>
                    @error('password_lama')
                        <div class="text-danger mb-2">{{ $message }}</div>
                    @enderror

                    <label for="password_baru" class="form-label">Password Baru</label>
                    <div class="input-group mb-3">
                        <input type="password" id="password_baru" name="password_baru" class="form-control" required>
                        <span class="input-group-text" onclick="togglePassword('password_baru', 'togglePasswordIcon2')"
                            style="cursor: pointer;">
                            <span id="togglePasswordIcon2" class="material-symbols-rounded">visibility</span>
                        </span>
                    </div>
                    @error('password_baru')
                        <div class="text-danger mb-2">{{ $message }}</div>
                    @enderror

                    <label for="password_baru_confirmation" class="form-label">Konfirmasi Password Baru</label>
                    <div class="input-group mb-3">
                        <input type="password" id="password_baru_confirmation" name="password_baru_confirmation"
                            class="form-control" required>
                        <span class="input-group-text"
                            onclick="togglePassword('password_baru_confirmation', 'togglePasswordIcon3')"
                            style="cursor: pointer;">
                            <span id="togglePasswordIcon3" class="material-symbols-rounded">visibility</span>
                        </span>
                    </div>
                    <button type="submit" class="btn btn-primary">Ubah Password</button>
                </form>

            </div>
        </div>

    </div>
</x-layout>