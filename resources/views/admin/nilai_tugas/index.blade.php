@extends('layouts.classroom')

@section('title', 'Penilaian Tugas')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="font-weight-normal text-muted mb-0">Penilaian Tugas Mahasiswa</h4>
    </div>

    <div class="gc-course-grid">
        @forelse($assignments as $index => $assignment)
            @php
                $colors = ['#1a73e8', '#1e8e3e', '#fbbc04', '#ea4335', '#8e24aa', '#e91e63'];
                $color = $colors[$index % count($colors)];
            @endphp
            <div class="gc-course-card">
                <div class="gc-card-header" style="background-color: {{ $color }}; {{ $color == '#fbbc04' ? 'color: #3c4043;' : '' }}">
                    <h2 class="gc-card-title">{{ $assignment->title }}</h2>
                    <div class="gc-card-subtitle">
                        Tenggat: {{ $assignment->deadline ? \Carbon\Carbon::parse($assignment->deadline)->format('d M Y') : 'Tanpa batas' }}
                    </div>
                    <div class="gc-card-avatar d-flex align-items-center justify-content-center bg-light text-dark" style="font-size: 24px;">
                        <i class="fas fa-clipboard-check" style="color: {{ $color }};"></i>
                    </div>
                </div>
                <div class="gc-card-body d-flex flex-column justify-content-center">
                    <p class="text-muted small mb-0">{{ Str::limit($assignment->description, 80) }}</p>
                </div>
                <div class="gc-card-footer">
                    <a href="{{ route('admin.nilai_tugas.show', $assignment->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 20px;">
                        Lihat Nilai
                    </a>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info" style="border-radius: 8px;">
                    Belum ada tugas yang diberikan.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection