@extends('layouts.classroom')

@section('title', 'Data Mahasiswa')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="font-weight-normal text-muted mb-0">Daftar Mahasiswa</h4>
        
        <form method="GET" action="{{ route('admin.mahasiswa.index') }}" class="m-0 w-25">
            <div class="input-group">
                <input type="text" 
                       name="search" 
                       class="form-control form-control-sm" 
                       placeholder="Cari nama atau NIM..."
                       value="{{ request('search') }}"
                       style="border-radius: 20px 0 0 20px;">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-sm btn-primary" style="border-radius: 0 20px 20px 0;">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="gc-card p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="gc-table">
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 30%">Nama</th>
                        <th style="width: 25%">Email</th>
                        <th style="width: 20%">NIM</th>
                        <th style="width: 20%">Terdaftar Pada</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $key => $mhs)
                    <tr>
                        <td class="text-muted">{{ $key + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $mhs->foto ?? 'https://ui-avatars.com/api/?name='.urlencode($mhs->nama).'&color=1a73e8&background=e8f0fe' }}" alt="User" class="gc-avatar mr-3">
                                <strong>{{ $mhs->nama }}</strong>
                            </div>
                        </td>
                        <td class="text-muted">{{ $mhs->email }}</td>
                        <td class="font-weight-medium">{{ $mhs->mahasiswa->nim ?? '-' }}</td>
                        <td class="text-muted">{{ $mhs->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Tidak ada mahasiswa yang ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection