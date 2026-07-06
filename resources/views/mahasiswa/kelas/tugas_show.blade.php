@extends('layouts.classroom')

@section('title', $tugas->title)

@push('css')
<style>
.detail-page { max-width: 1000px; margin: 0 auto; padding: 24px 16px; display: flex; gap: 24px; flex-wrap: wrap; }
.main-content { flex: 1; min-width: 60%; }
.side-content { width: 300px; flex-shrink: 0; }
@media (max-width: 768px) {
    .side-content { width: 100%; }
}
.detail-header { display: flex; align-items: flex-start; gap: 20px; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid #e0e0e0; }
.detail-icon { width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0; }
.detail-icon-task { background: #e8f0fe; color: #1a73e8; }
.detail-icon-quiz { background: #fef7e0; color: #f29900; }
.detail-title { font-size: 26px; font-weight: 600; color: #1f1f1f; margin-bottom: 6px; line-height: 1.3; }
.detail-meta { font-size: 13px; color: #80868b; }
.detail-score { font-size: 14px; font-weight: 500; color: #3c4043; margin-top: 8px; }
.detail-body { font-size: 15px; color: #3c4043; line-height: 1.7; margin-bottom: 24px; }
.attachment-chip { display: inline-flex; align-items: center; gap: 10px; border: 1px solid #e0e0e0; border-radius: 8px; padding: 10px 16px; text-decoration: none; color: #3c4043; background: #f8f9fa; font-size: 13px; margin-right: 8px; margin-bottom: 8px; transition: background 0.15s; }
.attachment-chip:hover { background: #e8f0fe; text-decoration: none; }
.comment-box { background: white; border-radius: 12px; border: 1px solid #e0e0e0; padding: 16px; }
.comment-input { width: 100%; border: none; outline: none; font-size: 14px; color: #3c4043; resize: none; background: transparent; }
.comment-divider { border: none; border-bottom: 1px solid #e0e0e0; margin: 8px 0; }
.breadcrumb-link { color: #1a73e8; text-decoration: none; font-size: 13px; }
.breadcrumb-link:hover { text-decoration: underline; }
.submission-card { background: white; border-radius: 8px; border: 1px solid #e0e0e0; padding: 20px; box-shadow: 0 1px 2px 0 rgba(60,64,67,0.3), 0 1px 3px 1px rgba(60,64,67,0.15); }
.status-text { font-size: 15px; font-weight: 500; }
.status-missing { color: #d93025; }
.status-done { color: #1e8e3e; }
.status-assigned { color: #1a73e8; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4" style="max-width: 1000px; margin: 0 auto;">
    
    {{-- Breadcrumb --}}
    <div class="mb-4">
        <a href="{{ route('mahasiswa.kelas.show', $mataKuliah->id) }}" class="breadcrumb-link">
            <i class="fas fa-arrow-left mr-2"></i>{{ $mataKuliah->nama_mk }}
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div style="display: flex; gap: 24px; flex-wrap: wrap;">
        {{-- Sisi Kiri: Deskripsi Tugas --}}
        <div style="flex: 1; min-width: 60%;">
            <div class="detail-header">
                <div class="detail-icon {{ $tugas->type === 'quiz' ? 'detail-icon-quiz' : 'detail-icon-task' }}">
                    <i class="{{ $tugas->type === 'quiz' ? 'fas fa-question-circle' : 'fas fa-clipboard-list' }}"></i>
                </div>
                <div style="flex:1; min-width:0;">
                    <div class="detail-title">{{ $tugas->title }}</div>
                    <div class="detail-meta">
                        @php $teacher = $activeTeachers->first(); @endphp
                        {{ $teacher ? $teacher->nama : 'Pengajar' }}
                        &nbsp;&bull;&nbsp;
                        {{ \Carbon\Carbon::parse($tugas->created_at)->isoFormat('D MMMM YYYY') }}
                    </div>
                    <div class="detail-score d-flex justify-content-between align-items-center">
                        <div>100 poin</div>
                        @if($tugas->deadline)
                            <div style="color: #5f6368; font-weight: 400;">Tenggat: {{ \Carbon\Carbon::parse($tugas->deadline)->isoFormat('D MMM YYYY, HH:mm') }}</div>
                        @endif
                    </div>
                </div>
                @if($isTeacher)
                <div class="dropdown">
                    <button class="btn btn-link text-muted p-0" data-toggle="dropdown"
                            style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;border-radius:50%;">
                        <i class="fas fa-ellipsis-v" style="font-size:18px;color:#5f6368;"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right shadow-sm" style="border-radius:8px;min-width:160px;padding:4px 0;">
                        <a class="dropdown-item py-2" href="#" style="font-size:14px;">
                            <i class="fas fa-edit mr-2 text-warning"></i>Edit {{ $tugas->type === 'quiz' ? 'Kuis' : 'Tugas' }}
                        </a>
                        <div class="dropdown-divider my-1"></div>
                        <form action="{{ route('admin.assignments.destroy', $tugas->id) }}" method="POST"
                              onsubmit="return confirm('Hapus {{ $tugas->type === 'quiz' ? 'kuis' : 'tugas' }} ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="dropdown-item py-2 text-danger" style="font-size:14px;">
                                <i class="fas fa-trash-alt mr-2"></i>Hapus
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>

            {{-- Description --}}
            @if($tugas->description)
            <div class="detail-body mb-4">
                {!! nl2br(e($tugas->description)) !!}
            </div>
            @endif

            {{-- Attachments --}}
            @if($tugas->file || $tugas->notebook_url)
            <div class="mb-5">
                <div style="font-size:12px;font-weight:600;color:#80868b;text-transform:uppercase;letter-spacing:.8px;margin-bottom:12px;">
                    Lampiran
                </div>
                @if($tugas->file)
                    <a href="{{ asset('storage/'.$tugas->file) }}" target="_blank" class="attachment-chip">
                        <i class="fas fa-file-alt" style="color:#e53935;font-size:20px;"></i>
                        <span>{{ basename($tugas->file) }}</span>
                        <i class="fas fa-external-link-alt" style="font-size:11px;color:#9aa0a6;"></i>
                    </a>
                @endif
                @if($tugas->notebook_url)
                    @php
                        $colabUrl = $tugas->notebook_url;
                        if (str_contains($tugas->notebook_url, 'drive.google.com')) {
                            preg_match('/[-\w]{25,}/', $tugas->notebook_url, $matches);
                            if (!empty($matches[0])) {
                                $colabUrl = 'https://colab.research.google.com/drive/' . $matches[0];
                            }
                        }
                    @endphp
                    <a href="{{ $colabUrl }}" target="_blank" class="attachment-chip" style="background-color: #fff3e0; border-color: #ffe0b2;">
                        <img src="https://colab.research.google.com/img/colab_favicon_256px.png" style="width:20px;height:20px;border-radius:50%;" alt="Colab">
                        <span style="font-weight: 500; color: #e65100;">Kerjakan di Google Colab</span>
                        <i class="fas fa-external-link-alt" style="font-size:11px;color:#fb8c00;"></i>
                    </a>
                @endif
            </div>
            @endif

            {{-- Komentar Kelas --}}
            <div style="font-size:12px;font-weight:600;color:#80868b;text-transform:uppercase;letter-spacing:.8px;margin-bottom:12px;">
                Komentar kelas
            </div>
            <div class="comment-box d-flex mb-4" style="gap:12px;">
                <img src="{{ Auth::user()->foto ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->nama).'&color=1a73e8&background=e8f0fe' }}"
                     style="width:32px;height:32px;border-radius:50%;object-fit:cover;flex-shrink:0;margin-top:2px;" alt="">
                <div style="flex:1;">
                    <textarea class="comment-input" rows="1" placeholder="Tambahkan komentar kelas..."></textarea>
                    <div class="comment-divider"></div>
                    <div class="d-flex justify-content-end mt-2">
                        <button class="btn btn-sm" style="color:#1a73e8;font-weight:500;font-size:13px;">
                            <i class="fas fa-paper-plane mr-1"></i>Kirim
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sisi Kanan: Status Pengumpulan & Panel Dosen --}}
        <div style="width: 300px; flex-shrink: 0;">
            @if($isTeacher)
                {{-- Panel Pengajar --}}
                <div class="submission-card">
                    <h5 class="mb-4">Status Pengumpulan</h5>
                    <div class="d-flex justify-content-around text-center mb-4">
                        <div>
                            <div style="font-size: 32px; font-weight: 300;">{{ $allSubmissions->whereNotNull('file')->count() }}</div>
                            <div class="small text-muted">Diserahkan</div>
                        </div>
                        <div style="width: 1px; background: #e0e0e0;"></div>
                        <div>
                            <div style="font-size: 32px; font-weight: 300;">{{ $activeStudents->count() - $allSubmissions->whereNotNull('file')->count() }}</div>
                            <div class="small text-muted">Diberikan</div>
                        </div>
                    </div>
                    <a href="{{ route('admin.tugas_mahasiswa.show', $tugas->id) }}" class="btn btn-primary w-100" style="border-radius: 4px;">
                        Buka Nilai
                    </a>
                </div>
            @else
                {{-- Panel Mahasiswa --}}
                <div class="submission-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0" style="font-size: 20px;">Tugas Anda</h5>
                        @php
                            $isDone = $submission && $submission->file;
                            $isLate = $tugas->deadline && \Carbon\Carbon::parse($tugas->deadline)->isPast();
                        @endphp
                        @if($isDone)
                            <span class="status-text status-done">Diserahkan {{ $isLate ? 'terlambat' : '' }}</span>
                        @elseif($isLate)
                            <span class="status-text status-missing">Tidak ada (Terlambat)</span>
                        @else
                            <span class="status-text status-assigned">Ditugaskan</span>
                        @endif
                    </div>
                    
                    @if($isDone)
                        {{-- File yang sudah dikumpulkan --}}
                        <div class="mb-3">
                            <a href="{{ asset('storage/'.$submission->file) }}" target="_blank" class="attachment-chip w-100 mb-2 justify-content-between">
                                <div class="d-flex align-items-center gap-2" style="overflow: hidden;">
                                    <i class="fas fa-file-pdf text-danger" style="font-size:20px;"></i>
                                    <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ basename($submission->file) }}</span>
                                </div>
                                <i class="fas fa-external-link-alt text-muted small"></i>
                            </a>
                            @if($submission->score !== null)
                                <div class="mt-3 p-3 bg-light rounded text-center">
                                    <div class="small text-muted mb-1">Nilai Anda</div>
                                    <div style="font-size: 24px; font-weight: bold; color: #1a73e8;">{{ $submission->score }} / 100</div>
                                </div>
                            @endif
                        </div>
                        
                        <form action="{{ route('mahasiswa.kelas.tugas.submit', [$mataKuliah->id, $tugas->id]) }}" method="POST">
                            @csrf
                            <button type="submit" name="unsubmit" value="1" class="btn btn-outline-secondary w-100" style="border-radius: 4px; font-weight: 500;">
                                Batalkan pengiriman
                            </button>
                        </form>
                    @else
                        {{-- Form Pengumpulan --}}
                        @if($tugas->notebook_url)
                        <div class="alert alert-info p-3 mb-3" style="font-size: 13px; border-radius: 8px;">
                            <strong>Tugas Colab:</strong> Setelah selesai mengerjakan di Colab, pilih menu <strong>File > Print > Save as PDF</strong>, lalu kumpulkan file PDF tersebut di bawah ini.
                        </div>
                        @endif

                        <form action="{{ route('mahasiswa.kelas.tugas.submit', [$mataKuliah->id, $tugas->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <input type="file" name="file" id="submissionFile" class="d-none" required onchange="document.getElementById('fileName').innerText = this.files[0].name">
                                <button type="button" class="btn btn-outline-primary w-100 mb-3" style="border-radius: 4px; font-weight: 500;" onclick="document.getElementById('submissionFile').click()">
                                    <i class="fas fa-plus mr-2"></i> Tambah atau buat
                                </button>
                                <div id="fileName" class="text-center small text-muted mb-3" style="word-break: break-all;"></div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100" style="border-radius: 4px; font-weight: 500;">
                                Serahkan
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
