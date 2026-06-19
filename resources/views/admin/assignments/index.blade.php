@extends('layouts.classroom')

@section('title', 'Data Tugas')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="font-weight-normal text-muted mb-0">Manajemen Tugas</h4>
        
        <a href="{{ route('admin.assignments.create') }}" class="btn btn-primary" style="border-radius: 20px; padding: 6px 20px;">
            <i class="fas fa-plus mr-2"></i> Tambah Tugas
        </a>
    </div>

    <div class="gc-card p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="gc-table">
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 25%">
                            <a href="{{ route('admin.assignments.index', [
                                'sort' => 'title',
                                'direction' => ($sort == 'title' && $direction == 'asc') ? 'desc' : 'asc'
                            ]) }}" class="text-dark">
                                Judul Tugas
                                @if($sort == 'title')
                                    {!! $direction == 'asc' ? '↑' : '↓' !!}
                                @endif
                            </a>
                        </th>
                        <th style="width: 20%">Materi Terkait</th>
                        <th style="width: 20%">Deskripsi</th>
                        <th style="width: 15%">Deadline</th>
                        <th style="width: 15%" class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignments as $key => $assignment)
                    <tr>
                        <td class="text-muted">{{ $key + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="mr-3 bg-light text-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border-radius: 50%;">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                                <div>
                                    <div class="font-weight-medium text-dark">{{ $assignment->title }}</div>
                                    @if($assignment->notebook_url)
                                        <a href="{{ $assignment->notebook_url }}" target="_blank" class="small text-primary mt-1 d-inline-block">
                                            <i class="fas fa-external-link-alt mr-1"></i> Buka Notebook
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="text-muted small">
                            {{ $assignment->material->title ?? '-' }}
                        </td>
                        <td class="text-muted small">{{ Str::limit($assignment->description, 50) }}</td>
                        <td>
                            @if($assignment->deadline)
                                @php
                                    $deadline = \Carbon\Carbon::parse($assignment->deadline);
                                    $isPast = $deadline->isPast();
                                @endphp
                                <span class="{{ $isPast ? 'text-danger' : 'text-muted' }} small font-weight-medium">
                                    {{ $deadline->format('d M Y, H:i') }}
                                </span>
                            @else
                                <span class="text-muted small">Tanpa batas waktu</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <a href="{{ route('admin.assignments.edit', $assignment->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 20px;">
                                Edit
                            </a>

                            <form action="{{ route('admin.assignments.destroy', $assignment->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 20px;" onclick="return confirm('Yakin hapus tugas ini?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada tugas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection