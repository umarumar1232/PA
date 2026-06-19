@extends('layouts.classroom')

@section('content')
<div class="container">

<h4>Tambah Tugas</h4>

<form action="{{ route('admin.assignments.store') }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="mb-3">
<label>Materi</label>
<select name="material_id" class="form-control">
@foreach($materials as $material)
<option value="{{ $material->id }}">
{{ $material->title }}
</option>
@endforeach
</select>
</div>

<div class="mb-3">
<label>Judul Tugas</label>
<input type="text" name="title" class="form-control">
</div>

<div class="mb-3">
<label>Deskripsi</label>
<textarea name="description" class="form-control"></textarea>
</div>

<div class="mb-3">
<label>Link Notebook</label>
<input type="text" name="notebook_url" class="form-control">
</div>

<div class="mb-3">
<label>File</label>
<input type="file" name="file" class="form-control">
</div>

<div class="mb-3">
<label>Deadline</label>
<input type="datetime-local" name="deadline" class="form-control">
</div>

<button class="btn btn-success">Simpan</button>
<a href="{{ route('admin.assignments.index') }}" class="btn btn-secondary">Kembali</a>

</form>

</div>
@endsection