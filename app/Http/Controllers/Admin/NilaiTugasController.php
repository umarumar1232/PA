<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\User;
use App\Models\Submission;

class NilaiTugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assignments = Assignment::all();
        return view('admin.nilai_tugas.index', compact('assignments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $assignment_id = $request->assignment_id;
        $scores = $request->scores;

        foreach ($scores as $user_id => $score) {

            \App\Models\Submission::updateOrCreate(
                [
                    'assignment_id' => $assignment_id,
                    'user_id' => $user_id
                ],
                [
                    'score' => $score,
                    'status' => $score ? 'sudah' : 'belum'
                ]
            );
        }

        return redirect()->back()->with('success', 'Nilai berhasil disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $assignment = Assignment::with('material.mataKuliah')->findOrFail($id);
        $mataKuliah = $assignment->material->mataKuliah ?? null;
        $mahasiswa = $mataKuliah ? $mataKuliah->enrolledStudents()->get() : collect();

        $submissions = Submission::where('assignment_id', $id)
                        ->get()
                        ->keyBy('user_id');

        return view('admin.nilai_tugas.show', compact(
            'assignment',
            'mahasiswa',
            'submissions'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function rekap()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        
        $mahasiswaQuery = \App\Models\User::where('role', 'mahasiswa');
        $assignmentQuery = \App\Models\Assignment::query();

        if ($user && $user->role === 'dosen') {
            $mataKuliahIds = \App\Models\MataKuliah::whereHas('teachers', function($q) use ($user) {
                $q->where('users.user_id', $user->user_id);
            })->pluck('id');
            
            $mahasiswaQuery->whereHas('enrolledClasses', function($q) use ($mataKuliahIds) {
                $q->whereIn('mata_kuliahs.id', $mataKuliahIds);
            });

            $assignmentQuery->whereHas('material', function($q) use ($user) {
                $q->where('created_by', $user->user_id)
                  ->orWhereHas('mataKuliah.teachers', function($q2) use ($user) {
                      $q2->where('users.user_id', $user->user_id);
                  });
            });
        }

        $mahasiswa = $mahasiswaQuery->get();
        $assignments = $assignmentQuery->get();

        $submissions = \App\Models\Submission::all()
                        ->groupBy('user_id');

        return view('admin.nilai_tugas.rekap', compact(
            'mahasiswa',
            'assignments',
            'submissions'
        ));
    }
}
