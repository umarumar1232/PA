<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\User;
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
        $pendingInvitations = $user->pendingClasses()->withCount('materials')->get();

        return view('mahasiswa.home.index', compact('mataKuliahs', 'pendingInvitations'));
    }

    /**
     * Gabung ke kelas menggunakan kode kelas
     */
    public function join(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'kode_kelas' => 'required|string|max:255',
        ]);

        $kodeKelas = strtolower(trim($request->input('kode_kelas')));

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

        $isTeacher = $this->checkEnrollment($mataKuliah);

        $categories = \App\Models\Category::with(['materials' => function ($query) use ($id) {
            $query->where('matakuliah_id', $id);
        }, 'materials.assignments'])->get();

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

        // Dapatkan pengajar dan mahasiswa aktif/pending
        $activeTeachers = $mataKuliah->teachers()->wherePivot('status', 'accepted')->get();
        $pendingTeachers = $mataKuliah->teachers()->wherePivot('status', 'pending')->get();
        $activeStudents = $mataKuliah->enrolledStudents()->get();
        $pendingStudents = $mataKuliah->pendingStudents()->get();

        // Dapatkan list mahasiswa sistem yang tidak ada di kelas ini (untuk diundang)
        $allStudents = collect();
        if ($isTeacher) {
            $allStudents = User::where('role', 'mahasiswa')
                ->whereNotIn('user_id', function($q) use ($id) {
                    $q->select('user_id')->from('kelas_mahasiswa')->where('mata_kuliah_id', $id);
                })->get();
        }

        return view('mahasiswa.kelas.show', compact(
            'mataKuliah',
            'assignments',
            'submissions',
            'categories',
            'isTeacher',
            'activeTeachers',
            'pendingTeachers',
            'activeStudents',
            'pendingStudents',
            'allStudents'
        ));
    }

    public function acceptStudentInvitation($id)
    {
        $user = Auth::user();
        \Illuminate\Support\Facades\DB::table('kelas_mahasiswa')
            ->where('mata_kuliah_id', $id)
            ->where('user_id', $user->user_id)
            ->update(['status' => 'accepted', 'updated_at' => now()]);

        $kelas = MataKuliah::findOrFail($id);

        return redirect()->route('mahasiswa.kelas.show', $id)
            ->with('success', 'Berhasil bergabung dengan kelas ' . $kelas->nama_mk);
    }

    public function declineStudentInvitation($id)
    {
        $user = Auth::user();
        \Illuminate\Support\Facades\DB::table('kelas_mahasiswa')
            ->where('mata_kuliah_id', $id)
            ->where('user_id', $user->user_id)
            ->delete();

        return redirect()->back()->with('success', 'Berhasil menolak undangan bergabung');
    }

    /**
     * Detail Materi
     */
    public function showMateri($kelasId, $id)
    {
        $mataKuliah = MataKuliah::with(['materials.category', 'materials.assignments'])->findOrFail($kelasId);
        
        $isTeacher = $this->checkEnrollment($mataKuliah);

        $material   = \App\Models\Material::with(['category', 'assignments.submissions'])->findOrFail($id);

        $user       = Auth::user();

        $activeTeachers = $mataKuliah->teachers()->wherePivot('status', 'accepted')->get();

        return view('mahasiswa.kelas.materi_show', compact('mataKuliah', 'material', 'isTeacher', 'activeTeachers'));
    }

    /**
     * Detail Tugas
     */
    public function showTugas($kelasId, $id)
    {
        $mataKuliah = MataKuliah::with(['materials.category'])->findOrFail($kelasId);

        $isTeacher = $this->checkEnrollment($mataKuliah);

        $tugas      = Assignment::with(['material.category', 'submissions'])->findOrFail($id);

        $user       = Auth::user();

        $submission     = Submission::where('assignment_id', $id)->where('user_id', $user->user_id)->first();
        $allSubmissions = $isTeacher ? Submission::with('user')->where('assignment_id', $id)->get() : collect();
        $activeTeachers = $mataKuliah->teachers()->wherePivot('status', 'accepted')->get();
        $activeStudents = $mataKuliah->enrolledStudents()->get();

        return view('mahasiswa.kelas.tugas_show', compact(
            'mataKuliah', 'tugas', 'isTeacher', 'submission', 'allSubmissions', 'activeTeachers', 'activeStudents'
        ));
    }

    /**
     * Submit Tugas
     */
    public function submitTugas(\Illuminate\Http\Request $request, $kelasId, $id)
    {
        $mataKuliah = MataKuliah::findOrFail($kelasId);
        $this->checkEnrollment($mataKuliah);

        if ($request->has('unsubmit')) {
            $user = Auth::user();
            $sub = Submission::where('assignment_id', $id)->where('user_id', $user->user_id)->first();
            if ($sub) {
                // Hapus file fisik jika ada
                if ($sub->file && \Illuminate\Support\Facades\Storage::disk('public')->exists($sub->file)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($sub->file);
                }
                $sub->delete();
            }
            return redirect()->back()->with('success', 'Pengumpulan dibatalkan.');
        }

        $request->validate([
            'file' => 'nullable|file|mimes:pdf,doc,docx,zip,ipynb,py|max:20480',
            'link' => 'nullable|url',
        ]);

        if (!$request->hasFile('file') && !$request->filled('link')) {
            return redirect()->back()->with('error', 'Harap unggah file atau masukkan link Colab.');
        }

        $user = Auth::user();
        $path = null;
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('submissions', 'public');
        }

        Submission::updateOrCreate(
            ['assignment_id' => $id, 'user_id' => $user->user_id],
            ['file' => $path, 'link' => $request->input('link'), 'submitted_at' => now()]
        );

        return redirect()->route('mahasiswa.kelas.tugas.show', [$kelasId, $id])
            ->with('success', 'Tugas berhasil dikumpulkan!');
    }

    /**
     * Cek otorisasi enrollment user di kelas ini
     */
    private function checkEnrollment($mataKuliah)
    {
        $user = Auth::user();
        
        $isTeacher = false;
        if ($user->role === 'admin' || $user->role === 'dosen') {
            $isTeacher = $mataKuliah->teachers()
                ->where('users.user_id', $user->user_id)
                ->wherePivot('status', 'accepted')
                ->exists();
        }

        $isStudent = false;
        if ($user->role === 'mahasiswa') {
            $isStudent = $user->enrolledClasses()
                ->where('mata_kuliahs.id', $mataKuliah->id)
                ->exists();
        }

        if (!$isTeacher && !$isStudent) {
            abort(403, 'Anda tidak terdaftar di kelas ini.');
        }

        return $isTeacher;
    }
}
