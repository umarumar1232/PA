@extends('layouts.app_admin')

@section('content')
<div class="container-fluid">

    <h4 class="mb-3">Data Mahasiswa</h4>

    <div class="row mb-3">
      <div class="col-md-6">
        <form method="GET" action="{{ route('admin.mahasiswa.index') }}">
            <div class="input-group">
                <input type="text" 
                       name="search" 
                       class="form-control" 
                       placeholder="Cari nama atau NIM..."
                       value="{{ request('search') }}">

                <button type="submit" class="btn btn-primary">
                    Cari
                </button>
            </div>
        </form>
      </div>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>NIM</th>
                <th>Dibuat</th>
            </tr>
        </thead>

        <tbody>
            @foreach($users as $key => $mhs)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $mhs->name }}</td>
                <td>{{ $mhs->email }}</td>
                <td>{{ $mhs->nim }}</td>
                <td>{{ $mhs->created_at }}</td>
            </tr>
            @endforeach
        </tbody>

    </table>

</div>
@endsection