@extends('layouts.app_admin')

@section('content')
@php use Illuminate\Support\Str; @endphp
<div class="container-fluid">

    <h4 class="mb-4">Tugas Mahasiswa</h4>

    <div class="row">
        @forelse($assignments as $tugas)
        
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">

                    <h5 class="card-title">
                        {{ $tugas->title }}
                    </h5>

                    <p class="card-text text-muted">
                        {{ Str::limit($tugas->description, 80) }}
                    </p>

                    <p class="mt-auto">
                        <strong>Deadline:</strong><br>
                        {{ $tugas->deadline 
                            ? \Carbon\Carbon::parse($tugas->deadline)->format('d M Y') 
                            : '-' }}
                    </p>

                    <a href="{{ route('admin.tugas_mahasiswa.show', $tugas->id) }}" 
                       class="btn btn-primary btn-sm mt-2">
                        Lihat Detail
                    </a>

                </div>
            </div>
        </div>

        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                Belum ada tugas
            </div>
        </div>
        @endforelse
    </div>

</div>

<style>
.card:hover {
    transform: translateY(-5px);
    transition: 0.2s;
}
</style>
@endsection