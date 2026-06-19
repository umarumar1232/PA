@extends('layouts.app_admin')

@section('content')
<div class="container-fluid">

    <a href="{{ route('admin.nilai_tugas.index') }}" class="btn btn-secondary mb-3">
        ← Kembali
    </a>

    <h4 class="mb-3">{{ $assignment->title }}</h4>

    <form action="{{ route('admin.nilai_tugas.store') }}" method="POST">
        @csrf

        <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">

        <div class="card">
            <div class="card-body">

                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Nilai</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($mahasiswa as $key => $mhs)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $mhs->name }}</td>
                            <td>{{ $mhs->nim }}</td>

                            <td>
                                @php
                                    $submission = $submissions[$mhs->id] ?? null;
                                @endphp

                                <input type="number"
                                    name="scores[{{ $mhs->id }}]"
                                    value="{{ $submission->score ?? '' }}"
                                    class="form-control form-control-sm"
                                    style="width:80px;">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <button type="submit" class="btn btn-success mt-3">
                    Simpan Nilai
                </button>

            </div>
        </div>

    </form>

</div>
@endsection