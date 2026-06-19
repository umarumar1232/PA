@extends('layouts.classroom')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid py-4">
    <h4 class="mb-4 font-weight-normal text-muted">Statistik E-Learning</h4>

    <div class="gc-course-grid">
        <!-- Card 1 -->
        <div class="gc-course-card">
            <div class="gc-card-header" style="background-color: #1a73e8;">
                <h2 class="gc-card-title">Total Pengguna</h2>
                <div class="gc-card-subtitle">Semua Role</div>
                <div class="gc-card-avatar d-flex align-items-center justify-content-center bg-light text-primary" style="font-size: 24px;">
                    {{ $jumlah_user }}
                </div>
            </div>
            <div class="gc-card-body d-flex flex-column justify-content-center">
                <p class="text-muted mb-1"><i class="fas fa-user-graduate mr-2"></i> Mahasiswa: <strong>{{ $jumlah_siswa }}</strong></p>
                <p class="text-muted mb-0"><i class="fas fa-chalkboard-teacher mr-2"></i> Dosen/Admin: <strong>{{ $jumlah_guru }}</strong></p>
            </div>
            <div class="gc-card-footer">
                <a href="{{ route('admin.users.index') }}" class="gc-icon-btn" title="Kelola Pengguna">
                    <i class="fas fa-users"></i>
                </a>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="gc-course-card">
            <div class="gc-card-header" style="background-color: #1e8e3e;">
                <h2 class="gc-card-title">Materi & Kelas</h2>
                <div class="gc-card-subtitle">Total Pembelajaran</div>
                <div class="gc-card-avatar d-flex align-items-center justify-content-center bg-light text-success" style="font-size: 24px;">
                    {{ $jumlah_kelas }}
                </div>
            </div>
            <div class="gc-card-body d-flex flex-column justify-content-center">
                <p class="text-muted mb-0">Total materi yang tersedia di seluruh kelas aktif.</p>
            </div>
            <div class="gc-card-footer">
                <a href="{{ route('admin.materials.index') }}" class="gc-icon-btn" title="Kelola Materi">
                    <i class="fas fa-book"></i>
                </a>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="gc-course-card">
            <div class="gc-card-header" style="background-color: #fbbc04; color: #3c4043;">
                <h2 class="gc-card-title">Tugas & Assignment</h2>
                <div class="gc-card-subtitle">Menunggu Penilaian</div>
                <div class="gc-card-avatar d-flex align-items-center justify-content-center bg-light text-warning" style="font-size: 24px;">
                    {{ $jumlah_mapel ?? 0 }}
                </div>
            </div>
            <div class="gc-card-body d-flex flex-column justify-content-center">
                <p class="text-muted mb-0">Total tugas yang diberikan kepada mahasiswa.</p>
            </div>
            <div class="gc-card-footer">
                <a href="{{ route('admin.assignments.index') }}" class="gc-icon-btn" title="Kelola Tugas">
                    <i class="fas fa-tasks"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection