@extends('layouts/app_admin')

@section('title', $title ?? "")
@section('halaman', $halaman ?? "")

@section('content')
<div class="container-fluid">

    {{-- STATISTIC CARDS --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $jumlah_guru }}</h3>
                    <p>Jumlah Dosen</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $jumlah_siswa }}</h3>
                    <p>Jumlah Mahasiswa</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $jumlah_kelas }}</h3>
                    <p>Jumlah Materi</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $jumlah_user }}</h3>
                    <p>Jumlah User</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- CHART SECTION --}}
    <div class="col-lg-6">
      <div class="card shadow-sm">
        <div class="card-header text-center font-weight-bold">
            Distribusi Pengguna - Deep Learning
        </div>
        <div class="card-body d-flex justify-content-center align-items-center">
            <div style="width: 250px; height: 250px;">
                <canvas id="roleChart"></canvas>
            </div>
        </div>
      </div>
    </div>

        <!-- {{-- Future Expansion Column --}}
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header text-center font-weight-bold">
                    Ringkasan Sistem
                </div>
                <div class="card-body">
                    <p>Total Pengguna: <strong>{{ $jumlah_user }}</strong></p>
                    <p>Total Kelas: <strong>{{ $jumlah_kelas }}</strong></p>
                    <p>Total Assignment: <strong>{{ $jumlah_mapel ?? 0 }}</strong></p>
                    <hr>
                    <p class="text-muted mb-0">
                        Sistem e-learning mata kuliah Deep Learning berjalan normal.
                    </p>
                </div>
            </div>
        </div> -->
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const ctx = document.getElementById('roleChart');

    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Dosen', 'Mahasiswa'],
                datasets: [{
                    data: [{{ $jumlah_guru }}, {{ $jumlah_siswa }}],
                    backgroundColor: ['#17a2b8', '#28a745'],
                }]
            },
            options: {
                responsive: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

});
</script>
@endpush