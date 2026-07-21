@extends('layouts.classroom')

@section('content')
<div class="container">
    <h3>Tambah Materi</h3>

    <form action="{{ route('admin.materials.store') }}" 
      method="POST" 
      enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Judul</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}">
            @error('title')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Upload File (PDF, Word, dll)</label>
          <input type="file" name="file[]" multiple class="form-control">
          <small class="text-muted">Bisa memilih lebih dari satu file.</small>
        </div>

        <div class="mb-3">
          <label class="form-label">URL Video YouTube (Opsional)</label>
          <input type="url" name="video_url" class="form-control" placeholder="https://youtube.com/...">
        </div>

        <div class="mb-3">
          <label class="form-label">Kategori / Pertemuan (Pilih atau Ketik Baru)</label>
          <div class="input-group">
            <select name="category_id" class="form-control">
              <option value="">-- Pilih Kategori --</option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
              @endforeach
            </select>
            <span class="input-group-text">ATAU</span>
            <input type="text" name="new_category" class="form-control" placeholder="Ketik kategori baru...">
          </div>
          <small class="text-muted">Isi input teks jika ingin membuat kategori baru.</small>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.materials.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection