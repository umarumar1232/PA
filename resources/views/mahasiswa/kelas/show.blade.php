@extends('layouts.classroom')

@section('title', $mataKuliah->nama_mk)

@push('css')
<style>
/* ---- Google Classroom-style Stream Card ---- */
.stream-card {
    display: flex;
    align-items: center;
    background: #ffffff;
    border-radius: 12px;
    padding: 14px 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.05);
    cursor: pointer;
    transition: box-shadow 0.15s ease, background 0.15s ease;
    border: 1px solid #e8eaed;
}
.stream-card:hover {
    box-shadow: 0 3px 10px rgba(0,0,0,0.12);
    background: #f8faff;
}
.stream-card > .d-flex {
    width: 100%;
}
.stream-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 17px;
}
.stream-icon-material {
    background: #e8f0fe;
    color: #1a73e8;
}
.stream-icon-assignment {
    background: #e6f4ea;
    color: #1e8e3e;
}
.stream-more-btn:hover {
    background: #f1f3f4 !important;
}
</style>
@endpush

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
        
        <div style="position: relative; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; width: 100%;">
            <div>
                {{-- Breadcrumb --}}
                <div style="font-size: 13px; opacity: 0.8; margin-bottom: 8px;">
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('mahasiswa.home') }}" style="color: white; text-decoration: none; opacity: 0.8;">
                        <i class="fas fa-home mr-1"></i> Beranda
                    </a>
                    <span class="mx-2">&rsaquo;</span>
                    <span>{{ $mataKuliah->nama_mk }}</span>
                </div>
                <h1 style="font-family: 'Google Sans', sans-serif; font-size: 32px; font-weight: 500; margin: 0; line-height: 1.2;">
                    {{ $mataKuliah->nama_mk }}
                </h1>
                <p style="margin: 4px 0 0; opacity: 0.85; font-size: 14px;">
                    <strong>Kode kelas:</strong> {{ $mataKuliah->kode_mk }} 
                    @if($mataKuliah->bagian) &bull; <strong>Kelas:</strong> {{ $mataKuliah->bagian }} @endif
                    @if($mataKuliah->jadwal) &bull; <strong>Jadwal:</strong> {{ $mataKuliah->jadwal }} @endif
                    @if($mataKuliah->mata_pelajaran) &bull; <strong>Subject:</strong> {{ $mataKuliah->mata_pelajaran }} @endif
                    @if($mataKuliah->ruang) &bull; <strong>Ruangan:</strong> {{ $mataKuliah->ruang }} @endif
                </p>
            </div>
            @if($isTeacher)
                <button class="btn btn-light d-flex align-items-center shadow-sm" data-toggle="modal" data-target="#editClassModal" style="border-radius: 20px; padding: 6px 16px; font-weight: 500; font-size: 13px; color: #1a73e8; border: none; margin-top: 12px; height: 36px;">
                    <i class="fas fa-edit mr-2"></i> Edit Kelas
                </button>
            @endif
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
                    {{-- Kode Kelas Card --}}
                    <div class="gc-card p-3 mb-3 d-flex flex-column justify-content-center" style="border-radius: 8px;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span style="font-size: 13px; font-weight: 500; color: #3c4043;">Kode kelas</span>
                            <button class="btn btn-link text-muted p-0" title="Salin Kode" onclick="navigator.clipboard.writeText('{{ $mataKuliah->kode_mk }}'); alert('Kode kelas berhasil disalin: {{ $mataKuliah->kode_mk }}');" style="text-decoration: none;">
                                <i class="far fa-copy" style="font-size: 14px;"></i>
                            </button>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span style="font-family: 'Google Sans', sans-serif; font-size: 22px; font-weight: 700; color: #1a73e8; letter-spacing: 0.5px;">
                                {{ $mataKuliah->kode_mk }}
                            </span>
                            <button class="btn btn-link text-muted p-0" title="Tampilkan Layar Penuh" onclick="showLargeCode('{{ $mataKuliah->kode_mk }}')" style="text-decoration: none;">
                                <i class="fas fa-expand" style="font-size: 14px;"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Large Code Overlay Modal -->
                    <div id="largeCodeOverlay" onclick="hideLargeCode()" style="display: none; position: fixed; inset: 0; background: rgba(255,255,255,0.98); z-index: 9999; align-items: center; justify-content: center; flex-direction: column; cursor: pointer;">
                        <div style="font-size: 24px; color: #5f6368; margin-bottom: 24px; font-family: 'Google Sans', sans-serif;">Kode kelas</div>
                        <div id="largeCodeText" style="font-size: 160px; font-weight: 700; color: #1a73e8; font-family: 'Google Sans', sans-serif; letter-spacing: 4px;"></div>
                        <div style="font-size: 18px; color: #5f6368; margin-top: 40px;"><i class="fas fa-times mr-2"></i> Klik di mana saja untuk menutup</div>
                    </div>

                    <script>
                    function showLargeCode(code) {
                        document.getElementById('largeCodeText').innerText = code;
                        document.getElementById('largeCodeOverlay').style.display = 'flex';
                    }
                    function hideLargeCode() {
                        document.getElementById('largeCodeOverlay').style.display = 'none';
                    }
                    </script>

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
                    {{-- Tombol Pengumuman Baru (Teacher Only) --}}
                    @if($isTeacher)
                    <div class="gc-card p-3 mb-3 d-flex align-items-center" style="cursor: pointer; color: #5f6368; border-radius: 12px;">
                        <img src="{{ Auth::user()->foto ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->nama).'&color=1a73e8&background=e8f0fe' }}" class="gc-avatar mr-3">
                        <span style="flex: 1; border-bottom: 1px solid #e0e0e0; padding-bottom: 4px; font-size: 14px; color: #80868b;">Umuman sesuatu kepada kelas Anda...</span>
                    </div>
                    @endif

                    {{-- Daftar Materi & Tugas sebagai Post (Google Classroom Style) --}}
                    @php
                        // Gabungkan semua item (materi + tugas) lalu sort by created_at terbaru
                        $streamItems = collect();
                        foreach ($mataKuliah->materials as $mat) {
                            $streamItems->push(['type' => 'material', 'item' => $mat, 'created_at' => $mat->created_at, 'updated_at' => $mat->updated_at]);
                            foreach ($mat->assignments as $asn) {
                                $streamItems->push(['type' => 'assignment', 'item' => $asn, 'created_at' => $asn->created_at, 'updated_at' => $asn->updated_at]);
                            }
                        }
                        $streamItems = $streamItems->sortByDesc('created_at');

                        // Nama guru pertama di kelas atau nama user saat ini
                        $teacherName = $activeTeachers->first() ? $activeTeachers->first()->nama : 'Pengajar';
                    @endphp

                    @forelse($streamItems as $entry)
                        @php
                            $item = $entry['item'];
                            $isMaterial = $entry['type'] === 'material';
                            $isEdited = $item->updated_at && $item->updated_at->diffInSeconds($item->created_at) > 30;
                        @endphp
                        <div class="stream-card mb-3">
                            <div class="d-flex align-items-center" style="gap: 14px;">
                                {{-- Icon Box --}}
                                <div class="stream-icon {{ $isMaterial ? 'stream-icon-material' : 'stream-icon-assignment' }}">
                                    @if($isMaterial)
                                        <i class="fas fa-bookmark"></i>
                                    @else
                                        <i class="fas fa-clipboard-list"></i>
                                    @endif
                                </div>

                                {{-- Content --}}
                                <div style="flex: 1; min-width: 0;">
                                    <div style="font-size: 14px; color: #1f1f1f; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        @if($isMaterial)
                                            <span style="font-weight: 400; color: #3c4043;">{{ $teacherName }} memposting materi baru: </span>{{ $item->title }}
                                        @else
                                            <span style="font-weight: 400; color: #3c4043;">{{ $teacherName }} memposting tugas baru: </span>{{ $item->title }}
                                        @endif
                                    </div>
                                    <div style="font-size: 12px; color: #80868b; margin-top: 2px;">
                                        {{ \Carbon\Carbon::parse($item->created_at)->isoFormat('D MMM') }}
                                        @if($isEdited)
                                            <span style="color: #bbb;">(Diedit {{ \Carbon\Carbon::parse($item->updated_at)->isoFormat('D MMM') }})</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Three-dot Menu --}}
                                @if($isTeacher || !$isMaterial)
                                <div class="dropdown">
                                    <button class="btn btn-link text-muted p-0 stream-more-btn" data-toggle="dropdown" style="line-height: 1; text-decoration: none; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-ellipsis-v" style="font-size: 16px; color: #5f6368;"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right shadow-sm" style="border-radius: 8px; border: 1px solid #e8eaed; min-width: 160px; padding: 4px 0;">
                                        @if($isTeacher)
                                            @if($isMaterial)
                                                <a class="dropdown-item py-2 edit-material-btn" href="#"
                                                    data-id="{{ $item->id }}"
                                                    data-title="{{ $item->title }}"
                                                    data-description="{{ $item->description }}"
                                                    data-category-id="{{ $item->category_id }}"
                                                    data-video-url="{{ $item->video_url }}"
                                                    style="font-size: 14px;">
                                                    <i class="fas fa-edit mr-2 text-warning"></i>Edit Materi
                                                </a>
                                            @else
                                                <a class="dropdown-item py-2 edit-assignment-btn" href="#"
                                                    data-id="{{ $item->id }}"
                                                    data-title="{{ $item->title }}"
                                                    data-description="{{ $item->description }}"
                                                    data-material-id="{{ $item->material_id }}"
                                                    data-notebook-url="{{ $item->notebook_url }}"
                                                    data-deadline="{{ $item->deadline ? \Carbon\Carbon::parse($item->deadline)->format('Y-m-d\TH:i') : '' }}"
                                                    style="font-size: 14px;">
                                                    <i class="fas fa-edit mr-2 text-warning"></i>Edit Tugas
                                                </a>
                                            @endif
                                            <div class="dropdown-divider my-1"></div>
                                            @if($isMaterial)
                                                <form action="{{ route('admin.materials.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus materi ini?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="dropdown-item py-2 text-danger" style="font-size: 14px;">
                                                        <i class="fas fa-trash-alt mr-2"></i>Hapus
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.assignments.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus tugas ini?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="dropdown-item py-2 text-danger" style="font-size: 14px;">
                                                        <i class="fas fa-trash-alt mr-2"></i>Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            @if(!$isMaterial)
                                                @php $sub = $submissions[$item->id] ?? null; @endphp
                                                <span class="dropdown-item py-2 disabled" style="font-size: 13px; color: #80868b;">
                                                    @if($sub && $sub->file) <i class="fas fa-check-circle mr-2 text-success"></i>Sudah dikumpulkan
                                                    @else <i class="fas fa-times-circle mr-2 text-danger"></i>Belum dikumpulkan @endif
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-stream fa-3x mb-3 d-block"></i>
                            Belum ada materi yang diposting.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ---- TAB: TUGAS KELAS ---- --}}
        <div id="content-tugaskelas" class="tab-content-panel" style="display:none;">
            @if($isTeacher)
            <div class="mb-4 text-left">
                <div class="dropdown d-inline-block">
                    <button class="btn btn-primary d-flex align-items-center shadow-sm dropdown-toggle" id="dropdownCreate" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius: 24px; padding: 10px 24px; font-weight: 500; font-size: 14px; background-color: #1a73e8; border: none; transition: background-color 0.2s;">
                        <i class="fas fa-plus mr-2" style="font-size: 16px;"></i> Buat
                    </button>
                    <div class="dropdown-menu mt-2 shadow-sm" aria-labelledby="dropdownCreate" style="border-radius: 8px; border: 1px solid #e0e0e0;">
                        <a class="dropdown-item py-2" href="#" data-toggle="modal" data-target="#createMaterialModal"><i class="fas fa-book-open mr-2 text-primary"></i> Materi</a>
                        @if($mataKuliah->materials->isEmpty())
                            <a class="dropdown-item py-2 disabled text-muted" href="#" title="Harap buat materi terlebih dahulu"><i class="fas fa-clipboard-list mr-2"></i> Tugas (Buat materi dulu)</a>
                        @else
                            <a class="dropdown-item py-2" href="#" data-toggle="modal" data-target="#createAssignmentModal"><i class="fas fa-clipboard-list mr-2 text-success"></i> Tugas</a>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            @forelse($mataKuliah->materials as $material)
                <div class="mb-4">
                    {{-- Header Pertemuan --}}
                    <div class="d-flex align-items-center justify-content-between mb-2" style="border-bottom: 1px solid #e0e0e0; padding-bottom: 8px;">
                        <div>
                            <i class="fas fa-folder text-primary mr-2"></i>
                            <span class="font-weight-medium text-dark" style="font-size: 15px;">{{ $material->title }}</span>
                            <small class="text-muted ml-2">({{ $material->category->name ?? '' }})</small>
                        </div>
                        @if($isTeacher)
                            <div class="d-flex align-items-center" style="gap: 12px;">
                                <button class="btn btn-link text-muted p-0 edit-material-btn" title="Edit Materi"
                                    data-id="{{ $material->id }}"
                                    data-title="{{ $material->title }}"
                                    data-description="{{ $material->description }}"
                                    data-category-id="{{ $material->category_id }}"
                                    data-video-url="{{ $material->video_url }}"
                                    style="text-decoration: none;">
                                    <i class="fas fa-edit text-warning" style="font-size: 14px;"></i>
                                </button>
                                <form action="{{ route('admin.materials.destroy', $material->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini beserta tugas di dalamnya?');" style="margin:0; display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link text-muted p-0" title="Hapus Materi" style="text-decoration: none;">
                                        <i class="fas fa-trash-alt text-danger" style="font-size: 14px;"></i>
                                    </button>
                                </form>
                            </div>
                        @endif
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
                            @if($isTeacher)
                            <div class="mr-3 d-flex align-items-center" style="gap: 12px;">
                                <button class="btn btn-link text-muted p-0 edit-assignment-btn" title="Edit Tugas"
                                    data-id="{{ $tugas->id }}"
                                    data-title="{{ $tugas->title }}"
                                    data-description="{{ $tugas->description }}"
                                    data-material-id="{{ $tugas->material_id }}"
                                    data-notebook-url="{{ $tugas->notebook_url }}"
                                    data-deadline="{{ $tugas->deadline ? \Carbon\Carbon::parse($tugas->deadline)->format('Y-m-d\TH:i') : '' }}"
                                    style="text-decoration: none;">
                                    <i class="fas fa-edit text-warning" style="font-size: 13px;"></i>
                                </button>
                                <form action="{{ route('admin.assignments.destroy', $tugas->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini?');" style="margin:0; display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link text-muted p-0" title="Hapus Tugas" style="text-decoration: none;">
                                        <i class="fas fa-trash-alt text-danger" style="font-size: 13px;"></i>
                                    </button>
                                </form>
                            </div>
                            @endif
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
                    Belum ada materi atau tugas yang diberikan.
                </div>
            @endforelse
        </div>

        {{-- ---- TAB: ORANG ---- --}}
        <div id="content-orang" class="tab-content-panel" style="display:none;">
            <div class="gc-card p-4">
                {{-- Header Pengajar --}}
                <div class="d-flex justify-content-between align-items-center mb-3" style="border-bottom: 1px solid #e0e0e0; padding-bottom: 8px;">
                    <h5 class="font-weight-medium text-dark mb-0">
                        <i class="fas fa-chalkboard-teacher text-primary mr-2"></i>Pengajar
                    </h5>
                    @if($isTeacher)
                        <button class="btn btn-link text-primary p-0" title="Undang Pengajar" data-toggle="modal" data-target="#inviteTeacherModal" style="text-decoration: none;">
                            <i class="fas fa-user-plus" style="font-size: 18px;"></i>
                        </button>
                    @endif
                </div>

                {{-- List Pengajar --}}
                <div class="mb-4">
                    @foreach($activeTeachers as $teacher)
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $teacher->foto ?? 'https://ui-avatars.com/api/?name='.urlencode($teacher->nama).'&color=1a73e8&background=e8f0fe' }}" class="gc-avatar mr-3" style="width: 40px; height: 40px;">
                            <div>
                                <div class="font-weight-medium">{{ $teacher->nama }}</div>
                                <div class="text-muted small">{{ $teacher->email }}</div>
                            </div>
                        </div>
                    @endforeach
                    
                    @if($isTeacher)
                        @foreach($pendingTeachers as $t)
                            <div class="d-flex align-items-center mb-3" style="opacity: 0.65;">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($t->nama) }}&color=5f6368&background=f1f3f4" class="gc-avatar mr-3" style="width: 40px; height: 40px;">
                                <div>
                                    <div class="font-weight-medium">{{ $t->nama }} <span class="badge badge-secondary ml-1" style="font-size: 10px; font-weight: normal;">Undangan Terkirim</span></div>
                                    <div class="text-muted small">{{ $t->email }}</div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- Header Mahasiswa --}}
                <div class="d-flex justify-content-between align-items-center mb-3" style="border-bottom: 1px solid #e0e0e0; padding-bottom: 8px;">
                    <h5 class="font-weight-medium text-dark mb-0">
                        <i class="fas fa-users text-primary mr-2"></i>Mahasiswa
                    </h5>
                    <div class="d-flex align-items-center" style="gap: 12px;">
                        <span class="text-muted small">{{ count($activeStudents) }} mahasiswa</span>
                        @if($isTeacher)
                            <button class="btn btn-link text-primary p-0" title="Undang Mahasiswa" data-toggle="modal" data-target="#inviteStudentModal" style="text-decoration: none;">
                                <i class="fas fa-user-plus" style="font-size: 18px;"></i>
                            </button>
                        @endif
                    </div>
                </div>

                {{-- List Mahasiswa --}}
                <div>
                    @forelse($activeStudents as $student)
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $student->foto ?? 'https://ui-avatars.com/api/?name='.urlencode($student->nama).'&color=1e8e3e&background=e6f4ea' }}" class="gc-avatar mr-3" style="width: 40px; height: 40px;">
                            <div>
                                <div class="font-weight-medium">{{ $student->nama }}</div>
                                <div class="text-muted small">{{ $student->email }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted small py-2">Belum ada mahasiswa yang terdaftar di kelas ini.</div>
                    @endforelse

                    @if($isTeacher)
                        @foreach($pendingStudents as $s)
                            <div class="d-flex align-items-center mb-3" style="opacity: 0.65;">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($s->nama) }}&color=5f6368&background=f1f3f4" class="gc-avatar mr-3" style="width: 40px; height: 40px;">
                                <div>
                                    <div class="font-weight-medium">{{ $s->nama }} <span class="badge badge-secondary ml-1" style="font-size: 10px; font-weight: normal;">Undangan Terkirim</span></div>
                                    <div class="text-muted small">{{ $s->email }}</div>
                                </div>
                            </div>
                        @endforeach
                    @endif
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

@if($isTeacher && !$mataKuliah->materials->isEmpty())
<!-- Create Assignment Modal -->
<div class="modal fade" id="createAssignmentModal" tabindex="-1" aria-labelledby="createAssignmentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
    <div class="modal-content gc-modal-content">
      <form action="{{ route('admin.kelas.tugas.store', $mataKuliah->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header gc-modal-header">
          <h5 class="modal-title gc-modal-title" id="createAssignmentModalLabel">Buat Tugas Baru</h5>
        </div>
        <div class="modal-body gc-modal-body text-left">
          
          <div class="form-group mb-4">
              <label class="text-muted small font-weight-bold mb-1">Pilih Pertemuan / Materi*</label>
              <select name="material_id" class="form-control" required style="height: 48px; border-radius: 8px;">
                  @foreach($mataKuliah->materials as $mat)
                      <option value="{{ $mat->id }}">{{ $mat->title }} ({{ $mat->category->name ?? 'Materi' }})</option>
                  @endforeach
              </select>
          </div>

          <div class="gc-md-input-group mb-4">
            <input type="text" name="title" id="tugas_title_input" placeholder=" " required autocomplete="off">
            <label>Judul Tugas*</label>
          </div>

          <div class="form-group mb-4">
              <label class="text-muted small font-weight-bold mb-1">Petunjuk / Deskripsi</label>
              <textarea name="description" class="form-control" placeholder="Tulis instruksi pengerjaan tugas di sini..." rows="4" style="border-radius: 8px; padding: 12px;"></textarea>
          </div>

          <div class="form-group mb-4">
              <label class="text-muted small font-weight-bold mb-1">Tenggat Waktu (Deadline)</label>
              <input type="datetime-local" name="deadline" class="form-control" style="height: 48px; border-radius: 8px;">
          </div>

          <div class="gc-md-input-group mb-4">
            <input type="url" name="notebook_url" placeholder=" " autocomplete="off">
            <label>URL Notebook Google Colab (Opsional)</label>
          </div>

          <div class="form-group mb-4">
              <label class="text-muted small font-weight-bold mb-1">Lampiran File Tugas (Opsional)</label>
              <div class="custom-file">
                  <input type="file" name="file" class="custom-file-input" id="assignmentFileInput" onchange="$(this).next('.custom-file-label').html(this.files[0].name)">
                  <label class="custom-file-label" for="assignmentFileInput" style="border-radius: 8px; line-height: 2.2;">Pilih File...</label>
              </div>
          </div>

        </div>
        <div class="modal-footer gc-modal-footer">
          <button type="button" class="gc-modal-btn gc-modal-btn-cancel" data-dismiss="modal">Batal</button>
          <button type="submit" class="gc-modal-btn gc-modal-btn-submit active" id="create_tugas_submit_btn">Buat</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif

@if($isTeacher)
<!-- Edit Class Modal -->
<div class="modal fade" id="editClassModal" tabindex="-1" aria-labelledby="editClassModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 560px;">
    <div class="modal-content gc-modal-content">
      <form action="{{ route('admin.kelas.update', $mataKuliah->id) }}" method="POST">
        @csrf
        <div class="modal-header gc-modal-header">
          <h5 class="modal-title gc-modal-title" id="editClassModalLabel">Edit detail kelas</h5>
        </div>
        <div class="modal-body gc-modal-body text-left">
          
          <div class="gc-md-input-group mb-4">
            <input type="text" name="nama_kelas" id="edit_nama_kelas" placeholder=" " value="{{ $mataKuliah->nama_mk }}" required autocomplete="off">
            <label>Nama*</label>
          </div>

          <div class="gc-md-input-group mb-4">
            <input type="text" name="bagian" id="edit_bagian" placeholder=" " value="{{ $mataKuliah->bagian }}" autocomplete="off">
            <label>Kelas</label>
          </div>

          <div class="gc-md-input-group mb-4">
            <input type="text" name="jadwal" id="edit_jadwal" placeholder=" " value="{{ $mataKuliah->jadwal }}" autocomplete="off">
            <label>Jadwal</label>
          </div>

          <div class="gc-md-input-group mb-4">
            <input type="text" name="mata_pelajaran" id="edit_mata_pelajaran" placeholder=" " value="{{ $mataKuliah->mata_pelajaran }}" autocomplete="off">
            <label>Subject / Mata Kuliah</label>
          </div>

          <div class="gc-md-input-group mb-4">
            <input type="text" name="ruang" id="edit_ruang" placeholder=" " value="{{ $mataKuliah->ruang }}" autocomplete="off">
            <label>Ruangan</label>
          </div>

        </div>
        <div class="modal-footer gc-modal-footer">
          <button type="button" class="gc-modal-btn gc-modal-btn-cancel" data-dismiss="modal">Batal</button>
          <button type="submit" class="gc-modal-btn gc-modal-btn-submit active">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Create Material Modal -->
<div class="modal fade" id="createMaterialModal" tabindex="-1" aria-labelledby="createMaterialModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
    <div class="modal-content gc-modal-content">
      <form action="{{ route('admin.kelas.materi.store', $mataKuliah->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header gc-modal-header">
          <h5 class="modal-title gc-modal-title" id="createMaterialModalLabel">Buat Materi Baru</h5>
        </div>
        <div class="modal-body gc-modal-body text-left">
          
          <div class="form-group mb-4">
              <label class="text-muted small font-weight-bold mb-1">Pertemuan / Kategori*</label>
              <select name="category_id" class="form-control" required style="height: 48px; border-radius: 8px;">
                  @foreach($categories as $cat)
                      <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                  @endforeach
              </select>
          </div>

          <div class="gc-md-input-group mb-4">
            <input type="text" name="title" placeholder=" " required autocomplete="off">
            <label>Judul Materi*</label>
          </div>

          <div class="form-group mb-4">
              <label class="text-muted small font-weight-bold mb-1">Deskripsi</label>
              <textarea name="description" class="form-control" placeholder="Tulis deskripsi materi di sini..." rows="4" style="border-radius: 8px; padding: 12px;"></textarea>
          </div>

          <div class="gc-md-input-group mb-4">
            <input type="url" name="video_url" placeholder=" " autocomplete="off">
            <label>Video URL (Youtube) (Opsional)</label>
          </div>

          <div class="form-group mb-4">
              <label class="text-muted small font-weight-bold mb-1">Lampiran File Materi (Opsional)</label>
              <div class="custom-file">
                  <input type="file" name="file" class="custom-file-input" id="materialFileInput" onchange="$(this).next('.custom-file-label').html(this.files[0].name)">
                  <label class="custom-file-label" for="materialFileInput" style="border-radius: 8px; line-height: 2.2;">Pilih File...</label>
              </div>
          </div>

        </div>
        <div class="modal-footer gc-modal-footer">
          <button type="button" class="gc-modal-btn gc-modal-btn-cancel" data-dismiss="modal">Batal</button>
          <button type="submit" class="gc-modal-btn gc-modal-btn-submit active">Buat</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Material Modal -->
<div class="modal fade" id="editMaterialModal" tabindex="-1" aria-labelledby="editMaterialModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
    <div class="modal-content gc-modal-content">
      <form action="" method="POST" id="editMaterialForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-header gc-modal-header">
          <h5 class="modal-title gc-modal-title" id="editMaterialModalLabel">Edit Materi</h5>
        </div>
        <div class="modal-body gc-modal-body text-left">
          
          <div class="form-group mb-4">
              <label class="text-muted small font-weight-bold mb-1">Pertemuan / Kategori*</label>
              <select name="category_id" id="edit_material_category_id" class="form-control" required style="height: 48px; border-radius: 8px;">
                  @foreach($categories as $cat)
                      <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                  @endforeach
              </select>
          </div>

          <div class="gc-md-input-group mb-4">
            <input type="text" name="title" id="edit_material_title" placeholder=" " required autocomplete="off">
            <label>Judul Materi*</label>
          </div>

          <div class="form-group mb-4">
              <label class="text-muted small font-weight-bold mb-1">Deskripsi</label>
              <textarea name="description" id="edit_material_description" class="form-control" placeholder="Tulis deskripsi materi di sini..." rows="4" style="border-radius: 8px; padding: 12px;"></textarea>
          </div>

          <div class="gc-md-input-group mb-4">
            <input type="url" name="video_url" id="edit_material_video_url" placeholder=" " autocomplete="off">
            <label>Video URL (Youtube) (Opsional)</label>
          </div>

          <div class="form-group mb-4">
              <label class="text-muted small font-weight-bold mb-1">Ganti File Materi (Opsional, biarkan kosong jika tetap)</label>
              <div class="custom-file">
                  <input type="file" name="file" class="custom-file-input" id="editMaterialFileInput" onchange="$(this).next('.custom-file-label').html(this.files[0].name)">
                  <label class="custom-file-label" for="editMaterialFileInput" style="border-radius: 8px; line-height: 2.2;">Pilih File...</label>
              </div>
          </div>

        </div>
        <div class="modal-footer gc-modal-footer">
          <button type="button" class="gc-modal-btn gc-modal-btn-cancel" data-dismiss="modal">Batal</button>
          <button type="submit" class="gc-modal-btn gc-modal-btn-submit active">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Assignment Modal -->
<div class="modal fade" id="editAssignmentModal" tabindex="-1" aria-labelledby="editAssignmentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
    <div class="modal-content gc-modal-content">
      <form action="" method="POST" id="editAssignmentForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-header gc-modal-header">
          <h5 class="modal-title gc-modal-title" id="editAssignmentModalLabel">Edit Tugas</h5>
        </div>
        <div class="modal-body gc-modal-body text-left">
          
          <div class="form-group mb-4">
              <label class="text-muted small font-weight-bold mb-1">Pilih Pertemuan / Materi*</label>
              <select name="material_id" id="edit_assignment_material_id" class="form-control" required style="height: 48px; border-radius: 8px;">
                  @foreach($mataKuliah->materials as $mat)
                      <option value="{{ $mat->id }}">{{ $mat->title }} ({{ $mat->category->name ?? 'Materi' }})</option>
                  @endforeach
              </select>
          </div>

          <div class="gc-md-input-group mb-4">
            <input type="text" name="title" id="edit_assignment_title" placeholder=" " required autocomplete="off">
            <label>Judul Tugas*</label>
          </div>

          <div class="form-group mb-4">
              <label class="text-muted small font-weight-bold mb-1">Petunjuk / Deskripsi</label>
              <textarea name="description" id="edit_assignment_description" class="form-control" placeholder="Tulis instruksi pengerjaan tugas di sini..." rows="4" style="border-radius: 8px; padding: 12px;"></textarea>
          </div>

          <div class="form-group mb-4">
              <label class="text-muted small font-weight-bold mb-1">Tenggat Waktu (Deadline)</label>
              <input type="datetime-local" name="deadline" id="edit_assignment_deadline" class="form-control" style="height: 48px; border-radius: 8px;">
          </div>

          <div class="gc-md-input-group mb-4">
            <input type="url" name="notebook_url" id="edit_assignment_notebook_url" placeholder=" " autocomplete="off">
            <label>URL Notebook Google Colab (Opsional)</label>
          </div>

          <div class="form-group mb-4">
              <label class="text-muted small font-weight-bold mb-1">Ganti File Tugas (Opsional, biarkan kosong jika tetap)</label>
              <div class="custom-file">
                  <input type="file" name="file" class="custom-file-input" id="editAssignmentFileInput" onchange="$(this).next('.custom-file-label').html(this.files[0].name)">
                  <label class="custom-file-label" for="editAssignmentFileInput" style="border-radius: 8px; line-height: 2.2;">Pilih File...</label>
              </div>
          </div>

        </div>
        <div class="modal-footer gc-modal-footer">
          <button type="button" class="gc-modal-btn gc-modal-btn-cancel" data-dismiss="modal">Batal</button>
          <button type="submit" class="gc-modal-btn gc-modal-btn-submit active">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Invite Teacher Modal -->
<div class="modal fade" id="inviteTeacherModal" tabindex="-1" aria-labelledby="inviteTeacherModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
    <div class="modal-content gc-modal-content">
      <form action="{{ route('admin.kelas.invite-teacher', $mataKuliah->id) }}" method="POST">
        @csrf
        <div class="modal-header gc-modal-header">
          <h5 class="modal-title gc-modal-title" id="inviteTeacherModalLabel">Undang Pengajar</h5>
        </div>
        <div class="modal-body gc-modal-body text-left">
          <p class="small text-muted mb-4">Ketik email pengajar (dosen/admin) yang terdaftar di sistem untuk diundang mengajar bersama di kelas ini.</p>
          <div class="gc-md-input-group mb-4">
            <input type="email" name="email" placeholder=" " required autocomplete="off">
            <label>Email Pengajar</label>
          </div>
        </div>
        <div class="modal-footer gc-modal-footer">
          <button type="button" class="gc-modal-btn gc-modal-btn-cancel" data-dismiss="modal">Batal</button>
          <button type="submit" class="gc-modal-btn gc-modal-btn-submit active">Undang</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Invite Student Modal -->
<div class="modal fade" id="inviteStudentModal" tabindex="-1" aria-labelledby="inviteStudentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 540px;">
    <div class="modal-content gc-modal-content">
      <div class="modal-header gc-modal-header d-block">
        <h5 class="modal-title gc-modal-title mb-3" id="inviteStudentModalLabel">Undang Mahasiswa</h5>
        
        <ul class="nav nav-pills nav-fill bg-white p-1 rounded border" id="inviteStudentTabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link active py-2" id="invite-email-tab" data-toggle="pill" href="#invite-email-panel" role="tab" style="font-size: 13px; font-weight: 500; border-radius: 4px;">Ketik Email (Milist)</a>
          </li>
          <li class="nav-item">
            <a class="nav-link py-2" id="invite-list-tab" data-toggle="pill" href="#invite-list-panel" role="tab" style="font-size: 13px; font-weight: 500; border-radius: 4px;">Pilih Dari Daftar</a>
          </li>
        </ul>
      </div>

      <div class="modal-body gc-modal-body text-left mt-3">
        <div class="tab-content" id="inviteStudentTabContent">
          
          <div class="tab-pane fade show active" id="invite-email-panel" role="tabpanel" aria-labelledby="invite-email-tab">
            <form action="{{ route('admin.kelas.invite-students', $mataKuliah->id) }}" method="POST">
              @csrf
              <p class="small text-muted mb-3">Masukkan email mahasiswa. Anda bisa memasukkan beberapa email sekaligus dipisahkan dengan koma atau baris baru (milist).</p>
              <div class="form-group mb-4">
                <textarea name="emails" class="form-control" rows="5" placeholder="contoh1@mail.com, contoh2@mail.com" style="border-radius: 8px; padding: 12px; font-size: 14px;"></textarea>
              </div>
              <div class="d-flex justify-content-end" style="gap: 8px;">
                <button type="button" class="gc-modal-btn gc-modal-btn-cancel" data-dismiss="modal">Batal</button>
                <button type="submit" class="gc-modal-btn gc-modal-btn-submit active">Undang</button>
              </div>
            </form>
          </div>

          <div class="tab-pane fade" id="invite-list-panel" role="tabpanel" aria-labelledby="invite-list-tab">
            <form action="{{ route('admin.kelas.invite-students', $mataKuliah->id) }}" method="POST">
              @csrf
              <p class="small text-muted mb-3">Pilih mahasiswa di bawah untuk diundang ke kelas ini:</p>
              <div class="border rounded p-3 mb-4" style="max-height: 250px; overflow-y: auto; background: white;">
                @forelse($allStudents as $student)
                  <div class="custom-control custom-checkbox mb-3">
                    <input type="checkbox" name="user_ids[]" value="{{ $student->user_id }}" class="custom-control-input" id="chk_student_{{ $student->user_id }}">
                    <label class="custom-control-label d-flex align-items-center" for="chk_student_{{ $student->user_id }}" style="cursor: pointer; font-size: 14px;">
                      <img src="{{ $student->foto ?? 'https://ui-avatars.com/api/?name='.urlencode($student->nama).'&color=1e8e3e&background=e6f4ea' }}" class="gc-avatar mr-2" style="width: 28px; height: 28px;">
                      <span>{{ $student->nama }} <small class="text-muted">({{ $student->email }})</small></span>
                    </label>
                  </div>
                @empty
                  <div class="text-center text-muted small py-3">Tidak ada mahasiswa tambahan yang tersedia untuk diundang.</div>
                @endforelse
              </div>
              <div class="d-flex justify-content-end" style="gap: 8px;">
                <button type="button" class="gc-modal-btn gc-modal-btn-cancel" data-dismiss="modal">Batal</button>
                <button type="submit" class="gc-modal-btn gc-modal-btn-submit active" {{ $allStudents->isEmpty() ? 'disabled' : '' }}>Undang</button>
              </div>
            </form>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endif

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

$(document).ready(function() {
    // Populate Edit Material Modal
    $('.edit-material-btn').click(function() {
        var id = $(this).data('id');
        var title = $(this).data('title');
        var description = $(this).data('description');
        var categoryId = $(this).data('category-id');
        var videoUrl = $(this).data('video-url');

        var actionUrl = "{{ url('admin/materials') }}/" + id;
        $('#editMaterialForm').attr('action', actionUrl);
        $('#edit_material_title').val(title);
        $('#edit_material_description').val(description);
        $('#edit_material_category_id').val(categoryId);
        $('#edit_material_video_url').val(videoUrl);

        $('#editMaterialModal').modal('show');
    });

    // Populate Edit Assignment Modal
    $('.edit-assignment-btn').click(function() {
        var id = $(this).data('id');
        var title = $(this).data('title');
        var description = $(this).data('description');
        var materialId = $(this).data('material-id');
        var notebookUrl = $(this).data('notebook-url');
        var deadline = $(this).data('deadline');

        var actionUrl = "{{ url('admin/assignments') }}/" + id;
        $('#editAssignmentForm').attr('action', actionUrl);
        $('#edit_assignment_title').val(title);
        $('#edit_assignment_description').val(description);
        $('#edit_assignment_material_id').val(materialId);
        $('#edit_assignment_notebook_url').val(notebookUrl);
        $('#edit_assignment_deadline').val(deadline);

        $('#editAssignmentModal').modal('show');
    });
});
</script>
@endpush
@endsection
