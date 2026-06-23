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
     * Halaman utama: daftar semua mata kuliah (kelas) yang diikuti
     */
    public function index()
    {
        $user = Auth::user();
        $mataKuliahs = $user->enrolledClasses()->withCount('materials')->get();

        return view('mahasiswa.home.index', compact('mataKuliahs'));
    }

    /**
     * Gabung ke kelas menggunakan kode kelas
     */
    public function join(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'kode_kelas' => 'required|string|max:255',
        ]);

        $kodeKelas = strtoupper(trim($request->input('kode_kelas')));

        // Find the class by its code (kode_mk)
        $kelas = MataKuliah::where('kode_mk', $kodeKelas)->first();

        if (!$kelas) {
            return redirect()->back()->with('error', 'Kelas dengan kode tersebut tidak ditemukan.');
        }

        $user = Auth::user();

        // Check if student is already enrolled in this class
        $isEnrolled = $user->enrolledClasses()->where('mata_kuliahs.id', $kelas->id)->exists();

        if ($isEnrolled) {
            return redirect()->back()->with('error', 'Anda sudah bergabung di kelas ini.');
        }

        // Enroll the student
        $user->enrolledClasses()->attach($kelas->id);

        return redirect()->route('mahasiswa.kelas.show', $kelas->id)
            ->with('success', 'Berhasil bergabung dengan kelas ' . $kelas->nama_mk);
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
