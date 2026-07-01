@extends('layouts.classroom')

@section('title', $material->title)

@push('css')
<style>
.detail-page { max-width: 900px; margin: 0 auto; padding: 24px 16px; }
.detail-header { display: flex; align-items: flex-start; gap: 20px; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid #e0e0e0; }
.detail-icon { width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0; }
.detail-icon-material { background: #e8f0fe; color: #1a73e8; }
.detail-title { font-size: 26px; font-weight: 600; color: #1f1f1f; margin-bottom: 6px; line-height: 1.3; }
.detail-meta { font-size: 13px; color: #80868b; }
.detail-body { font-size: 15px; color: #3c4043; line-height: 1.7; margin-bottom: 24px; }
.attachment-chip { display: inline-flex; align-items: center; gap: 10px; border: 1px solid #e0e0e0; border-radius: 8px; padding: 10px 16px; text-decoration: none; color: #3c4043; background: #f8f9fa; font-size: 13px; margin-right: 8px; margin-bottom: 8px; transition: background 0.15s; }
.attachment-chip:hover { background: #e8f0fe; text-decoration: none; }
.comment-box { background: white; border-radius: 12px; border: 1px solid #e0e0e0; padding: 16px; }
.comment-input { width: 100%; border: none; outline: none; font-size: 14px; color: #3c4043; resize: none; background: transparent; }
.comment-divider { border: none; border-bottom: 1px solid #e0e0e0; margin: 8px 0; }
.breadcrumb-link { color: #1a73e8; text-decoration: none; font-size: 13px; }
.breadcrumb-link:hover { text-decoration: underline; }
</style>
@endpush

@section('content')
<div class="detail-page">

    {{-- Breadcrumb --}}
    <div class="mb-4">
        <a href="{{ route('mahasiswa.kelas.show', $mataKuliah->id) }}" class="breadcrumb-link">
            <i class="fas fa-arrow-left mr-2"></i>{{ $mataKuliah->nama_mk }}
        </a>
    </div>

    {{-- Success/Error alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    {{-- Header --}}
    <div class="detail-header">
        <div class="detail-icon detail-icon-material">
            <i class="fas fa-bookmark"></i>
        </div>
        <div style="flex:1; min-width:0;">
            <div class="detail-title">{{ $material->title }}</div>
            <div class="detail-meta">
                @php $teacher = $activeTeachers->first(); @endphp
                {{ $teacher ? $teacher->nama : 'Pengajar' }}
                &nbsp;&bull;&nbsp;
                {{ \Carbon\Carbon::parse($material->created_at)->isoFormat('D MMMM YYYY') }}
                @if($material->updated_at && $material->updated_at->diffInSeconds($material->created_at) > 30)
                    &nbsp;(Diedit {{ \Carbon\Carbon::parse($material->updated_at)->isoFormat('D MMM') }})
                @endif
                @if($material->category)
                    &nbsp;&bull;&nbsp; {{ $material->category->name }}
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
                    <i class="fas fa-edit mr-2 text-warning"></i>Edit Materi
                </a>
                <div class="dropdown-divider my-1"></div>
                <form action="{{ route('admin.materials.destroy', $material->id) }}" method="POST"
                      onsubmit="return confirm('Hapus materi ini?');">
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
    @if($material->description)
    <div class="detail-body mb-4">
        {!! nl2br(e($material->description)) !!}
    </div>
    @endif

    {{-- Attachments --}}
    @if($material->file || $material->video_url)
    <div class="mb-5">
        <div style="font-size:12px;font-weight:600;color:#80868b;text-transform:uppercase;letter-spacing:.8px;margin-bottom:12px;">
            Lampiran
        </div>
        @if($material->file)
            <a href="{{ asset('storage/'.$material->file) }}" target="_blank" class="attachment-chip">
                <i class="fas fa-file-alt" style="color:#e53935;font-size:20px;"></i>
                <span>{{ basename($material->file) }}</span>
                <i class="fas fa-external-link-alt" style="font-size:11px;color:#9aa0a6;"></i>
            </a>
        @endif
        @if($material->video_url)
            <a href="{{ $material->video_url }}" target="_blank" class="attachment-chip">
                <i class="fab fa-youtube" style="color:#e53935;font-size:22px;"></i>
                <span>Tonton Video</span>
                <i class="fas fa-external-link-alt" style="font-size:11px;color:#9aa0a6;"></i>
            </a>
        @endif
    </div>
    @endif

    {{-- Related Assignments --}}
    @if($material->assignments->count() > 0)
    <div class="mb-5">
        <div style="font-size:12px;font-weight:600;color:#80868b;text-transform:uppercase;letter-spacing:.8px;margin-bottom:12px;">
            Tugas Terkait
        </div>
        @foreach($material->assignments as $asn)
        <a href="{{ route('mahasiswa.kelas.tugas.show', [$mataKuliah->id, $asn->id]) }}"
           class="d-flex align-items-center" style="gap:14px;background:#fff;border:1px solid #e8eaed;border-radius:10px;padding:14px 16px;margin-bottom:8px;text-decoration:none;transition:box-shadow 0.15s,background 0.15s;">
            <div style="width:40px;height:40px;border-radius:10px;background:#e6f4ea;color:#1e8e3e;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:18px;">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:14px;font-weight:500;color:#1f1f1f;">{{ $asn->title }}</div>
                <div style="font-size:12px;color:#80868b;margin-top:2px;">
                    @if($asn->deadline)
                        Tenggat: {{ \Carbon\Carbon::parse($asn->deadline)->isoFormat('D MMM YYYY, HH:mm') }}
                    @else
                        Tanpa batas waktu
                    @endif
                </div>
            </div>
            <i class="fas fa-chevron-right" style="color:#bbb;font-size:13px;"></i>
        </a>
        @endforeach
    </div>
    @endif

    {{-- Komentar Kelas --}}
    <div style="font-size:12px;font-weight:600;color:#80868b;text-transform:uppercase;letter-spacing:.8px;margin-bottom:12px;">
        Komentar kelas
    </div>
    <div class="comment-box d-flex" style="gap:12px;">
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
@endsection
