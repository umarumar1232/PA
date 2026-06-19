<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Http\Request;
use App\Models\Submission;
use App\Models\User;

class TugasMahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assignments = Assignment::latest()->get();

        return view('admin.tugas_mahasiswa.index', compact('assignments'));
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

            Submission::updateOrCreate(
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
        $assignment = Assignment::findOrFail($id);
        $mahasiswa = User::where('role', 'mahasiswa')->get();

        $submissions = Submission::where('assignment_id', $id)->get()
                        ->keyBy('user_id');

        return view('admin.tugas_mahasiswa.show', compact(
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
}
