@extends('layouts.classroom')

@section('title', 'Beranda')

@section('content')
<div class="container-fluid py-4">
    <h4 class="mb-4 font-weight-normal text-muted">Mata Kuliah yang Diikuti</h4>

    <div class="gc-course-grid">
        @forelse($mataKuliahs as $index => $mk)
            @php
                $colors = [
                    ['bg' => '#1a73e8', 'text' => '#fff'],
                    ['bg' => '#1e8e3e', 'text' => '#fff'],
                    ['bg' => '#e37400', 'text' => '#fff'],
                    ['bg' => '#a142f4', 'text' => '#fff'],
                    ['bg' => '#ea4335', 'text' => '#fff'],
                    ['bg' => '#137333', 'text' => '#fff'],
                    ['bg' => '#c5221f', 'text' => '#fff'],
                    ['bg' => '#185abc', 'text' => '#fff'],
                ];
                $c = $colors[$index % count($colors)];
                $inisial = strtoupper(substr($mk->nama_mk, 0, 2));
            @endphp
            <a href="{{ route('mahasiswa.kelas.show', $mk->id) }}" class="gc-course-card" style="text-decoration:none; color:inherit;">
                <div class="gc-card-header" style="background-color: {{ $c['bg'] }}; color: {{ $c['text'] }}; position: relative; height: 100px; padding: 16px;">
                    <div style="font-family: 'Google Sans', sans-serif; font-size: 20px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 75%;">
                        {{ $mk->nama_mk }}
                    </div>
                    <div style="font-size: 13px; opacity: 0.9; margin-top: 4px;">
                        {{ $mk->kode_mk }} &bull; Semester {{ $mk->semester }}
                    </div>
                    <!-- Avatar inisial -->
                    <div style="position: absolute; right: 16px; bottom: -28px; width: 56px; height: 56px; border-radius: 50%; background: white; color: {{ $c['bg'] }}; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 20px; border: 2px solid #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.2); z-index: 1;">
                        {{ $inisial }}
                    </div>
                </div>
                <div class="gc-card-body" style="padding: 40px 16px 8px 16px; min-height: 80px;">
                    <p class="text-muted small mb-0">
                        {{ $mk->materials->count() }} pertemuan tersedia
                    </p>
                </div>
                <div class="gc-card-footer" style="border-top: 1px solid #e0e0e0; padding: 4px 8px; display: flex; justify-content: flex-end; align-items: center;">
                    <button class="gc-icon-btn" title="Buka Materi" onclick="event.preventDefault(); window.location='{{ route('mahasiswa.kelas.show', $mk->id) }}'">
                        <i class="fas fa-folder-open" style="font-size: 18px; color: #5f6368;"></i>
                    </button>
                </div>
            </a>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="fas fa-chalkboard fa-3x mb-3 d-block"></i>
                Belum ada mata kuliah yang tersedia.
            </div>
        @endforelse
    </div>
</div>
@endsection