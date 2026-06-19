@extends('layouts.classroom')

@section('title', 'Beri Nilai')

@section('content')
<div class="container-fluid py-4">

    <a href="{{ route('admin.nilai_tugas.index') }}" class="btn btn-sm btn-outline-secondary mb-4" style="border-radius: 20px;">
        <i class="fas fa-arrow-left mr-2"></i> Kembali
    </a>

    <!-- Detail Tugas -->
    <div class="gc-card p-4 mb-4" style="border-top: 4px solid #1a73e8;">
        <div class="d-flex align-items-center mb-2">
            <div class="mr-3 bg-primary text-white d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; border-radius: 50%;">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <div>
                <h3 class="mb-0 text-dark">{{ $assignment->title }}</h3>
                <div class="text-muted small">Batas waktu: {{ $assignment->deadline ? \Carbon\Carbon::parse($assignment->deadline)->format('d M Y, H:i') : 'Tanpa batas waktu' }}</div>
            </div>
        </div>
        <hr>
        <p class="text-muted">{{ $assignment->description }}</p>
    </div>

    <!-- Tabs untuk mirip GC -->
    <div class="gc-tabs">
        <a href="#" class="gc-tab active">Penilaian Siswa</a>
        <a href="#" class="gc-tab text-muted" style="cursor: not-allowed;">Detail Tugas</a>
    </div>

    <form action="{{ route('admin.nilai_tugas.store') }}" method="POST">
        @csrf
        <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">

        <div class="gc-card p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="gc-table">
                    <thead>
                        <tr>
                            <th style="width: 5%">No</th>
                            <th style="width: 35%">Mahasiswa</th>
                            <th style="width: 20%">NIM</th>
                            <th style="width: 20%">Status</th>
                            <th style="width: 20%">Nilai (0-100)</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($mahasiswa as $key => $mhs)
                            @php
                                // Note: Depending on logic, $mhs might be user or mahasiswa
                                // Assuming $mhs is User from Admin controller logic
                                $submission = $submissions[$mhs->user_id] ?? null;
                            @endphp
                        <tr>
                            <td class="text-muted">{{ $key+1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $mhs->foto ?? 'https://ui-avatars.com/api/?name='.urlencode($mhs->nama).'&color=1a73e8&background=e8f0fe' }}" alt="User" class="gc-avatar mr-3">
                                    <strong class="text-dark">{{ $mhs->nama }}</strong>
                                </div>
                            </td>
                            <td class="text-muted">{{ $mhs->mahasiswa->nim ?? '-' }}</td>
                            <td>
                                @if($submission && $submission->file)
                                    <span class="badge badge-success">Diserahkan</span>
                                @else
                                    <span class="badge badge-danger">Belum</span>
                                @endif
                            </td>
                            <td>
                                <div class="input-group input-group-sm" style="width: 100px;">
                                    <input type="number"
                                        name="scores[{{ $mhs->user_id }}]"
                                        value="{{ $submission->score ?? '' }}"
                                        class="form-control text-center"
                                        min="0" max="100"
                                        placeholder="-">
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada mahasiswa.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white text-right p-3 border-top">
                <button type="submit" class="btn btn-primary px-4" style="border-radius: 20px;">
                    Simpan Nilai
                </button>
            </div>
        </div>

    </form>

</div>
@endsection