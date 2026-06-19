<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'E-Learning') }} @hasSection('title') - @yield('title') @endif</title>
  
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <!-- Font Awesome for Icons -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Bootstrap (Core structure) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  
  <!-- Google Classroom Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/classroom.css') }}">
  
  @stack('css')
</head>
<body>

  <!-- Top App Bar -->
  <nav class="gc-navbar">
    <div class="gc-navbar-left">
      <button class="gc-menu-btn" id="drawerToggle">
        <i class="fas fa-bars"></i>
      </button>
      <a href="{{ url('/') }}" class="gc-navbar-brand">
        <img src="https://upload.wikimedia.org/wikipedia/commons/5/59/Google_Classroom_Logo.png" alt="Logo" width="32" class="mr-2">
        <span class="d-none d-sm-inline">Google Classroom</span>
        <span class="d-inline d-sm-none">Classroom</span>
      </a>
    </div>
    <div class="gc-navbar-right">
      <div class="dropdown">
        <img src="{{ Auth::user()->foto ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->nama).'&color=1a73e8&background=e8f0fe' }}" class="gc-avatar dropdown-toggle" data-toggle="dropdown" alt="User Image">
        <div class="dropdown-menu dropdown-menu-right mt-2">
          <div class="dropdown-header text-center">
            <strong>{{ Auth::user()->nama }}</strong><br>
            <span class="text-muted">{{ Auth::user()->email }}</span>
          </div>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user-circle mr-2"></i> Profile</a>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt mr-2"></i> Logout</button>
          </form>
        </div>
      </div>
    </div>
  </nav>

  <!-- Side Drawer -->
  <div class="gc-drawer" id="drawer">
    <div class="py-2">
      {{-- Beranda --}}
      <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('mahasiswa.home') }}"
         class="gc-nav-item {{ request()->routeIs('admin.dashboard') || request()->routeIs('mahasiswa.home') ? 'active' : '' }}">
        <i class="fas fa-home"></i> Beranda
      </a>

      @if(Auth::user()->role !== 'admin')
        {{-- Kalender --}}
        <a href="#" class="gc-nav-item text-muted">
          <i class="far fa-calendar-alt"></i> Kalender
        </a>

        <div class="gc-nav-divider"></div>

        {{-- Daftar tugas --}}
        <a href="#" class="gc-nav-item text-muted">
          <i class="fas fa-list-check" style="font-size: 18px;"></i> Daftar tugas
        </a>

        <div class="gc-nav-divider"></div>

        {{-- Label "Terdaftar" dengan daftar kelas --}}
        <div class="px-4 py-1 text-muted small font-weight-bold" style="letter-spacing: 0.5px;">TERDAFTAR</div>

        @php
          $allMK = \App\Models\MataKuliah::all();
          $mkColors = ['#1a73e8','#1e8e3e','#e37400','#a142f4','#ea4335','#137333','#c5221f','#185abc'];
        @endphp

        @foreach($allMK as $i => $mk)
          @php
            $mkColor = $mkColors[$i % count($mkColors)];
            $mkInisial = strtoupper(substr($mk->nama_mk, 0, 1));
            $isActiveMK = request()->routeIs('mahasiswa.kelas.show') && request()->route('id') == $mk->id;
          @endphp
          <a href="{{ route('mahasiswa.kelas.show', $mk->id) }}"
             class="gc-nav-item {{ $isActiveMK ? 'active' : '' }}"
             style="align-items: center;">
            <span style="width: 28px; height: 28px; border-radius: 50%; background: {{ $mkColor }}; color: white; display: inline-flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; margin-right: 16px; flex-shrink: 0;">
              {{ $mkInisial }}
            </span>
            <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 14px; font-weight: 400;">
              {{ $mk->nama_mk }}
              <small class="d-block text-muted" style="font-size: 11px;">{{ $mk->kode_mk }}</small>
            </span>
          </a>
        @endforeach

        <div class="gc-nav-divider"></div>

        {{-- Kelas yang diarsipkan --}}
        <a href="#" class="gc-nav-item text-muted">
          <i class="fas fa-archive"></i> Kelas yang diarsipkan
        </a>

      @else
        {{-- ADMIN MENU --}}
        <div class="gc-nav-divider"></div>
        <div class="px-4 py-2 text-muted small font-weight-bold">MASTER DATA</div>

        <a href="{{ route('admin.users.index') }}" class="gc-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
          <i class="fas fa-users"></i> Data User
        </a>
        <a href="{{ route('admin.mahasiswa.index') }}" class="gc-nav-item {{ request()->routeIs('admin.mahasiswa.*') ? 'active' : '' }}">
          <i class="fas fa-user-graduate"></i> Data Mahasiswa
        </a>
        <a href="{{ route('admin.materials.index') }}" class="gc-nav-item {{ request()->routeIs('admin.materials.*') ? 'active' : '' }}">
          <i class="fas fa-book"></i> Data Materi
        </a>
        <a href="{{ route('admin.assignments.index') }}" class="gc-nav-item {{ request()->routeIs('admin.assignments.*') ? 'active' : '' }}">
          <i class="fas fa-tasks"></i> Data Tugas
        </a>

        <div class="gc-nav-divider"></div>
        <div class="px-4 py-2 text-muted small font-weight-bold">PENILAIAN</div>
        <a href="{{ route('admin.nilai_tugas.index') }}" class="gc-nav-item {{ request()->routeIs('admin.nilai_tugas.index') ? 'active' : '' }}">
          <i class="fas fa-check-circle"></i> Beri Nilai
        </a>
        <a href="{{ route('admin.nilai_tugas.rekap') }}" class="gc-nav-item {{ request()->routeIs('admin.nilai_tugas.rekap') ? 'active' : '' }}">
          <i class="fas fa-chart-bar"></i> Rekap Nilai
        </a>
      @endif

      <div class="gc-nav-divider"></div>
      <a href="{{ route('profile.edit') }}" class="gc-nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
        <i class="fas fa-cog"></i> Setelan
      </a>
    </div>
  </div>

  <!-- Main Content -->
  <div class="gc-main-content" id="mainContent">
    
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 8px;">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif
    
    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 8px;">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    @yield('content')
  </div>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const drawerToggle = document.getElementById('drawerToggle');
      const drawer = document.getElementById('drawer');
      const mainContent = document.getElementById('mainContent');
      
      // Auto-open drawer on larger screens
      if(window.innerWidth >= 992) {
        drawer.classList.add('open');
        mainContent.classList.add('expanded');
      }
      
      drawerToggle.addEventListener('click', function() {
        if(window.innerWidth >= 992) {
          if(drawer.classList.contains('closed')) {
            drawer.classList.remove('closed');
            mainContent.classList.add('expanded');
          } else {
            drawer.classList.add('closed');
            mainContent.classList.remove('expanded');
          }
        } else {
          drawer.classList.toggle('open');
        }
      });
    });
  </script>
  
  @stack('javascript')
  @stack('scripts')
</body>
</html>
