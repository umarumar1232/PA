<x-guest-layout>
    <div style="text-align: center; margin-bottom: 24px;">
        <h2 style="font-size: 24px; font-weight: 600; color: #202124; margin-bottom: 8px;">Daftar Akun Baru</h2>
        <p style="color: #5f6368; font-size: 14px;">Bergabung dengan E-Learning untuk mengakses kelas</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="auth-input-group">
            <label for="name" class="auth-label">Nama Lengkap</label>
            <input id="name" class="auth-input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Masukkan nama lengkap Anda">
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Email Address -->
        <div class="auth-input-group">
            <label for="email" class="auth-label">Email</label>
            <input id="email" class="auth-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="Masukkan alamat email">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Password -->
        <div class="auth-input-group">
            <label for="password" class="auth-label">Password</label>
            <input id="password" class="auth-input" type="password" name="password" required autocomplete="new-password" placeholder="Buat password (min. 8 karakter)">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Confirm Password -->
        <div class="auth-input-group" style="margin-bottom: 24px;">
            <label for="password_confirmation" class="auth-label">Konfirmasi Password</label>
            <input id="password_confirmation" class="auth-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-600 text-sm" />
        </div>

        <button type="submit" class="auth-btn-primary">
            Daftar Sekarang
        </button>
    </form>

    <div class="auth-divider">
        <span>ATAU</span>
    </div>

    <a href="{{ url('/auth/google') }}" class="auth-btn-google">
        <svg class="w-5 h-5" viewBox="0 0 48 48" style="width: 20px; height: 20px;">
            <path fill="#EA4335" d="M24 9.5c3.9 0 7.4 1.4 10.1 4.1l7.5-7.5C36.5 2.2 30.7 0 24 0 14.6 0 6.5 5.4 2.5 13.2l8.8 6.8C13.4 13.8 18.2 9.5 24 9.5z"/>
            <path fill="#34A853" d="M46.1 24.5c0-1.7-.1-3.4-.4-5H24v9.5h12.4c-.5 2.8-2 5.1-4.3 6.7l6.6 5.1c3.9-3.6 6.4-9 6.4-16.3z"/>
            <path fill="#4A90E2" d="M11.3 28c-1-2.8-1-5.8 0-8.6l-8.8-6.8C.9 16.1 0 20 0 24s.9 7.9 2.5 11.4l8.8-6.8z"/>
            <path fill="#FBBC05" d="M24 48c6.7 0 12.3-2.2 16.4-6l-6.6-5.1c-2 1.4-4.6 2.3-9.8 2.3-5.8 0-10.6-4.3-12.7-10.5l-8.8 6.8C6.5 42.6 14.6 48 24 48z"/>
        </svg>
        Daftar dengan Google
    </a>

    <div class="auth-footer">
        Sudah punya akun? <a href="{{ route('login') }}" class="auth-link">Masuk di sini</a>
    </div>
</x-guest-layout>
