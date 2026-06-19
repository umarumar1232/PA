<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;

class MataKuliahController extends Controller
{
    /**
     * Halaman utama: daftar semua mata kuliah (kelas)
     */
    public function index()
    {
        $mataKuliahs = MataKuliah::all();

        return view('mahasiswa.home.index', compact('mataKuliahs'));
    }

    /**
     * Detail satu kelas: Forum, Tugas kelas, Orang
     */
    public function show($id)
    {
        $mataKuliah = MataKuliah::with([
            'materials.category',
            'materials.assignments.submissions',
        ])->findOrFail($id);

        $user = Auth::user();

        // Semua tugas dalam kelas ini
        $materialIds = $mataKuliah->materials->pluck('id');
        $assignments = Assignment::whereIn('material_id', $materialIds)
            ->with('material.category')
            ->orderBy('deadline')
            ->get();

        // Submission milik mahasiswa ini
        $submissions = Submission::where('user_id', $user->user_id)
            ->whereIn('assignment_id', $assignments->pluck('id'))
            ->get()
            ->keyBy('assignment_id');

        return view('mahasiswa.kelas.show', compact(
            'mataKuliah',
            'assignments',
            'submissions'
        ));
    }
}
