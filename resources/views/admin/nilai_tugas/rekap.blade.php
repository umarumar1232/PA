@extends('layouts.app_admin')

@section('content')
<div class="container-fluid">

    <h4 class="mb-3">Rekap Nilai Mahasiswa</h4>

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table custom-table table-bordered table-striped text-center align-middle table-hover ">
                <thead class="text-center align-middle">
                    <tr>
                        <th style="min-width:150px;">Nama</th>
                        <th style="min-width:100px;">NIM</th>

                        @foreach($assignments as $tugas)
                            <th>{{ $tugas->title }}</th>
                        @endforeach
                        <th style="min-width:100px;">Rata-rata</th>

                    </tr>
                </thead>

                <tbody>
                    @foreach($mahasiswa as $mhs)
                    <tr>
                        <td class="text-start" style="white-space: nowrap;">
                            {{ $mhs->nama }}
                        </td>
                        <td>{{ $mhs->mahasiswa->nim ?? '-' }}</td>

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

                            <td>{{ $nilai ?? '-' }}</td>

                        @endforeach

                        {{-- KOLOM RATA-RATA --}}
                        <td>
                            {{ $count > 0 ? round($total / $count, 1) : '-' }}
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
            

        </div>
    </div>

</div>
@endsection