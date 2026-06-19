@extends('layouts.classroom')

@section('content')
<div class="container-fluid">

    <h4 class="mb-3">
        Tugas: {{ $assignment->title }}
    </h4>

    <a href="{{ route('admin.tugas_mahasiswa.index') }}" class="btn btn-secondary mb-3">
        ← Kembali
    </a>

    <div class="card">
        <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Mahasiswa</th>
                        <th>Email</th>
                        <th>NIM</th>
                        <th>Status</th>
                        <th>Nilai</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($mahasiswa as $key => $mhs)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $mhs->name }}</td>
                        <td>{{ $mhs->email }}</td>
                        <td>{{ $mhs->nim }}</td>

                        @php
                            $submission = $submissions[$mhs->id] ?? null;
                        @endphp

                        <td>
                            @if($submission && $submission->file)
                                <span class="badge-status badge-sudah">Sudah Submit</span>
                            @else
                                <span class="badge-status badge-belum">Belum Submit</span>
                            @endif
                        </td>

                        <td>
                            {{ $submission->score ?? '-' }}
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            Tidak ada mahasiswa
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>

</div>
@endsection