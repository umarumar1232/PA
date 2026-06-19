@extends('layouts.app_admin')

@section('content')
<div class="container-fluid">

    <a href="{{ route('admin.materials.create') }}" class="btn btn-primary mb-3">
        Tambah Materi
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
                    <a href="{{ route('admin.materials.index', [
                        'sort' => 'title',
                        'direction' => ($sort == 'title' && $direction == 'asc') ? 'desc' : 'asc'
                    ]) }}">
                        Nama Materi
                        @if($sort == 'title')
                            {!! $direction == 'asc' ? '↑' : '↓' !!}
                        @endif
                    </a>
                </th>

                <th>Deskripsi</th>

                <th>File</th>

                <th>Video</th>

                <th>
                    <a href="{{ route('admin.materials.index', [
                        'sort' => 'category_id',
                        'direction' => ($sort == 'category_id' && $direction == 'asc') ? 'desc' : 'asc'
                    ]) }}">
                        Kategori
                        @if($sort == 'category_id')
                            {!! $direction == 'asc' ? '↑' : '↓' !!}
                        @endif
                    </a>
                </th>

                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materials as $key => $material)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $material->title }}</td>
                <td>{{ $material->description }}</td>
                <td>
                    @if($material->file)
                        <a href="{{ asset('storage/'.$material->file) }}" target="_blank">
                            Lihat File
                        </a>
                    @endif
                </td>

                <td>
                @if($material->video_url)
                    <a href="{{ $material->video_url }}" 
                        target="_blank" 
                        class="btn btn-danger btn-sm">
                        Lihat Video
                    </a>
                    @else
                    -
                    @endif
                </td>

                <td>
                @if($material->category)
                    {{ $material->category->name }}
                    @else
                        -
                    @endif
                </td>

                <td>
                    <a href="{{ route('admin.materials.edit', $material->id) }}" class="btn btn-warning btn-sm">Edit</a>

                    <form action="{{ route('admin.materials.destroy', $material) }}" 
                        method="POST" 
                        style="display:inline;">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Yakin hapus materi ini?')">
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
    }, 3000); // 3000ms = 3 detik
</script>

@endsection