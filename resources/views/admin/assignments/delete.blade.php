@extends('layouts.classroom')

@section('content')
<div class="container">

<h4>Hapus Tugas</h4>

<p>Yakin ingin menghapus tugas <b>{{ $assignment->title }}</b>?</p>

<form action="{{ route('admin.assignments.destroy',$assignment->id) }}" method="POST">
@csrf
@method('DELETE')

<button class="btn btn-danger">Hapus</button>
<a href="{{ route('admin.assignments.index') }}" class="btn btn-secondary">Batal</a>

</form>

</div>
@endsection