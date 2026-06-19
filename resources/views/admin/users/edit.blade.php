@extends('layouts.app_admin')

@section('content')

<h4>Edit User</h4>

<form action="{{ route('admin.users.update', $user->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="name"
               value="{{ $user->name }}"
               class="form-control">
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email"
               value="{{ $user->email }}"
               class="form-control">
    </div>

    @if ($user->id !== auth()->id())
        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-control">
                <option value="mahasiswa" {{ $user->role == 'mahasiswa' ? 'selected' : '' }}>
                    Mahasiswa
                </option>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>
                    Admin
                </option>
            </select>
        </div>
    @endif

    <button class="btn btn-success">Update</button>
</form>

@endsection