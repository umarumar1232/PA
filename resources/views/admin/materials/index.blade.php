@extends('layouts.classroom')

@section('title', 'Data Materi')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="font-weight-normal text-muted mb-0">Manajemen Materi</h4>
        
        <a href="{{ route('admin.materials.create') }}" class="btn btn-primary" style="border-radius: 20px; padding: 6px 20px;">
            <i class="fas fa-plus mr-2"></i> Tambah Materi
        </a>
    </div>

    <div class="gc-card p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="gc-table">
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 25%">
                            <a href="{{ route('admin.materials.index', [
                                'sort' => 'title',
                                'direction' => ($sort == 'title' && $direction == 'asc') ? 'desc' : 'asc'
                            ]) }}" class="text-dark">
                                Nama Materi
                                @if($sort == 'title')
                                    {!! $direction == 'asc' ? '↑' : '↓' !!}
                                @endif
                            </a>
                        </th>
                        <th style="width: 30%">Deskripsi</th>
                        <th style="width: 10%">Lampiran</th>
                        <th style="width: 15%">
                            <a href="{{ route('admin.materials.index', [
                                'sort' => 'category_id',
                                'direction' => ($sort == 'category_id' && $direction == 'asc') ? 'desc' : 'asc'
                            ]) }}" class="text-dark">
                                Kategori
                                @if($sort == 'category_id')
                                    {!! $direction == 'asc' ? '↑' : '↓' !!}
                                @endif
                            </a>
                        </th>
                        <th style="width: 15%" class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materials as $key => $material)
                    <tr>
                        <td class="text-muted">{{ $key + 1 }}</td>
                        <td class="font-weight-medium text-dark">{{ $material->title }}</td>
                        <td class="text-muted small">{{ Str::limit($material->description, 50) }}</td>
                        <td>
                            <div class="d-flex flex-column gap-2">
                                @if($material->file)
                                    <a href="{{ asset('storage/'.$material->file) }}" target="_blank" class="badge badge-pill badge-light border mb-1" title="Lihat File">
                                        <i class="fas fa-file-alt text-primary mr-1"></i> File
                                    </a>
                                @endif
                                @if($material->video_url)
                                    <a href="{{ $material->video_url }}" target="_blank" class="badge badge-pill badge-light border" title="Lihat Video">
                                        <i class="fab fa-youtube text-danger mr-1"></i> Video
                                    </a>
                                @endif
                                @if(!$material->file && !$material->video_url)
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-pill badge-secondary px-3 py-1 font-weight-normal" style="font-size: 12px;">
                                {{ $material->category->name ?? '-' }}
                            </span>
                        </td>
                        <td class="text-right">
                            <a href="{{ route('admin.materials.edit', $material->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 20px;">
                                Edit
                            </a>

                            <form action="{{ route('admin.materials.destroy', $material) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 20px;" onclick="return confirm('Yakin hapus materi ini?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada materi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection