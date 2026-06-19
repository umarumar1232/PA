@extends('layouts.app_admin')

@section('content')
<div class="container">

<h4>Edit Tugas</h4>

<form action="{{ route('admin.assignments.update',$assignment->id) }}" method="POST" enctype="multipart/form-data">
@csrf
@method('PUT')

<div class="mb-3">
<label>Materi</label>
<select name="material_id" class="form-control">

@foreach($materials as $material)
<option value="{{ $material->id }}"
@if($assignment->material_id == $material->id) selected @endif>
{{ $material->title }}
</option>
@endforeach

</select>
</div>

<div class="mb-3">
<label>Judul Tugas</label>
<input type="text" name="title" value="{{ $assignment->title }}" class="form-control">
</div>

<div class="mb-3">
<label>Deskripsi</label>
<textarea name="description" class="form-control">{{ $assignment->description }}</textarea>
</div>

<div class="mb-3">
<label>Link Notebook</label>
<input type="text" name="notebook_url" value="{{ $assignment->notebook_url }}" class="form-control">
</div>

<div class="mb-3">
<label>File Baru</label>
<input type="file" name="file" class="form-control">
</div>

<div class="mb-3">
<label>Deadline</label>
<input type="datetime-local" name="deadline"
value="{{ $assignment->deadline }}"
class="form-control">
</div>

<button class="btn btn-success">Update</button>
<a href="{{ route('admin.assignments.index') }}" class="btn btn-secondary">Kembali</a>

</form>

</div>
@endsection