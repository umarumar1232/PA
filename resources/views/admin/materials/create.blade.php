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
          <label class="form-label">Upload File (PDF, ipynb, dll)</label>
          <input type="file" name="file" class="form-control">
        </div>

        <div class="mb-3">
          <label class="form-label">Link Video (YouTube / dll)</label>
          <input type="url" name="video_url" class="form-control" placeholder="https://youtube.com/...">
        </div>

        <div class="mb-3">
          <label class="form-label">Kategori</label>
            <select name="category_id" class="form-control" required>
              <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $category)
                  <option value="{{ $category->id }}">
                  {{ $category->name }}
                  </option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.materials.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection