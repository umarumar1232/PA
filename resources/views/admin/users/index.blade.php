@extends('layouts.classroom')

@section('title', 'Data User')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="font-weight-normal text-muted mb-0">Manajemen Pengguna</h4>
        
        <form method="GET" class="m-0">
            <select name="role" onchange="this.form.submit()" class="form-control form-control-sm" style="border-radius: 20px; padding: 4px 16px;">
                <option value="">Semua Role</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin / Dosen</option>
                <option value="mahasiswa" {{ request('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
            </select>
        </form>
    </div>

    <div class="gc-card p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="gc-table">
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 25%">Nama</th>
                        <th style="width: 30%">Email</th>
                        <th style="width: 20%">Role</th>
                        <th style="width: 20%" class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $key => $user)
                    <tr>
                        <td class="text-muted">{{ $key + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $user->foto ?? 'https://ui-avatars.com/api/?name='.urlencode($user->nama).'&color=1a73e8&background=e8f0fe' }}" alt="User" class="gc-avatar mr-3">
                                <strong>{{ $user->nama }}</strong>
                            </div>
                        </td>
                        <td class="text-muted">{{ $user->email }}</td>
                        <td>
                            <span class="badge badge-pill {{ $user->role == 'admin' ? 'badge-primary' : 'badge-success' }} px-3 py-1 font-weight-normal" style="font-size: 12px;">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="text-right">
                            <a href="{{ route('admin.users.edit', $user->user_id) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 20px;">
                                Edit
                            </a>

                            <form action="{{ route('admin.users.destroy', $user->user_id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Yakin hapus user?')" class="btn btn-sm btn-outline-danger" style="border-radius: 20px;">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Tidak ada data pengguna.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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