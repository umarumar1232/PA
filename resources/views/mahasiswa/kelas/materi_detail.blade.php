@extends('layouts.classroom')

@section('title', $material->title)

@push('css')
<style>
.material-detail-card {
    background: white;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    padding: 24px;
    margin-bottom: 16px;
}

.material-header {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 16px;
}

.material-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #e8f0fe;
    color: #1a73e8;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}

.material-title {
    font-size: 32px;
    font-weight: 400;
    line-height: 40px;
    margin: 0 0 4px 0;
}

.material-meta {
    font-size: 14px;
    color: #5f6368;
    display: flex;
    align-items: center;
    gap: 8px;
}

.section-header {
    font-size: 14px;
    font-weight: 500;
    color: #3c4043;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.comment-section {
    border-top: 1px solid #e0e0e0;
    padding-top: 16px;
    margin-top: 16px;
}

.add-comment-btn {
    background: transparent;
    border: none;
    color: #1a73e8;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    padding: 8px 16px;
    border-radius: 4px;
    transition: background 0.2s;
}

.add-comment-btn:hover {
    background: #e8f0fe;
}

.attachment-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 12px;
    border: 1px solid #e0e0e0;
}

.attachment-file {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: white;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
}

.file-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: #e8f0fe;
    color: #1a73e8;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.description-box {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 16px;
    border: 1px solid #e0e0e0;
}

.description-text {
    font-size: 14px;
    color: #3c4043;
    line-height: 1.6;
    white-space: pre-wrap;
}
</style>
@endpush

@section('content')
<div style="margin: 0; padding: 0;">
    {{-- ===== MAIN CONTENT ===== --}}
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                {{-- Material Card - Stretched Layout --}}
                <div class="material-detail-card">
                    <div class="row">
                        {{-- Left: Material Details --}}
                        <div class="col-md-8">
                            <div class="material-header">
                                <div class="material-icon">
                                    <i class="fas fa-bookmark"></i>
                                </div>
                                <div style="flex: 1;">
                                    <h2 class="material-title">{{ $material->title }}</h2>
                                    <div class="material-meta">
                                        <span>{{ $activeTeachers->first()->nama ?? 'Pengajar' }}</span>
                                        <span style="margin: 0 8px;">&bull;</span>
                                        <span>{{ \Carbon\Carbon::parse($material->created_at)->isoFormat('D MMM YYYY') }}</span>
                                    </div>
                                    @if($material->category)
                                        <div style="font-size: 14px; color: #5f6368; margin-top: 4px;">
                                            {{ $material->category->name }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Description --}}
                            @if($material->description)
                                <div class="description-box">
                                    <div class="description-text">{{ $material->description }}</div>
                                </div>
                            @endif

                            {{-- Video URL --}}
                            @if($material->video_url)
                                <div style="margin-bottom: 16px;">
                                    <div style="font-size: 13px; color: #5f6368; margin-bottom: 8px;">
                                        <i class="fas fa-video mr-1"></i>
                                        <strong>Video:</strong>
                                    </div>
                                    <a href="{{ $material->video_url }}" target="_blank" style="color: #1a73e8; text-decoration: none; font-size: 14px; font-weight: 500;">
                                        {{ $material->video_url }}
                                    </a>
                                </div>
                            @endif

                            {{-- Attachments --}}
                            @if($material->file)
                                <div style="margin-bottom: 16px;">
                                    <div style="font-size: 14px; font-weight: 500; color: #3c4043; margin-bottom: 8px;">
                                        Lampiran
                                    </div>
                                    <div class="attachment-file">
                                        <div class="file-icon">
                                            <i class="fas fa-file"></i>
                                        </div>
                                        <div style="flex: 1;">
                                            <div style="font-size: 14px; font-weight: 500; color: #3c4043;">
                                                {{ basename($material->file) }}
                                            </div>
                                        </div>
                                        <a href="{{ asset('storage/' . $material->file) }}" target="_blank" style="color: #1a73e8; text-decoration: none; font-size: 13px; font-weight: 500;">
                                            Buka
                                        </a>
                                    </div>
                                </div>
                            @endif

                            {{-- Class Comments Section --}}
                            <div class="comment-section">
                                <div style="font-size: 14px; font-weight: 500; color: #3c4043; margin-bottom: 8px;">
                                    Komentar kelas
                                </div>
                                <button style="background: transparent; border: none; color: #1a73e8; font-size: 14px; font-weight: 500; cursor: pointer; padding: 8px 16px; border-radius: 4px; transition: background 0.2s;">
                                    Tambahkan komentar kelas
                                </button>
                            </div>
                        </div>

                        {{-- Right: Assignments List --}}
                        <div class="col-md-4">
                            <div style="font-size: 14px; font-weight: 500; color: #3c4043; margin-bottom: 12px;">
                                Tugas dalam materi ini
                            </div>

                            @if($material->assignments->isNotEmpty())
                                @foreach($material->assignments as $tugas)
                                    @php
                                        $submission = $submissions[$tugas->id] ?? null;
                                        $isDone = $submission && $submission->file;
                                    @endphp
                                    <a href="{{ route('mahasiswa.kelas.tugas.show', [$mataKuliah->id, $tugas->id]) }}" style="text-decoration: none; color: inherit; display: block; margin-bottom: 12px;">
                                        <div style="background: #f8f9fa; border-radius: 8px; padding: 12px; border: 1px solid #e0e0e0;">
                                            <div style="display: flex; align-items: center; gap: 12px;">
                                                <div style="width: 32px; height: 32px; border-radius: 50%; background: {{ $isDone ? '#e8f5e9' : '#e8f0fe' }}; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-clipboard-list" style="color: {{ $isDone ? '#1e8e3e' : '#1a73e8' }}; font-size: 14px;"></i>
                                                </div>
                                                <div style="flex: 1;">
                                                    <div style="font-size: 14px; font-weight: 500; color: #3c4043;">{{ $tugas->title }}</div>
                                                    @if($tugas->deadline)
                                                        <div style="font-size: 12px; color: #5f6368;">
                                                            <i class="fas fa-clock mr-1"></i>{{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div style="margin-top: 8px; font-size: 12px; color: {{ $isDone ? '#1e8e3e' : '#ea4335' }}; font-weight: 500;">
                                                @if($isDone)
                                                    <i class="fas fa-check-circle mr-1"></i> Diserahkan
                                                    @if($submission->score !== null)
                                                        &bull; Nilai: {{ $submission->score }}
                                                    @endif
                                                @else
                                                    <i class="fas fa-exclamation-circle mr-1"></i> Belum dikumpulkan
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            @else
                                <p class="text-muted small" style="font-size: 13px; color: #5f6368;">Belum ada tugas dalam materi ini.</p>
                            @endif

                            {{-- Private Comments Section --}}
                            <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid #e0e0e0;">
                                <div style="font-size: 14px; font-weight: 500; color: #3c4043; margin-bottom: 8px;">
                                    Komentar pribadi
                                </div>
                                <button style="background: transparent; border: none; color: #1a73e8; font-size: 14px; font-weight: 500; cursor: pointer; padding: 8px 16px; border-radius: 4px; transition: background 0.2s;">
                                    Tambahkan komentar untuk {{ Auth::user()->nama }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
