@extends('layouts.app_admin')

@section('content')

<h4>Tambah User</h4>

<form action="{{ route('admin.users.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="name" class="form-control">
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control">
    </div>

    <div class="mb-3">
        <label>Role</label>
        <select name="role" class="form-control">
            <option value="admin">Admin/Dosen</option>
        </select>
    </div>

    <button class="btn btn-success">Simpan</button>
</form>

@endsection