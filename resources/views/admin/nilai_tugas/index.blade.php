@extends('layouts.app_admin')

@section('content')
<div class="container-fluid">

    <h4 class="mb-3">Nilai Tugas</h4>

    <div class="row">
        @foreach($assignments as $assignment)
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-2">{{ $assignment->title }}</h5>

                        <a href="{{ route('admin.nilai_tugas.show', $assignment->id) }}"
                           class="btn btn-primary btn-sm">
                            Lihat Nilai
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection