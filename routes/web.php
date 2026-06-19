<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AssignmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TugasMahasiswaController;
use App\Http\Controllers\Admin\NilaiTugasController;
use App\Models\Assignment;
use App\Models\Material;
use App\Models\Submission;
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

Route::get('/mahasiswa/home', function () {

    $user = auth()->user();

    $assignments = Assignment::all();

    $submissions = Submission::where('user_id', $user->id)
                    ->get()
                    ->keyBy('assignment_id');

    $materials = Material::all();

    return view('mahasiswa.home.index', compact(
        'assignments',
        'submissions',
        'materials'
    ));

})->name('mahasiswa.home');;

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