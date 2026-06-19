@extends('layouts.classroom')

@section('title', $mataKuliah->nama_mk)

@section('content')
<div style="margin: 0; padding: 0;">

    {{-- ===== HEADER BANNER (mirip Google Classroom) ===== --}}
    <div style="
        background: linear-gradient(135deg, #1a73e8 0%, #0d47a1 100%);
        color: white;
        padding: 32px 40px;
        position: relative;
        overflow: hidden;
        min-height: 160px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        margin-bottom: 0;
    ">
        {{-- Decorative background pattern --}}
        <div style="position: absolute; inset: 0; background-image: repeating-linear-gradient(45deg, rgba(255,255,255,0.03) 0, rgba(255,255,255,0.03) 1px, transparent 0, transparent 50%); background-size: 15px 15px;"></div>
        
        <div style="position: relative;">
            {{-- Breadcrumb --}}
            <div style="font-size: 13px; opacity: 0.8; margin-bottom: 8px;">
                <a href="{{ route('mahasiswa.home') }}" style="color: white; text-decoration: none; opacity: 0.8;">
                    <i class="fas fa-home mr-1"></i> Beranda
                </a>
                <span class="mx-2">&rsaquo;</span>
                <span>{{ $mataKuliah->nama_mk }}</span>
            </div>
            <h1 style="font-family: 'Google Sans', sans-serif; font-size: 32px; font-weight: 500; margin: 0; line-height: 1.2;">
                {{ $mataKuliah->nama_mk }}
            </h1>
            <p style="margin: 4px 0 0; opacity: 0.85; font-size: 14px;">
                {{ $mataKuliah->kode_mk }} &bull; Semester {{ $mataKuliah->semester }}
            </p>
        </div>
    </div>

    {{-- ===== TAB NAVIGASI (Forum | Tugas kelas | Orang) ===== --}}
    <div style="background: white; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: center; position: sticky; top: 64px; z-index: 100; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
        <a href="#forum" class="kelas-tab active" id="tab-forum" onclick="showTab('forum')">
            Forum
        </a>
        <a href="#tugaskelas" class="kelas-tab" id="tab-tugaskelas" onclick="showTab('tugaskelas')">
            Tugas kelas
        </a>
        <a href="#orang" class="kelas-tab" id="tab-orang" onclick="showTab('orang')">
            Orang
        </a>
    </div>

    {{-- ===== KONTEN TAB ===== --}}
    <div class="container-fluid py-4">

        {{-- ---- TAB: FORUM ---- --}}
        <div id="content-forum" class="tab-content-panel">
            <div class="row">
                {{-- Kolom Kiri: Mendatang --}}
                <div class="col-md-3 mb-4">
                    <div class="gc-card p-3">
                        <h6 class="font-weight-bold text-dark mb-3">Mendatang</h6>
                        @php
                            $tugasAktif = $assignments->filter(function($a) use ($submissions) {
                                $sub = $submissions[$a->id] ?? null;
                                return !($sub && $sub->file);
                            })->take(5);
                        @endphp
                        @if($tugasAktif->isEmpty())
                            <p class="text-muted small mb-1">Hore, tidak ada tugas yang perlu segera diselesaikan!</p>
                        @else
                            @foreach($tugasAktif as $tugas)
                                <div class="mb-2 pb-2" style="border-bottom: 1px solid #f0f0f0;">
                                    <div style="font-size: 13px; font-weight: 500; color: #3c4043;">{{ $tugas->title }}</div>
                                    <div style="font-size: 11px; color: #5f6368;">
                                        @if($tugas->deadline)
                                            <i class="fas fa-clock mr-1"></i>{{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y') }}
                                        @else
                                            Tanpa batas waktu
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        <a href="#tugaskelas" onclick="showTab('tugaskelas')" class="text-primary small font-weight-medium" style="text-decoration: none;">Lihat semua</a>
                    </div>
                </div>

                {{-- Kolom Kanan: Stream/Feed Materi --}}
                <div class="col-md-9">
                    {{-- Tombol Pengumuman Baru (Admin Only) --}}
                    @if(Auth::user()->role === 'admin')
                    <div class="gc-card p-3 mb-3 d-flex align-items-center" style="cursor: pointer; color: #5f6368;">
                        <img src="{{ Auth::user()->foto ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->nama).'&color=1a73e8&background=e8f0fe' }}" class="gc-avatar mr-3">
                        <span style="flex: 1; border-bottom: 1px solid #e0e0e0; padding-bottom: 4px;">Umumkan sesuatu kepada kelas Anda...</span>
                    </div>
                    @endif

                    {{-- Daftar Materi sebagai Post di Stream --}}
                    @foreach($mataKuliah->materials as $material)
                    <div class="gc-card mb-3" style="overflow: hidden;">
                        <div class="d-flex align-items-center p-3" style="border-bottom: 1px solid #f5f5f5;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: #1a73e8; display: flex; align-items: center; justify-content: center; margin-right: 12px; flex-shrink: 0;">
                                <i class="fas fa-book-open" style="color: white; font-size: 16px;"></i>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 500; font-size: 14px; color: #3c4043;">Materi diposting</div>
                                <div style="font-size: 12px; color: #5f6368;">{{ $material->category->name ?? 'Materi' }}</div>
                            </div>
                            <div style="font-size: 12px; color: #5f6368;">{{ $material->created_at->diffForHumans() }}</div>
                        </div>
                        <div class="p-3">
                            <div style="font-family: 'Google Sans', sans-serif; font-size: 16px; font-weight: 500; color: #1a73e8; margin-bottom: 4px;">
                                {{ $material->title }}
                            </div>
                            <p class="text-muted small mb-2">{{ $material->description }}</p>

                            {{-- Lampiran/File --}}
                            @if($material->file || $material->video_url)
                            <div class="d-flex flex-wrap mt-2" style="gap: 8px;">
                                @if($material->file)
                                    <a href="{{ asset('storage/'.$material->file) }}" target="_blank"
                                       style="display: inline-flex; align-items: center; border: 1px solid #e0e0e0; border-radius: 8px; padding: 8px 12px; text-decoration: none; color: #3c4043; background: #f8f9fa; font-size: 13px;">
                                        <i class="fas fa-file-pdf text-danger mr-2"></i> Buka File
                                    </a>
                                @endif
                                @if($material->video_url)
                                    <a href="{{ $material->video_url }}" target="_blank"
                                       style="display: inline-flex; align-items: center; border: 1px solid #e0e0e0; border-radius: 8px; padding: 8px 12px; text-decoration: none; color: #3c4043; background: #f8f9fa; font-size: 13px;">
                                        <i class="fab fa-youtube text-danger mr-2"></i> Tonton Video
                                    </a>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach

                    @if($mataKuliah->materials->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-stream fa-3x mb-3 d-block"></i>
                        Belum ada materi yang diposting.
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ---- TAB: TUGAS KELAS ---- --}}
        <div id="content-tugaskelas" class="tab-content-panel" style="display:none;">
            @forelse($mataKuliah->materials as $material)
                <div class="mb-4">
                    {{-- Header Pertemuan --}}
                    <div class="d-flex align-items-center mb-2" style="border-bottom: 1px solid #e0e0e0; padding-bottom: 8px;">
                        <i class="fas fa-folder text-primary mr-2"></i>
                        <span class="font-weight-medium text-dark" style="font-size: 15px;">{{ $material->title }}</span>
                        <small class="text-muted ml-2">({{ $material->category->name ?? '' }})</small>
                    </div>

                    {{-- Daftar Tugas dalam Pertemuan ini --}}
                    @foreach($material->assignments as $tugas)
                        @php
                            $submission = $submissions[$tugas->id] ?? null;
                            $isDone = $submission && $submission->file;
                        @endphp
                        <div class="gc-card mb-2 d-flex align-items-center" style="padding: 12px 16px; border-radius: 8px;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: {{ $isDone ? '#e8f5e9' : '#e8f0fe' }}; display: flex; align-items: center; justify-content: center; margin-right: 16px; flex-shrink: 0;">
                                <i class="fas fa-clipboard-list" style="color: {{ $isDone ? '#1e8e3e' : '#1a73e8' }};"></i>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 500; font-size: 14px; color: #3c4043;">{{ $tugas->title }}</div>
                                @if($tugas->deadline)
                                    <div style="font-size: 12px; color: {{ \Carbon\Carbon::parse($tugas->deadline)->isPast() && !$isDone ? '#ea4335' : '#5f6368' }};">
                                        <i class="fas fa-clock mr-1"></i>Tenggat: {{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y, H:i') }}
                                    </div>
                                @endif
                            </div>
                            <div class="text-right" style="flex-shrink: 0; margin-left: 12px;">
                                @if($isDone)
                                    <span style="font-size: 12px; color: #1e8e3e; font-weight: 500;"><i class="fas fa-check-circle mr-1"></i>Diserahkan</span>
                                    @if($submission->score !== null)
                                        <div style="font-size: 12px; color: #5f6368;">Nilai: <strong>{{ $submission->score }}</strong>/100</div>
                                    @endif
                                @else
                                    <span style="font-size: 12px; color: #ea4335; font-weight: 500;"><i class="fas fa-exclamation-circle mr-1"></i>Belum dikumpulkan</span>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    @if($material->assignments->isEmpty())
                        <div class="text-muted small pl-4 py-2">Belum ada tugas untuk pertemuan ini.</div>
                    @endif
                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-tasks fa-3x mb-3 d-block"></i>
                    Belum ada tugas yang diberikan.
                </div>
            @endforelse
        </div>

        {{-- ---- TAB: ORANG ---- --}}
        <div id="content-orang" class="tab-content-panel" style="display:none;">
            <div class="gc-card p-4">
                <h5 class="font-weight-medium text-dark mb-3" style="border-bottom: 1px solid #e0e0e0; padding-bottom: 8px;">
                    <i class="fas fa-chalkboard-teacher text-primary mr-2"></i>Pengajar
                </h5>
                <div class="d-flex align-items-center mb-4">
                    <img src="https://ui-avatars.com/api/?name=Dosen&color=1a73e8&background=e8f0fe" class="gc-avatar mr-3" style="width: 40px; height: 40px;">
                    <div>
                        <div class="font-weight-medium">Tim Pengajar</div>
                        <div class="text-muted small">{{ $mataKuliah->kode_mk }}</div>
                    </div>
                </div>

                <h5 class="font-weight-medium text-dark mb-3" style="border-bottom: 1px solid #e0e0e0; padding-bottom: 8px;">
                    <i class="fas fa-users text-primary mr-2"></i>Mahasiswa
                </h5>
                <div class="text-muted small">
                    Semua mahasiswa yang terdaftar di sistem dapat mengakses kelas ini.
                </div>
            </div>
        </div>

    </div>
</div>

<style>
.kelas-tab {
    display: inline-block;
    padding: 16px 24px;
    font-size: 14px;
    font-weight: 500;
    color: #5f6368;
    text-decoration: none !important;
    border-bottom: 3px solid transparent;
    transition: color 0.2s, border-color 0.2s;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.kelas-tab:hover {
    color: #3c4043;
    background: #f1f3f4;
    border-radius: 4px 4px 0 0;
}
.kelas-tab.active {
    color: #1a73e8;
    border-bottom-color: #1a73e8;
}
</style>

@push('scripts')
<script>
function showTab(name) {
    event.preventDefault();

    // Sembunyikan semua panel
    document.querySelectorAll('.tab-content-panel').forEach(el => el.style.display = 'none');
    // Hapus active dari semua tab
    document.querySelectorAll('.kelas-tab').forEach(el => el.classList.remove('active'));

    // Tampilkan panel yang dipilih
    document.getElementById('content-' + name).style.display = 'block';
    // Aktifkan tab yang dipilih
    document.getElementById('tab-' + name).classList.add('active');
}
</script>
@endpush
@endsection
