@extends('layouts.app_admin')

@section('content')
<div class="container-fluid">

    <a href="{{ route('admin.assignments.create') }}" class="btn btn-primary mb-3">
        Tambah Tugas
    </a>

    @if(session('success'))
        <div id="success-alert" class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>

                <th>
                    <a href="{{ route('admin.assignments.index', [
                        'sort' => 'title',
                        'direction' => ($sort == 'title' && $direction == 'asc') ? 'desc' : 'asc'
                    ]) }}">
                        Judul Tugas
                        @if($sort == 'title')
                            {!! $direction == 'asc' ? '↑' : '↓' !!}
                        @endif
                    </a>
                </th>

                <th>Materi</th>

                <th>Deskripsi</th>

                <th>Link Notebook</th>

                <th>Deadline</th>

                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @foreach($assignments as $key => $assignment)
            <tr>
                <td>{{ $key + 1 }}</td>

                <td>{{ $assignment->title }}</td>

                <td>
                    {{ $assignment->material->title ?? '-' }}
                </td>

                <td>{{ $assignment->description }}</td>

                <td>
                    @if($assignment->notebook_url)
                        <a href="{{ $assignment->notebook_url }}" 
                           target="_blank" 
                           class="btn btn-info btn-sm">
                            Buka Notebook
                        </a>
                    @else
                        -
                    @endif
                </td>

                <td>{{ $assignment->deadline }}</td>

                <td>

                    <a href="{{ route('admin.assignments.edit', $assignment->id) }}" 
                       class="btn btn-warning btn-sm">
                        Edit
                    </a>

                    <form action="{{ route('admin.assignments.destroy', $assignment->id) }}" 
                          method="POST" 
                          style="display:inline;">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Yakin hapus tugas ini?')">
                            Hapus
                        </button>
                    </form>

                </td>
            </tr>
            @endforeach
        </tbody>

    </table>

</div>

<script>
setTimeout(function () {
    let alert = document.getElementById('success-alert');
    if (alert) {
        alert.style.transition = "opacity 0.5s ease";
        alert.style.opacity = "0";
        setTimeout(() => alert.remove(), 500);
    }
}, 3000);
</script>

@endsection