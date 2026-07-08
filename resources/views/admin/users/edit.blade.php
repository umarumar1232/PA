@extends('layouts.classroom')

@section('content')

<h4>Edit User</h4>

<form action="{{ route('admin.users.update', $user->user_id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="nama"
               value="{{ $user->nama }}"
               class="form-control">
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email"
               value="{{ $user->email }}"
               class="form-control">
    </div>

    @if ($user->user_id !== auth()->id())
        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-control">
                <option value="mahasiswa" {{ $user->role == 'mahasiswa' ? 'selected' : '' }}>
                    Mahasiswa
                </option>
                <option value="dosen" {{ in_array($user->role, ['dosen', 'ilb', 'admin']) ? 'selected' : '' }}>
                    Dosen / ILB
                </option>
            </select>
        </div>
    @endif

    <button class="btn btn-success">Update</button>
</form>

@endsection