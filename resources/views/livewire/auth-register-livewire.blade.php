<form wire:submit.prevent="register">
    <div class="auth-card mx-auto">
        <div class="auth-header">
            <div class="logo-container">
                <img src="/logo.png" alt="Logo">
            </div>
            <h2 class="auth-title">Daftar Akun</h2>
        </div>
        
        <div class="auth-body">
            {{-- Nama --}}
            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-person-fill me-2"></i>Nama Lengkap
                </label>
                <i class="bi bi-person input-icon"></i>
                <input type="text" class="form-control with-icon" wire:model="name" placeholder="Masukkan nama lengkap">
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- Alamat Email --}}
            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-envelope-fill me-2"></i>Alamat Email
                </label>
                <i class="bi bi-envelope input-icon"></i>
                <input type="email" class="form-control with-icon" wire:model="email" placeholder="nama@email.com">
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- Kata Sandi --}}
            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-lock-fill me-2"></i>Kata Sandi
                </label>
                <i class="bi bi-lock input-icon"></i>
                <input type="password" class="form-control with-icon" wire:model="password" placeholder="••••••••">
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- Kirim --}}
            <div class="form-group mt-4 mb-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-person-plus-fill me-2"></i>Daftar
                </button>
            </div>

            <div class="divider"></div>

            {{-- Link ke Login --}}
            <p class="text-center mb-0">
                <span class="text-muted">Sudah memiliki akun?</span>
                <a href="{{ route('auth.login') }}" class="auth-link ms-1">Masuk Sekarang</a>
            </p>
        </div>
    </div>
</form>