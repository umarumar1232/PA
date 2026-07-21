@extends('layouts.classroom')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Edit Materi</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.materials.update', $material) }}" 
                  method="POST" 
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Judul --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Judul</label>
                    <input type="text" 
                           name="title" 
                           class="form-control"
                           value="{{ old('title', $material->title) }}"
                           required>
                </div>

                {{-- Deskripsi --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Deskripsi</label>
                    <textarea name="description" 
                              class="form-control" 
                              rows="4"
                              required>{{ old('description', $material->description) }}</textarea>
                </div>

                {{-- File --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Upload File Tambahan/Baru</label>

                    @if($material->file && is_array($material->file))
                        <div class="mb-2">
                            <small class="text-muted">File saat ini:</small><br>
                            @foreach($material->file as $f)
                            <a href="{{ asset('storage/'.$f['path']) }}" 
                               target="_blank" 
                               class="text-decoration-none me-2">
                                <i class="fas fa-file"></i> {{ $f['name'] ?? basename($f['path']) }}
                            </a><br>
                            @endforeach
                        </div>
                    @endif

                    <input type="file" name="file[]" multiple class="form-control">
                    <small class="text-muted">Mengupload file baru akan menambahkannya ke daftar file yang ada.</small>
                </div>

                {{-- Video URL --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">URL Video YouTube (Opsional)</label>
                    <input type="text" 
                           name="video_url"
                           class="form-control"
                           value="{{ old('video_url', $material->video_url) }}">
                </div>

                {{-- Kategori --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">Kategori / Pertemuan (Pilih atau Ketik Baru)</label>
                    <div class="input-group">
                        <select name="category_id" class="form-select">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $material->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="input-group-text">ATAU</span>
                        <input type="text" name="new_category" class="form-control" placeholder="Ketik kategori baru...">
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.materials.index') }}" 
                       class="btn btn-secondary">
                        Kembali
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Update
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection