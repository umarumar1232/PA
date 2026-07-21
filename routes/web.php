<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AssignmentController;
use App\Http\Controllers\Admin\CategoryController;
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
    Route::get('/mahasiswa/tugas', [MataKuliahController::class, 'daftarTugas'])->name('mahasiswa.tugas.index');
    Route::get('/mahasiswa/kelas/{id}', [MataKuliahController::class, 'show'])->name('mahasiswa.kelas.show');
    Route::post('/mahasiswa/kelas/join', [MataKuliahController::class, 'join'])->name('mahasiswa.kelas.join');
    Route::post('/mahasiswa/kelas/invitation/{id}/accept', [MataKuliahController::class, 'acceptStudentInvitation'])->name('mahasiswa.kelas.invitation.accept');
    Route::post('/mahasiswa/kelas/invitation/{id}/decline', [MataKuliahController::class, 'declineStudentInvitation'])->name('mahasiswa.kelas.invitation.decline');

    // Detail Materi & Tugas (bisa diakses semua role)
    Route::get('/mahasiswa/kelas/{kelasId}/materi/{id}', [MataKuliahController::class, 'showMateri'])->name('mahasiswa.kelas.materi.show');
    Route::get('/mahasiswa/kelas/{kelasId}/tugas/{id}', [MataKuliahController::class, 'showTugas'])->name('mahasiswa.kelas.tugas.show');
    Route::post('/mahasiswa/kelas/{kelasId}/tugas/{id}/submit', [MataKuliahController::class, 'submitTugas'])->name('mahasiswa.kelas.tugas.submit');
    Route::post('/mahasiswa/comment', [MataKuliahController::class, 'storeComment'])->name('mahasiswa.comment.store');
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

        Route::post('/kelas/{id}/update', [DashboardController::class, 'updateKelas'])
            ->name('kelas.update');

        Route::post('/kelas/{id}/materi', [DashboardController::class, 'storeMateri'])
            ->name('kelas.materi.store');

        Route::post('/kelas/{id}/invite-teacher', [DashboardController::class, 'inviteTeacher'])
            ->name('kelas.invite-teacher');

        Route::post('/kelas/{id}/invite-students', [DashboardController::class, 'inviteStudents'])
            ->name('kelas.invite-students');

        Route::post('/kelas/invitation/{id}/accept', [DashboardController::class, 'acceptTeacherInvitation'])
            ->name('kelas.invitation.accept');

        Route::post('/kelas/invitation/{id}/decline', [DashboardController::class, 'declineTeacherInvitation'])
            ->name('kelas.invitation.decline');

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
        Route::resource('categories', CategoryController::class);

        
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