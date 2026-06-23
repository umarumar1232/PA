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
      <a href="{{ url('/') }}" class="gc-navbar-brand d-flex align-items-center" style="text-decoration: none;">
        <i class="fas fa-graduation-cap text-primary mr-2" style="font-size: 26px;"></i>
        <span style="font-family: 'Google Sans', sans-serif; font-size: 22px; font-weight: 500; color: #5f6368;">
          <span style="color: #1a73e8; font-weight: 700;">E</span>-Learning
        </span>
      </a>
    </div>
    <div class="gc-navbar-right d-flex align-items-center">
      <button class="btn btn-link text-dark p-0 mr-3 d-flex align-items-center justify-content-center navbar-plus-btn" 
              data-toggle="modal" 
              data-target="{{ Auth::user()->role === 'admin' ? '#createClassModal' : '#joinClassModal' }}" 
              style="border-radius: 50%; width: 40px; height: 40px; text-decoration: none; transition: background-color 0.2s;" 
              title="{{ Auth::user()->role === 'admin' ? 'Buat Kelas' : 'Gabung Kelas' }}">
        <i class="fas fa-plus" style="font-size: 20px; color: #5f6368;"></i>
      </button>
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

  <style>
    .navbar-plus-btn:hover {
      background-color: #f1f3f4;
    }
    .navbar-plus-btn:focus {
      outline: none;
      box-shadow: none;
    }
  </style>

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
          $allMK = Auth::user()->enrolledClasses;
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

  @if(Auth::user()->role === 'admin')
  <!-- Create Class Modal -->
  <div class="modal fade" id="createClassModal" tabindex="-1" aria-labelledby="createClassModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 560px;">
      <div class="modal-content gc-modal-content">
        <form action="{{ route('admin.kelas.store') }}" method="POST" id="createClassForm">
          @csrf
          <div class="modal-header gc-modal-header">
            <h5 class="modal-title gc-modal-title" id="createClassModalLabel">Buat kelas</h5>
          </div>
          <div class="modal-body gc-modal-body">
            
            <div class="gc-md-input-group">
              <input type="text" name="nama_kelas" id="nama_kelas_input" placeholder=" " required autocomplete="off">
              <label>Nama kelas*</label>
            </div>
            <div class="gc-input-helper">*Wajib diisi</div>

            <div class="gc-md-input-group">
              <input type="text" name="bagian" id="bagian_input" placeholder=" " autocomplete="off">
              <label>Bagian</label>
            </div>

            <div class="gc-md-input-group">
              <input type="text" name="tingkat" id="tingkat_input" placeholder=" " autocomplete="off">
              <label>Tingkat</label>
            </div>

            <div class="gc-md-input-group">
              <input type="text" name="mata_pelajaran" id="mata_pelajaran_input" placeholder=" " autocomplete="off">
              <label>Mata pelajaran</label>
            </div>

            <div class="gc-md-input-group">
              <input type="text" name="ruang" id="ruang_input" placeholder=" " autocomplete="off">
              <label>Ruang</label>
            </div>

          </div>
          <div class="modal-footer gc-modal-footer">
            <button type="button" class="gc-modal-btn gc-modal-btn-cancel" data-dismiss="modal">Batal</button>
            <button type="submit" class="gc-modal-btn gc-modal-btn-submit" id="create_class_submit_btn" disabled>Buat</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <style>
    .gc-modal-content {
      background-color: #f0f4f9;
      border-radius: 28px;
      border: none;
      padding: 24px;
      box-shadow: 0 4px 30px rgba(0,0,0,0.15);
    }
    .gc-modal-header {
      border: none;
      padding: 0 0 24px 0;
    }
    .gc-modal-title {
      font-family: 'Google Sans', sans-serif;
      font-size: 24px;
      font-weight: 400;
      color: #1f1f1f;
    }
    .gc-modal-body {
      padding: 0;
    }
    .gc-md-input-group {
      position: relative;
      background-color: #e8edf4;
      border-radius: 4px 4px 0 0;
      border-bottom: 1px solid #444746;
      margin-bottom: 20px;
      height: 56px;
      transition: background-color 0.2s, border-bottom 0.2s;
    }
    .gc-md-input-group:focus-within {
      background-color: #dfe4ec;
      border-bottom: 2px solid #0b57d0;
    }
    .gc-md-input-group input {
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      background: transparent;
      border: none;
      outline: none;
      padding: 24px 16px 6px 16px;
      font-size: 16px;
      color: #1f1f1f;
      box-sizing: border-box;
    }
    .gc-md-input-group label {
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: #444746;
      font-size: 16px;
      transition: all 0.2s ease;
      pointer-events: none;
      margin: 0;
    }
    /* Float the label up when input has text or is focused */
    .gc-md-input-group input:focus ~ label,
    .gc-md-input-group input:not(:placeholder-shown) ~ label {
      top: 14px;
      font-size: 11px;
      color: #0b57d0;
    }
    .gc-md-input-group input:focus ~ label {
      color: #0b57d0;
    }
    .gc-input-helper {
      font-size: 11px;
      color: #444746;
      margin-top: -16px;
      margin-bottom: 20px;
      padding-left: 16px;
    }
    .gc-modal-footer {
      border: none;
      display: flex;
      justify-content: flex-end;
      gap: 8px;
      padding: 24px 0 0 0;
    }
    .gc-modal-btn {
      background: transparent;
      border: none;
      outline: none;
      font-family: 'Google Sans', sans-serif;
      font-size: 14px;
      font-weight: 500;
      padding: 10px 24px;
      border-radius: 100px;
      cursor: pointer;
      transition: background-color 0.2s;
    }
    .gc-modal-btn-cancel {
      color: #0b57d0;
    }
    .gc-modal-btn-cancel:hover {
      background-color: rgba(11, 87, 208, 0.08);
      text-decoration: none;
    }
    .gc-modal-btn-submit {
      color: #a8a8a8;
      cursor: not-allowed;
    }
    .gc-modal-btn-submit.active {
      color: #0b57d0;
      cursor: pointer;
    }
    .gc-modal-btn-submit.active:hover {
      background-color: rgba(11, 87, 208, 0.08);
      text-decoration: none;
    }
  </style>
  @endif

  @if(Auth::user()->role === 'mahasiswa')
  <!-- Join Class Modal -->
  <div class="modal fade" id="joinClassModal" tabindex="-1" aria-labelledby="joinClassModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 560px;">
      <div class="modal-content gc-modal-content">
        <form action="{{ route('mahasiswa.kelas.join') }}" method="POST" id="joinClassForm">
          @csrf
          <div class="modal-header gc-modal-header">
            <h5 class="modal-title gc-modal-title" id="joinClassModalLabel">Gabung ke kelas</h5>
          </div>
          <div class="modal-body gc-modal-body">
            <!-- User account box -->
            <div class="d-flex align-items-center p-3 mb-4 bg-white border rounded" style="border-radius: 8px !important; border-color: #dadce0 !important;">
              <img src="{{ Auth::user()->foto ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->nama).'&color=1a73e8&background=e8f0fe' }}" class="gc-avatar mr-3" style="width: 40px; height: 40px;">
              <div style="flex: 1; font-size: 13px; line-height: 1.4;">
                <div class="text-muted">Anda saat ini login sebagai</div>
                <div class="font-weight-bold text-dark">{{ Auth::user()->nama }}</div>
                <div class="text-muted">{{ Auth::user()->email }}</div>
              </div>
            </div>

            <p style="font-size: 14px; color: #3c4043; margin-bottom: 24px;">
              Mintalah kode kelas kepada pengajar, lalu masukkan kode tersebut di sini.
            </p>

            <div class="gc-md-input-group">
              <input type="text" name="kode_kelas" id="kode_kelas_input" placeholder=" " required autocomplete="off" style="text-transform: uppercase;">
              <label>Kode kelas</label>
            </div>
            <div class="gc-input-helper">Gunakan kode kelas yang valid (contoh: MK001)</div>

          </div>
          <div class="modal-footer gc-modal-footer">
            <button type="button" class="gc-modal-btn gc-modal-btn-cancel" data-dismiss="modal">Batal</button>
            <button type="submit" class="gc-modal-btn gc-modal-btn-submit" id="join_class_submit_btn" disabled>Gabung</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  @endif

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const drawerToggle = document.getElementById('drawerToggle');
      const drawer = document.getElementById('drawer');
      const mainContent = document.getElementById('mainContent');
      mainContent.classList.add('expanded')
      
      // Auto-open drawer on larger screens
      if(window.innerWidth >= 992) {
        drawer.classList.add('open');
        mainContent.classList.remove('expanded');
      }
      
      drawerToggle.addEventListener('click', function() {

        if(window.innerWidth >= 992) {
          if(drawer.classList.contains('closed')) {
            drawer.classList.remove('closed');
            mainContent.classList.remove('expanded');
          } else {
            drawer.classList.add('closed');
            mainContent.classList.add('expanded');
          }
        } else {
          drawer.classList.toggle('open');
        }
      });

      // Enable/disable Submit Button dynamically in Create Class Modal
      const nameInput = document.getElementById('nama_kelas_input');
      const submitBtn = document.getElementById('create_class_submit_btn');

      if (nameInput && submitBtn) {
        nameInput.addEventListener('input', function() {
          if (nameInput.value.trim() !== '') {
            submitBtn.removeAttribute('disabled');
            submitBtn.classList.add('active');
          } else {
            submitBtn.setAttribute('disabled', 'true');
            submitBtn.classList.remove('active');
          }
        });
      }

      // Enable/disable Submit Button dynamically in Join Class Modal
      const joinInput = document.getElementById('kode_kelas_input');
      const joinSubmitBtn = document.getElementById('join_class_submit_btn');

      if (joinInput && joinSubmitBtn) {
        joinInput.addEventListener('input', function() {
          if (joinInput.value.trim() !== '') {
            joinSubmitBtn.removeAttribute('disabled');
            joinSubmitBtn.classList.add('active');
          } else {
            joinSubmitBtn.setAttribute('disabled', 'true');
            joinSubmitBtn.classList.remove('active');
          }
        });
      }
    });
  </script>
  
  @stack('javascript')
  @stack('scripts')
</body>
</html>
