@extends('layouts.classroom')

@section('title', 'Rekap Nilai')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="font-weight-normal text-muted mb-0">Rekap Nilai Mahasiswa</h4>
    </div>

    <div class="gc-card p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="gc-table">
                <thead class="text-center align-middle">
                    <tr>
                        <th class="text-left" style="min-width:200px;">Mahasiswa</th>
                        <th style="min-width:120px;">NIM</th>
                        @foreach($assignments as $tugas)
                            <th title="{{ $tugas->title }}" style="white-space: nowrap; max-width: 150px; overflow: hidden; text-overflow: ellipsis;">
                                {{ Str::limit($tugas->title, 15) }}
                            </th>
                        @endforeach
                        <th style="min-width:100px;" class="text-primary">Rata-rata</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($mahasiswa as $mhs)
                    <tr>
                        <td class="text-left" style="white-space: nowrap;">
                            <div class="d-flex align-items-center">
                                <img src="{{ $mhs->foto ?? 'https://ui-avatars.com/api/?name='.urlencode($mhs->nama).'&color=1a73e8&background=e8f0fe' }}" alt="User" class="gc-avatar mr-3" style="width: 28px; height: 28px;">
                                <strong class="text-dark">{{ $mhs->nama }}</strong>
                            </div>
                        </td>
                        <td class="text-center text-muted">{{ $mhs->mahasiswa->nim ?? '-' }}</td>

                        @php
                            $total = 0;
                            $count = 0;
                        @endphp

                        @foreach($assignments as $tugas)

                            @php
                                $nilai = optional(
                                    ($submissions[$mhs->user_id] ?? collect())
                                        ->where('assignment_id', $tugas->id)
                                        ->first()
                                )->score;

                                if ($nilai !== null) {
                                    $total += $nilai;
                                    $count++;
                                }
                            @endphp

                            <td class="text-center">
                                @if($nilai !== null)
                                    <span class="font-weight-medium">{{ $nilai }}</span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>

                        @endforeach

                        {{-- KOLOM RATA-RATA --}}
                        <td class="text-center font-weight-bold text-primary">
                            {{ $count > 0 ? round($total / $count, 1) : '-' }}
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ count($assignments) + 3 }}" class="text-center py-4 text-muted">Belum ada data mahasiswa atau tugas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection