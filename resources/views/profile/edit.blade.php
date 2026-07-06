@extends('layouts.classroom')

@section('title', 'Setelan Profil')

@push('css')
<style>
.profile-section {
    background: white;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 1px 2px 0 rgba(60,64,67,0.3), 0 1px 3px 1px rgba(60,64,67,0.15);
}
.profile-title {
    font-size: 20px;
    font-weight: 500;
    color: #1f1f1f;
    margin-bottom: 8px;
}
.profile-desc {
    font-size: 14px;
    color: #5f6368;
    margin-bottom: 24px;
}
</style>
@endpush

@section('content')
<div class="container-fluid py-4" style="max-width: 800px; margin: 0 auto;">
    
    <div class="mb-4">
        <h2 style="font-size: 28px; font-weight: 400; color: #202124;">Setelan Profil</h2>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Profil berhasil diperbarui.
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="profile-section">
        <h3 class="profile-title">Informasi Profil</h3>
        <p class="profile-desc">Perbarui informasi profil akun dan alamat email Anda.</p>
        
        @include('profile.partials.update-profile-information-form')
    </div>

    <div class="profile-section">
        <h3 class="profile-title">Perbarui Password</h3>
        <p class="profile-desc">Pastikan akun Anda menggunakan password acak yang panjang agar tetap aman.</p>
        
        @include('profile.partials.update-password-form')
    </div>

    <div class="profile-section">
        <h3 class="profile-title" style="color: #d93025;">Hapus Akun</h3>
        <p class="profile-desc">Setelah akun dihapus, semua data akan hilang permanen.</p>
        
        @include('profile.partials.delete-user-form')
    </div>

</div>
@endsection
