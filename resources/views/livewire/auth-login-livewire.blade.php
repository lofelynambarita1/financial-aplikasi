<form wire:submit.prevent="login">
    <div class="auth-card mx-auto">
        <div class="auth-header">
            <div class="logo-container">
                <img src="/logo.png" alt="Logo">
            </div>
            <h2 class="auth-title">Selamat Datang</h2>
        </div>
        
        <div class="auth-body">
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
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                </button>
            </div>

            <div class="divider"></div>

            <p class="text-center mb-0">
                <span class="text-muted">Belum memiliki akun?</span>
                <a href="{{ route('auth.register') }}" class="auth-link ms-1">Daftar Sekarang</a>
            </p>
        </div>
    </div>
</form>