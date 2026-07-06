@extends('layouts.classroom')

@section('title', 'Penilaian: ' . $assignment->title)

@section('content')
<div style="display: flex; height: calc(100vh - 64px); overflow: hidden; background-color: #f8f9fa;">
    
    {{-- Sisi Kiri: Daftar Mahasiswa --}}
    <div style="width: 320px; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; background: white;">
        
        <div style="padding: 16px; border-bottom: 1px solid #e0e0e0;">
            <a href="{{ route('mahasiswa.kelas.show', $assignment->material->mataKuliah->id ?? 1) }}#nilai" class="btn btn-sm btn-light mb-3" style="border-radius: 20px;">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            <h5 class="mb-1" style="font-family: 'Google Sans', sans-serif; font-size: 18px;">{{ $assignment->title }}</h5>
            <div class="text-muted small">
                {{ $assignment->type === 'quiz' ? 'Kuis' : 'Tugas' }} &bull; {{ $submissions->count() }} diserahkan
            </div>
        </div>

        <div style="flex: 1; overflow-y: auto;">
            @forelse($mahasiswa as $mhs)
                @php
                    $sub = $submissions[$mhs->user_id] ?? null;
                    $isActive = request('student_id') == $mhs->user_id;
                    $hasFile = $sub && $sub->file;
                @endphp
                <a href="{{ route('admin.tugas_mahasiswa.show', $assignment->id) }}?student_id={{ $mhs->user_id }}" 
                   class="d-flex align-items-center" 
                   style="padding: 12px 16px; border-bottom: 1px solid #f1f3f4; text-decoration: none; background-color: {{ $isActive ? '#e8f0fe' : 'transparent' }}; color: inherit; transition: background-color 0.2s;">
                    
                    <img src="{{ $mhs->foto ?? 'https://ui-avatars.com/api/?name='.urlencode($mhs->name ?? $mhs->nama).'&color=1a73e8&background=e8f0fe' }}" class="gc-avatar mr-3" style="width: 32px; height: 32px;">
                    
                    <div style="flex: 1; overflow: hidden;">
                        <div style="font-size: 14px; font-weight: 500; color: {{ $isActive ? '#1a73e8' : '#3c4043' }}; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">
                            {{ $mhs->name ?? $mhs->nama }}
                        </div>
                        <div style="font-size: 12px; color: #80868b;">
                            @if($hasFile)
                                <span class="text-success"><i class="fas fa-check mr-1"></i>Diserahkan</span>
                            @else
                                <span class="text-danger">Belum mengumpulkan</span>
                            @endif
                        </div>
                    </div>

                    <div style="font-size: 13px; font-weight: 500; color: #5f6368;">
                        {{ $sub->score ?? '-' }}
                    </div>
                </a>
            @empty
                <div class="p-4 text-center text-muted small">
                    Tidak ada mahasiswa.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Sisi Kanan: PDF Viewer dan Form Penilaian --}}
    <div style="flex: 1; display: flex; flex-direction: column; overflow: hidden;">
        @php
            $activeStudentId = request('student_id') ?? ($mahasiswa->first()->user_id ?? null);
            $activeSubmission = $activeStudentId ? ($submissions[$activeStudentId] ?? null) : null;
        @endphp

        @if(!$activeStudentId)
            <div class="d-flex align-items-center justify-content-center" style="flex: 1; color: #80868b;">
                Pilih mahasiswa dari daftar di sebelah kiri.
            </div>
        @elseif($activeSubmission && $activeSubmission->file)
            
            <div style="flex: 1; display: flex;">
                {{-- Area PDF --}}
                <div style="flex: 1; padding: 16px; background-color: #525659; display: flex; flex-direction: column;">
                    <div class="mb-2 text-white d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-file-pdf mr-2"></i> File Pekerjaan Mahasiswa
                        </div>
                        <a href="{{ asset('storage/' . $activeSubmission->file) }}" target="_blank" class="btn btn-sm btn-outline-light" style="border-radius: 20px;">
                            Buka di Tab Baru <i class="fas fa-external-link-alt ml-1"></i>
                        </a>
                    </div>
                    <iframe src="{{ asset('storage/' . $activeSubmission->file) }}" style="width: 100%; height: 100%; border: none; border-radius: 8px; background: white;"></iframe>
                </div>

                {{-- Area Penilaian (Panel Kanan Kecil) --}}
                <div style="width: 300px; background: white; border-left: 1px solid #e0e0e0; padding: 24px; display: flex; flex-direction: column;">
                    <h6 class="font-weight-bold mb-4">Penilaian</h6>
                    
                    <form action="{{ route('admin.nilai_tugas.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
                        <input type="hidden" name="scores[{{ $activeStudentId }}]" id="hidden_score_input" value="{{ $activeSubmission->score }}">
                        
                        <div class="form-group mb-4">
                            <label class="text-muted small font-weight-bold">Nilai (0-100)</label>
                            <input type="number" class="form-control" name="scores[{{ $activeStudentId }}]" value="{{ $activeSubmission->score }}" min="0" max="100" style="font-size: 24px; font-weight: bold; text-align: center; height: 60px; border-radius: 12px; border: 2px solid #e0e0e0;" placeholder="-">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mt-4" style="border-radius: 20px;">
                            Beri Nilai
                        </button>
                        
                        @if(session('success'))
                            <div class="alert alert-success mt-3 small p-2 text-center" style="border-radius: 8px;">
                                <i class="fas fa-check-circle mr-1"></i> Tersimpan
                            </div>
                        @endif
                    </form>
                </div>
            </div>

        @else
            <div class="d-flex align-items-center justify-content-center flex-column" style="flex: 1; color: #80868b; background: white;">
                <img src="https://ssl.gstatic.com/classroom/empty_states_21/empty_state_no_submissions.png" alt="No Submission" style="max-width: 250px; opacity: 0.8; margin-bottom: 24px;">
                <h5 class="font-weight-normal text-dark mb-1">Belum ada file yang dilampirkan</h5>
                <p class="small">Mahasiswa ini belum mengumpulkan tugas.</p>
                
                {{-- Form Penilaian (Kosong) --}}
                <form action="{{ route('admin.nilai_tugas.store') }}" method="POST" class="mt-4" style="width: 250px;">
                    @csrf
                    <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
                    <div class="form-group mb-3">
                        <label class="text-muted small font-weight-bold">Beri Nilai Meskipun Belum Kumpul (Opsional)</label>
                        <input type="number" class="form-control text-center" name="scores[{{ $activeStudentId }}]" min="0" max="100" style="font-size: 18px; border-radius: 8px;" placeholder="0">
                    </div>
                    <button type="submit" class="btn btn-outline-primary w-100" style="border-radius: 20px;">
                        Simpan Nilai
                    </button>
                </form>
            </div>
        @endif
    </div>

</div>
@endsection