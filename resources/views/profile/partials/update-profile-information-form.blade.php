<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" style="max-width: 500px;">
    @csrf
    @method('patch')

    <div class="form-group mb-4">
        <label for="nama" class="font-weight-medium text-dark" style="font-size: 14px;">Nama Lengkap</label>
        <input id="nama" name="nama" type="text" class="form-control" value="{{ old('nama', $user->nama) }}" required autofocus autocomplete="name" style="border-radius: 8px; font-size: 14px;">
        <x-input-error class="mt-2 text-danger small" :messages="$errors->get('nama')" />
    </div>

    @if($user->role === 'mahasiswa')
    <div class="form-group mb-4">
        <label for="nim" class="font-weight-medium text-dark" style="font-size: 14px;">Nomor Induk Mahasiswa (NIM)</label>
        <input id="nim" name="nim" type="text" class="form-control" value="{{ old('nim', $user->mahasiswa->nim ?? '') }}" placeholder="Masukkan NIM Anda" autocomplete="off" style="border-radius: 8px; font-size: 14px;">
        <x-input-error class="mt-2 text-danger small" :messages="$errors->get('nim')" />
    </div>
    @endif

    <div class="form-group mb-4">
        <label for="email" class="font-weight-medium text-dark" style="font-size: 14px;">Email</label>
        <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username" style="border-radius: 8px; font-size: 14px;">
        <x-input-error class="mt-2 text-danger small" :messages="$errors->get('email')" />

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-2">
                <p class="text-sm text-dark">
                    Email Anda belum diverifikasi.
                    <button form="send-verification" class="btn btn-link p-0 text-primary" style="font-size: 14px;">
                        Klik di sini untuk mengirim ulang email verifikasi.
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 font-weight-medium text-success" style="font-size: 13px;">
                        Tautan verifikasi baru telah dikirim ke alamat email Anda.
                    </p>
                @endif
            </div>
        @endif
    </div>

    <div class="d-flex align-items-center">
        <button type="submit" class="btn btn-primary px-4" style="border-radius: 20px; font-size: 14px; font-weight: 500;">
            Simpan Perubahan
        </button>
    </div>
</form>
