<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Material;
use App\Models\Assignment;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $jumlah_siswa = User::where('role', 'mahasiswa')->count();
        $jumlah_guru = User::where('role', 'admin')->count();
        $jumlah_kelas = MataKuliah::count();
        $jumlah_mapel = Assignment::count();
        $jumlah_user = User::count();

        // Mengambil kelas yang diajar oleh user yang sedang login (status accepted)
        $mataKuliahs = MataKuliah::whereHas('teachers', function($q) {
            $q->where('users.user_id', auth()->user()->user_id)
              ->where('status', 'accepted');
        })->withCount('materials')->get();

        // Mengambil undangan mengajar yang pending
        $pendingInvitations = MataKuliah::whereHas('teachers', function($q) {
            $q->where('users.user_id', auth()->user()->user_id)
              ->where('status', 'pending');
        })->get();

        return view('admin.home.index', compact(
            'jumlah_siswa',
            'jumlah_guru',
            'jumlah_kelas',
            'jumlah_mapel',
            'jumlah_user',
            'mataKuliahs',
            'pendingInvitations'
        ));
    }

    public function storeKelas(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'bagian' => 'nullable|string|max:255',
            'jadwal' => 'nullable|string|max:255',
            'mata_pelajaran' => 'nullable|string|max:255',
            'ruang' => 'nullable|string|max:255',
        ]);

        do {
            $kode_mk = strtolower(\Illuminate\Support\Str::random(7));
        } while (MataKuliah::where('kode_mk', $kode_mk)->exists());

        $kelas = MataKuliah::create([
            'kode_mk' => $kode_mk,
            'nama_mk' => $request->nama_kelas,
            'semester' => 1,
            'bagian' => $request->bagian,
            'jadwal' => $request->jadwal,
            'mata_pelajaran' => $request->mata_pelajaran,
            'ruang' => $request->ruang,
        ]);

        // Secara otomatis jadikan pembuat kelas sebagai owner (accepted)
        $kelas->teachers()->attach(auth()->user()->user_id, [
            'role' => 'owner',
            'status' => 'accepted'
        ]);

        return redirect()->back()->with('success', 'Kelas berhasil dibuat');
    }

    public function destroyKelas($id)
    {
        $kelas = MataKuliah::findOrFail($id);
        
        foreach ($kelas->materials as $material) {
            foreach ($material->assignments as $assignment) {
                // Hapus pengumpulan tugas beserta filenya
                foreach ($assignment->submissions as $submission) {
                    if ($submission->file && \Illuminate\Support\Facades\Storage::disk('public')->exists($submission->file)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($submission->file);
                    }
                    $submission->delete();
                }
                
                // Hapus file lampiran tugas
                if ($assignment->file && \Illuminate\Support\Facades\Storage::disk('public')->exists($assignment->file)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($assignment->file);
                }
                $assignment->delete();
            }
            
            // Hapus file lampiran materi
            if ($material->file && \Illuminate\Support\Facades\Storage::disk('public')->exists($material->file)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($material->file);
            }
            $material->delete();
        }
        
        $kelas->delete();

        return redirect()->back()->with('success', 'Kelas berhasil dihapus');
    }

    public function storeTugas(Request $request, $id)
    {
        $data = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'type' => 'nullable|string|in:assignment,quiz',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date'
        ]);

        $filesData = [];
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $path = $file->store('assignments','public');
                $filesData[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path
                ];
            }
        }
        
        $data['file'] = count($filesData) > 0 ? $filesData : null;

        Assignment::create($data);

        return redirect()->back()->with('success', 'Tugas berhasil ditambahkan ke kelas ini');
    }

    public function updateKelas(Request $request, $id)
    {
        $kelas = MataKuliah::findOrFail($id);

        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'bagian' => 'nullable|string|max:255',
            'jadwal' => 'nullable|string|max:255',
            'mata_pelajaran' => 'nullable|string|max:255',
            'ruang' => 'nullable|string|max:255',
        ]);

        $kelas->update([
            'nama_mk' => $request->nama_kelas,
            'bagian' => $request->bagian,
            'jadwal' => $request->jadwal,
            'mata_pelajaran' => $request->mata_pelajaran,
            'ruang' => $request->ruang,
        ]);

        return redirect()->back()->with('success', 'Detail kelas berhasil diperbarui');
    }

    public function storeMateri(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            // category_id optional because we might have new_category
        ]);

        $categoryId = $request->category_id;
        if ($request->filled('new_category')) {
            $category = \App\Models\Category::firstOrCreate(['name' => $request->new_category]);
            $categoryId = $category->id;
        }

        $filesData = [];
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $path = $file->store('materials/files', 'public');
                $filesData[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path
                ];
            }
        }

        Material::create([
            'matakuliah_id' => $id,
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $categoryId,
            'file' => count($filesData) > 0 ? $filesData : null,
            'video_url' => $request->video_url,
            'created_by' => auth()->user()->user_id ?? null,
        ]);

        return redirect()->back()->with('success', 'Materi berhasil ditambahkan');
    }

    public function acceptTeacherInvitation($id)
    {
        $kelas = MataKuliah::findOrFail($id);
        $kelas->teachers()->updateExistingPivot(auth()->user()->user_id, [
            'status' => 'accepted'
        ]);

        return redirect()->back()->with('success', 'Berhasil menerima undangan mengajar di kelas ' . $kelas->nama_mk);
    }

    public function declineTeacherInvitation($id)
    {
        $kelas = MataKuliah::findOrFail($id);
        $kelas->teachers()->detach(auth()->user()->user_id);

        return redirect()->back()->with('success', 'Berhasil menolak undangan mengajar');
    }

    public function inviteTeacher(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)
                    ->whereIn('role', ['admin', 'dosen'])
                    ->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User dengan email tersebut tidak ditemukan atau bukan pengajar/dosen.');
        }

        $kelas = MataKuliah::findOrFail($id);

        $exists = $kelas->teachers()->where('users.user_id', $user->user_id)->exists();
        if ($exists) {
            return redirect()->back()->with('error', 'Pengajar tersebut sudah ada di kelas ini.');
        }

        $kelas->teachers()->attach($user->user_id, [
            'role' => 'co-teacher',
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Undangan pengajar berhasil dikirim ke ' . $user->email);
    }

    public function inviteStudents(Request $request, $id)
    {
        $kelas = MataKuliah::findOrFail($id);

        if ($request->has('user_ids')) {
            $userIds = $request->input('user_ids');
            foreach ($userIds as $userId) {
                $exists = \Illuminate\Support\Facades\DB::table('kelas_mahasiswa')
                    ->where('mata_kuliah_id', $id)
                    ->where('user_id', $userId)
                    ->exists();
                if (!$exists) {
                    \Illuminate\Support\Facades\DB::table('kelas_mahasiswa')->insert([
                        'mata_kuliah_id' => $id,
                        'user_id' => $userId,
                        'status' => 'pending',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            return redirect()->back()->with('success', 'Mahasiswa berhasil diundang.');
        }

        if ($request->has('emails')) {
            $emailsInput = $request->input('emails');
            $emails = preg_split('/[\s,]+/', $emailsInput, -1, PREG_SPLIT_NO_EMPTY);
            
            $invitedCount = 0;
            $notFound = [];

            foreach ($emails as $email) {
                $student = User::where('email', $email)->where('role', 'mahasiswa')->first();
                if ($student) {
                    $exists = \Illuminate\Support\Facades\DB::table('kelas_mahasiswa')
                        ->where('mata_kuliah_id', $id)
                        ->where('user_id', $student->user_id)
                        ->exists();
                    if (!$exists) {
                        \Illuminate\Support\Facades\DB::table('kelas_mahasiswa')->insert([
                            'mata_kuliah_id' => $id,
                            'user_id' => $student->user_id,
                            'status' => 'pending',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        $invitedCount++;
                    }
                } else {
                    $notFound[] = $email;
                }
            }

            $msg = "$invitedCount mahasiswa berhasil diundang.";
            if (count($notFound) > 0) {
                $msg .= " Email berikut tidak ditemukan di sistem: " . implode(', ', $notFound);
            }
            return redirect()->back()->with('success', $msg);
        }

        return redirect()->back()->with('error', 'Data input undangan tidak valid.');
    }
}