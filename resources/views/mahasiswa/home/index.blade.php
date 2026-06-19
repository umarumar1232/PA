@extends('layouts.app_mahasiswa')

<style>
.card-header {
    padding: 14px 18px;
    font-weight: 600;
}
</style>

@section('content')
<div class="container-fluid">

    <h4 class="mb-3">Dashboard Mahasiswa</h4>

    <div class="card">
        <div class="card-body">

            {{-- Hay Syang --}}
            <div class="row mb-4">

                <div class="col-md-3">
                    <div class="card text-white bg-info shadow">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h3>{{ $materials->count() }}</h3>
                                <p class="mb-0">Jumlah Materi</p>
                            </div>
                            <i class="fas fa-book fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-white bg-success shadow">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h3>{{ $assignments->count() }}</h3>
                                <p class="mb-0">Jumlah Tugas</p>
                            </div>
                            <i class="fas fa-tasks fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-white bg-primary shadow">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h3>{{ $submissions->whereNotNull('file')->count() }}</h3>
                                <p class="mb-0">Sudah Dikerjakan</p>
                            </div>
                            <i class="fas fa-check fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-white bg-danger shadow">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h3>{{ $assignments->count() - $submissions->whereNotNull('file')->count() }}</h3>
                                <p class="mb-0">Belum Dikerjakan</p>
                            </div>
                            <i class="fas fa-times fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>

            </div>

            <hr>

            {{-- TABEL TUGAS --}}
            <div class="card mt-4">

                <div class="card-header">
                    <h5 class="mb-0">Daftar Tugas</h5>
                </div>

                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped text-center align-middle">

                            <thead>
                                <tr>
                                    <th class="text-start">Judul</th>
                                    <th>Deadline</th>
                                    <th>Status</th>
                                    <th>Nilai</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($assignments as $tugas)

                                    @php
                                        $submission = $submissions[$tugas->id] ?? null;
                                    @endphp

                                    <tr>
                                        <td class="text-start">{{ $tugas->title }}</td>

                                        <td>{{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y') }}</td>

                                        <td>
                                            @if($submission && $submission->file)
                                                <span class="badge bg-success">Sudah</span>
                                            @else
                                                <span class="badge bg-danger">Belum</span>
                                            @endif
                                        </td>

                                        <td>
                                            {{ $submission->score ?? '-' }}
                                        </td>
                                    </tr>

                                @endforeach
                            </tbody>

                        </table>
                    </div>

                </div>

            </div>

        </div>
    </div>

</div>

@endsection