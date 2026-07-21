@extends('layouts.classroom')

@section('title', 'Daftar Tugas')

@section('content')
<div class="container-fluid py-4" style="max-width: 1000px; margin: 0 auto;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="font-weight-normal text-dark mb-0" style="font-family: 'Google Sans', sans-serif;">Daftar Tugas</h3>
    </div>

    @if($assignments->isEmpty())
        <div class="text-center py-5 text-muted gc-card">
            <i class="fas fa-clipboard-list fa-3x mb-3 d-block text-secondary"></i>
            <h5 style="font-family: 'Google Sans', sans-serif;">Belum ada tugas</h5>
            <p>Anda belum memiliki tugas dari kelas yang diikuti.</p>
        </div>
    @else
        <div class="gc-card p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 30%; border-top: none;">Tugas</th>
                            <th style="width: 25%; border-top: none;">Kelas</th>
                            <th style="width: 20%; border-top: none;">Tenggat Waktu</th>
                            <th style="width: 15%; border-top: none;" class="text-center">Status</th>
                            <th style="width: 10%; border-top: none;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignments as $assignment)
                            @php
                                $submission = $assignment->submissions->first();
                                $isDone = $submission && $submission->file;
                                $isPast = $assignment->deadline && \Carbon\Carbon::parse($assignment->deadline)->isPast();
                                $kelas = $assignment->material->mataKuliah ?? null;
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3 text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border-radius: 50%; background-color: {{ $isDone ? '#1e8e3e' : ($isPast ? '#ea4335' : '#1a73e8') }};">
                                            <i class="{{ $assignment->type === 'quiz' ? 'fas fa-question-circle' : 'fas fa-clipboard-list' }}"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-medium text-dark">{{ $assignment->title }}</div>
                                            <div class="small text-muted">{{ $assignment->type === 'quiz' ? 'Kuis' : 'Tugas' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    @if($kelas)
                                        <a href="{{ route('mahasiswa.kelas.show', $kelas->id) }}" class="text-primary" style="text-decoration: none; font-weight: 500;">
                                            {{ $kelas->nama_mk }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if($assignment->deadline)
                                        <span class="{{ $isPast && !$isDone ? 'text-danger font-weight-bold' : 'text-dark' }}">
                                            {{ \Carbon\Carbon::parse($assignment->deadline)->format('d M Y, H:i') }}
                                        </span>
                                    @else
                                        <span class="text-muted">Tanpa batas waktu</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    @if($isDone)
                                        <span class="badge badge-success px-2 py-1" style="font-size: 12px; font-weight: 500;"><i class="fas fa-check mr-1"></i> Diserahkan</span>
                                        @if($submission->score !== null)
                                            <div class="small text-muted mt-1">Nilai: <strong>{{ $submission->score }}</strong></div>
                                        @endif
                                    @else
                                        @if($isPast)
                                            <span class="badge badge-danger px-2 py-1" style="font-size: 12px; font-weight: 500;">Terlambat</span>
                                        @else
                                            <span class="badge badge-warning px-2 py-1" style="font-size: 12px; font-weight: 500; color: #856404;">Belum diserahkan</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    @if($kelas)
                                        <a href="{{ route('mahasiswa.kelas.tugas.show', [$kelas->id, $assignment->id]) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 20px; font-weight: 500;">
                                            Buka
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
