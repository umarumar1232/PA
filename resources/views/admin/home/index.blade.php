@extends('layouts.classroom')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid py-4">
    {{-- Undangan Mengajar (Co-Teacher) --}}
    @if(isset($pendingInvitations) && !$pendingInvitations->isEmpty())
        <div class="mb-5">
            <h5 class="text-primary font-weight-bold mb-3"><i class="fas fa-envelope-open-text mr-2"></i>Undangan Mengajar</h5>
            <div class="row">
                @foreach($pendingInvitations as $inv)
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden; border-left: 5px solid #1a73e8;">
                            <div class="card-body p-3">
                                <h6 class="font-weight-bold text-dark mb-1">{{ $inv->nama_mk }}</h6>
                                <p class="text-muted small mb-2">
                                    <strong>Kelas:</strong> {{ $inv->bagian ?? 'Tanpa kelas' }}<br>
                                    <strong>Jadwal:</strong> {{ $inv->jadwal ?? 'Tanpa jadwal' }}<br>
                                    <strong>Subject:</strong> {{ $inv->mata_pelajaran ?? 'Tanpa subject' }}
                                </p>
                                <div class="d-flex justify-content-end" style="gap: 8px;">
                                    <form action="{{ route('admin.kelas.invitation.decline', $inv->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 20px; font-weight: 500; font-size: 12px;">Tolak</button>
                                    </form>
                                    <form action="{{ route('admin.kelas.invitation.accept', $inv->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary" style="border-radius: 20px; font-weight: 500; font-size: 12px; background-color: #1a73e8; border: none;">Terima</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="font-weight-normal text-muted mb-0">Daftar Kelas</h4>
    </div>

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
            <div class="gc-course-card-wrapper" style="position: relative;">
                <a href="{{ route('mahasiswa.kelas.show', $mk->id) }}" class="gc-course-card" style="text-decoration:none; color:inherit; height: 100%;">
                    <div class="gc-card-header" style="background-color: {{ $c['bg'] }}; color: {{ $c['text'] }}; position: relative; height: 110px; padding: 16px;">
                        <div style="font-family: 'Google Sans', sans-serif; font-size: 20px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 80%;">
                            {{ $mk->nama_mk }}
                        </div>
                        <div style="font-size: 13px; opacity: 0.9; margin-top: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $mk->bagian ?? 'Tanpa bagian' }}
                        </div>
                        <div style="font-size: 12px; opacity: 0.8; margin-top: 2px;">
                            {{ $mk->kode_mk }}
                        </div>
                        <!-- Avatar inisial -->
                        <div style="position: absolute; right: 16px; bottom: -28px; width: 56px; height: 56px; border-radius: 50%; background: white; color: {{ $c['bg'] }}; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 20px; border: 2px solid #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.2); z-index: 1;">
                            {{ $inisial }}
                        </div>
                    </div>
                    <div class="gc-card-body" style="padding: 36px 16px 12px 16px; min-height: 120px;">
                        <div class="mb-2" style="font-size: 13px; color: #5f6368;">
                            @if($mk->jadwal)
                                <div class="mb-1"><i class="far fa-calendar-alt mr-2" style="width: 16px;"></i>Jadwal: <strong>{{ $mk->jadwal }}</strong></div>
                            @endif
                            @if($mk->mata_pelajaran)
                                <div class="mb-1"><i class="fas fa-book mr-2" style="width: 16px;"></i>Subject: <strong>{{ $mk->mata_pelajaran }}</strong></div>
                            @endif
                            @if($mk->ruang)
                                <div class="mb-1"><i class="fas fa-door-open mr-2" style="width: 16px;"></i>Ruang: <strong>{{ $mk->ruang }}</strong></div>
                            @endif
                        </div>
                        <p class="text-muted small mb-0 mt-2">
                            <i class="fas fa-folder-open mr-1"></i> {{ $mk->materials_count }} pertemuan tersedia
                        </p>
                    </div>
                    <div class="gc-card-footer" style="border-top: 1px solid #e0e0e0; padding: 6px 16px; display: flex; justify-content: space-between; align-items: center; background-color: #f8f9fa;">
                        <div>
                            @if(Auth::user()->role === 'admin')
                                <form action="{{ route('admin.kelas.destroy', $mk->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini beserta seluruh materi dan tugas di dalamnya?');" style="display: inline;" onclick="event.stopPropagation();">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger p-0" title="Hapus Kelas" style="font-size: 14px; text-decoration: none;">
                                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                        <button class="btn btn-sm btn-outline-primary" style="border-radius: 16px; font-size: 12px; font-weight: 500; padding: 4px 12px;" onclick="event.preventDefault(); window.location='{{ route('mahasiswa.kelas.show', $mk->id) }}'">
                            Buka
                        </button>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="fas fa-chalkboard fa-4x mb-3 d-block text-light"></i>
                <h5 class="font-weight-normal">Belum ada kelas yang dibuat</h5>
                <p class="small">Klik tombol "+" di kanan atas atau tombol "Buat Kelas" untuk menambahkan kelas baru.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection