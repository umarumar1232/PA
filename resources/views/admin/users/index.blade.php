@extends('layouts.app_admin')

@section('content')

<form method="GET" class="mb-3">
    <select name="role" onchange="this.form.submit()" class="form-control w-25">
        <option value="">-- Semua Role --</option>
        <option value="admin" {{ $role == 'admin' ? 'selected' : '' }}>Admin/Dosen</option>
        <option value="mahasiswa" {{ $role == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
    </select>
</form>

<table class="table table-bordered table-hover table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $key => $user)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $user->nama }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ ucfirst($user->role) }}</td>
            <td>
              <a href="{{ route('admin.users.edit', $user->user_id) }}"
              class="btn btn-warning btn-sm">Edit</a>

              <form action="{{ route('admin.users.destroy', $user->user_id) }}"
                method="POST"
                style="display:inline;">
                  @csrf
                  @method('DELETE')
                    <button onclick="return confirm('Yakin hapus user?')"
                      class="btn btn-danger btn-sm">
                      Hapus
                    </button>
              </form>
          </td>
        </tr>
        @endforeach
    </tbody>
</table>

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