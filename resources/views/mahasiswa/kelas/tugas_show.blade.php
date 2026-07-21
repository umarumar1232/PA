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

            @php
                $isGoogleForm = $tugas->notebook_url && (str_contains($tugas->notebook_url, 'forms.gle') || str_contains($tugas->notebook_url, 'forms.google.com') || str_contains($tugas->notebook_url, 'docs.google.com/forms'));
            @endphp

            {{-- Attachments --}}
            @if($tugas->file || $isGoogleForm)
            <div class="mb-5">
                <div style="font-size:12px;font-weight:600;color:#80868b;text-transform:uppercase;letter-spacing:.8px;margin-bottom:12px;">
                    Lampiran
                </div>
                @if($tugas->file && is_array($tugas->file))
                    @foreach($tugas->file as $f)
                    <a href="{{ asset('storage/'.$f['path']) }}" target="_blank" class="attachment-chip">
                        <i class="fas fa-file-alt" style="color:#e53935;font-size:20px;"></i>
                        <span>{{ $f['name'] ?? basename($f['path']) }}</span>
                        <i class="fas fa-external-link-alt" style="font-size:11px;color:#9aa0a6;"></i>
                    </a>
                    @endforeach
                @elseif($tugas->file && is_string($tugas->file))
                    <a href="{{ asset('storage/'.$tugas->file) }}" target="_blank" class="attachment-chip">
                        <i class="fas fa-file-alt" style="color:#e53935;font-size:20px;"></i>
                        <span>{{ basename($tugas->file) }}</span>
                        <i class="fas fa-external-link-alt" style="font-size:11px;color:#9aa0a6;"></i>
                    </a>
                @endif
                
                @if($isGoogleForm)
                    <a href="{{ $tugas->notebook_url }}" target="_blank" class="attachment-chip" style="background-color: #f3e5f5; border-color: #e1bee7;">
                        <i class="fas fa-file-alt" style="color:#8e24aa;font-size:20px;"></i>
                        <span style="font-weight: 500; color: #6a1b9a;">Kerjakan Google Form (Kuis)</span>
                        <i class="fas fa-external-link-alt" style="font-size:11px;color:#ab47bc;"></i>
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
                    <form action="{{ route('mahasiswa.comment.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="assignment">
                        <input type="hidden" name="id" value="{{ $tugas->id }}">
                        <textarea name="body" class="comment-input" rows="1" placeholder="Tambahkan komentar kelas..." required></textarea>
                        <div class="comment-divider"></div>
                        <div class="d-flex justify-content-end mt-2">
                            <button type="submit" class="btn btn-sm" style="color:#1a73e8;font-weight:500;font-size:13px; border:none; background:none;">
                                <i class="fas fa-paper-plane mr-1"></i>Kirim
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- List Comments --}}
            @foreach($tugas->comments->sortByDesc('created_at') as $comment)
            <div class="d-flex mb-3" style="gap: 12px;">
                <img src="{{ $comment->user->foto ?? 'https://ui-avatars.com/api/?name='.urlencode($comment->user->nama).'&color=1a73e8&background=e8f0fe' }}"
                     style="width:32px;height:32px;border-radius:50%;object-fit:cover;flex-shrink:0;" alt="">
                <div>
                    <div style="font-size: 13px;">
                        <strong>{{ $comment->user->nama }}</strong>
                        <span class="text-muted ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <div style="font-size: 14px; color: #3c4043; margin-top: 2px;">
                        {!! nl2br(e($comment->body)) !!}
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Sisi Kanan: Status Pengumpulan & Panel Dosen --}}
        <div style="width: 300px; flex-shrink: 0;">
            @if($isTeacher)
                {{-- Panel Pengajar --}}
                <div class="submission-card">
                    <h5 class="mb-4">Status Pengumpulan</h5>
                    @php
                        $submittedCount = $allSubmissions->filter(function($sub) {
                            return !empty($sub->file) || !empty($sub->link);
                        })->count();
                        $assignedCount = max(0, $activeStudents->count() - $submittedCount);
                    @endphp
                    <div class="d-flex justify-content-around text-center mb-4">
                        <div>
                            <div style="font-size: 32px; font-weight: 300;">{{ $submittedCount }}</div>
                            <div class="small text-muted">Diserahkan</div>
                        </div>
                        <div style="width: 1px; background: #e0e0e0;"></div>
                        <div>
                            <div style="font-size: 32px; font-weight: 300;">{{ $assignedCount }}</div>
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
                            $isDone = $submission && ($submission->file || $submission->link);
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
                            @if($submission->file && is_array($submission->file))
                                @foreach($submission->file as $f)
                                <a href="{{ asset('storage/'.$f['path']) }}" target="_blank" class="attachment-chip w-100 mb-2 justify-content-between">
                                    <div class="d-flex align-items-center gap-2" style="overflow: hidden;">
                                        <i class="fas fa-file-pdf text-danger" style="font-size:20px;"></i>
                                        <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $f['name'] ?? basename($f['path']) }}</span>
                                    </div>
                                    <i class="fas fa-external-link-alt text-muted small"></i>
                                </a>
                                @endforeach
                            @elseif($submission->file && is_string($submission->file))
                                <a href="{{ asset('storage/'.$submission->file) }}" target="_blank" class="attachment-chip w-100 mb-2 justify-content-between">
                                    <div class="d-flex align-items-center gap-2" style="overflow: hidden;">
                                        <i class="fas fa-file-pdf text-danger" style="font-size:20px;"></i>
                                        <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ basename($submission->file) }}</span>
                                    </div>
                                    <i class="fas fa-external-link-alt text-muted small"></i>
                                </a>
                            @endif
                            @if($submission->link)
                            <a href="{{ $submission->link }}" target="_blank" class="attachment-chip w-100 mb-2 justify-content-between" style="background-color: #fff3e0; border-color: #ffe0b2;">
                                <div class="d-flex align-items-center gap-2" style="overflow: hidden;">
                                    <img src="https://colab.research.google.com/img/colab_favicon_256px.png" style="width:20px;height:20px;border-radius:50%;" alt="Colab">
                                    <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #e65100;">Tugas Colab / Drive</span>
                                </div>
                                <i class="fas fa-external-link-alt text-muted small"></i>
                            </a>
                            @endif

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
                            <strong>Tugas Colab:</strong> Anda bisa mengklik tombol di bawah untuk melampirkan file Colab (.ipynb) langsung dari Google Drive Anda.
                        </div>
                        @endif

                        <form action="{{ route('mahasiswa.kelas.tugas.submit', [$mataKuliah->id, $tugas->id]) }}" method="POST" enctype="multipart/form-data" id="submitTugasForm">
                            @csrf
                            <div class="mb-3">
                                <input type="file" name="file[]" multiple id="submissionFile" class="d-none" onchange="document.getElementById('fileName').innerText = this.files.length > 1 ? this.files.length + ' file terpilih' : (this.files.length === 1 ? this.files[0].name : ''); document.getElementById('linkName').innerText = ''; document.getElementById('submissionLink').value = '';">
                                <input type="hidden" name="link" id="submissionLink">
                                
                                <button type="button" class="btn btn-outline-primary w-100 mb-2" style="border-radius: 4px; font-weight: 500;" onclick="document.getElementById('submissionFile').click()">
                                    <i class="fas fa-upload mr-2"></i> Upload File Lokal
                                </button>
                                
                                <a href="https://colab.research.google.com/#create=true" target="_blank" class="btn w-100 mb-2" style="border-radius: 4px; font-weight: 500; background-color: #fff; border: 1px solid #dadce0; color: #3c4043; display: flex; justify-content: center; align-items: center;">
                                    <img src="https://colab.research.google.com/img/colab_favicon_256px.png" style="width: 18px; margin-right: 8px;" alt="Colab">
                                    Buat Colab Baru
                                </a>
                                
                                <button type="button" class="btn w-100 mb-3" style="border-radius: 4px; font-weight: 500; background-color: #fff; border: 1px solid #dadce0; color: #3c4043; display: flex; justify-content: center; align-items: center;" onclick="loadPicker()">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/1/12/Google_Drive_icon_%282020%29.svg" style="width: 18px; margin-right: 8px;" alt="Drive">
                                    Pilih dari Drive
                                </button>
                                
                                <div id="fileName" class="text-center small text-muted mb-2" style="word-break: break-all;"></div>
                                <div id="linkName" class="text-center small mb-2" style="word-break: break-all; color: #1a73e8; font-weight: 500;"></div>
                            </div>
                            <button type="button" onclick="submitFormIfValid()" class="btn btn-primary w-100" style="border-radius: 4px; font-weight: 500;">
                                Serahkan
                            </button>
                        </form>
                        
                        <script>
                            function submitFormIfValid() {
                                if (document.getElementById('submissionFile').files.length > 0 || document.getElementById('submissionLink').value !== '') {
                                    document.getElementById('submitTugasForm').submit();
                                } else {
                                    alert('Silakan pilih file atau lampirkan dari Drive terlebih dahulu.');
                                }
                            }
                        </script>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@if(!$isTeacher)
<script async defer src="https://apis.google.com/js/api.js" onload="gapiLoaded()"></script>
<script async defer src="https://accounts.google.com/gsi/client" onload="gisLoaded()"></script>
<script>
    const CLIENT_ID = '{{ config("services.google.client_id") }}';
    const API_KEY = '{{ config("services.google.api_key") }}';
    let tokenClient;
    let accessToken = null;
    let pickerInited = false;
    let gisInited = false;

    function gapiLoaded() {
        gapi.load('picker', {callback: onPickerApiLoad});
    }
    function onPickerApiLoad() {
        pickerInited = true;
    }
    function gisLoaded() {
        tokenClient = google.accounts.oauth2.initTokenClient({
            client_id: CLIENT_ID,
            scope: 'https://www.googleapis.com/auth/drive.readonly',
            callback: '', // set later
        });
        gisInited = true;
    }

    function loadPicker() {
        if (!pickerInited || !gisInited) {
            alert('Sedang memuat Google API. Silakan klik lagi dalam beberapa detik.');
            return;
        }
        tokenClient.callback = async (response) => {
            if (response.error !== undefined) {
                console.error(response);
                return;
            }
            accessToken = response.access_token;
            createPicker();
        };

        if (accessToken === null) {
            tokenClient.requestAccessToken({prompt: '', login_hint: '{{ Auth::user()->email }}'});
        } else {
            tokenClient.requestAccessToken({prompt: '', login_hint: '{{ Auth::user()->email }}'});
        }
    }

    function createPicker() {
        const view = new google.picker.DocsView(google.picker.ViewId.DOCS);
        const picker = new google.picker.PickerBuilder()
            .addView(view)
            .setOAuthToken(accessToken)
            .setDeveloperKey(API_KEY)
            .setCallback(pickerCallback)
            .build();
        picker.setVisible(true);
    }

    function pickerCallback(data) {
        if (data.action === google.picker.Action.PICKED) {
            const doc = data.docs[0];
            document.getElementById('submissionLink').value = doc.url;
            document.getElementById('linkName').innerText = 'Lampiran Drive: ' + doc.name;
            document.getElementById('submissionFile').value = '';
            document.getElementById('fileName').innerText = '';
        }
    }
</script>
@endif
@endsection
