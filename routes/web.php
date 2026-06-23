<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AssignmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TugasMahasiswaController;
use App\Http\Controllers\Admin\NilaiTugasController;
use App\Http\Controllers\Mahasiswa\MataKuliahController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Mahasiswa Dashboard
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/mahasiswa/home', [MataKuliahController::class, 'index'])->name('mahasiswa.home');
    Route::get('/mahasiswa/kelas/{id}', [MataKuliahController::class, 'show'])->name('mahasiswa.kelas.show');
    Route::post('/mahasiswa/kelas/join', [MataKuliahController::class, 'join'])->name('mahasiswa.kelas.join');
});

/*
|--------------------------------------------------------------------------
| Admin / Dosen
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::post('/kelas', [DashboardController::class, 'storeKelas'])
            ->name('kelas.store');

        Route::delete('/kelas/{id}', [DashboardController::class, 'destroyKelas'])
            ->name('kelas.destroy');

        Route::post('/kelas/{id}/tugas', [DashboardController::class, 'storeTugas'])
            ->name('kelas.tugas.store');

        Route::get('/admin/mahasiswa', [UserController::class, 'mahasiswa'])
            ->name('mahasiswa.index');

        Route::resource('tugas_mahasiswa', TugasMahasiswaController::class)
            ->only(['index', 'show']);

        Route::get('/nilai_tugas', [NilaiTugasController::class, 'index'])
            ->name('nilai_tugas.index');

        Route::get('/nilai_tugas/{id}', [NilaiTugasController::class, 'show'])
            ->name('nilai_tugas.show');

        Route::post('/nilai_tugas', [NilaiTugasController::class, 'store'])
            ->name('nilai_tugas.store');

        Route::get('/rekap_nilai', [NilaiTugasController::class, 'rekap'])
            ->name('nilai_tugas.rekap');

        Route::resource('materials', MaterialController::class);
        Route::resource('users', UserController::class);
        Route::resource('assignments', AssignmentController::class);

        
});

/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
Google OAuth
|--------------------------------------------------------------------------
*/

Route::get('/auth/google', [GoogleController::class, 'redirect']);
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

require __DIR__.'/auth.php';