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
        $jumlah_kelas = Material::count();
        $jumlah_mapel = Assignment::count();
        $jumlah_user = User::count();

        $mataKuliahs = MataKuliah::withCount('materials')->get();

        return view('admin.home.index', compact(
            'jumlah_siswa',
            'jumlah_guru',
            'jumlah_kelas',
            'jumlah_mapel',
            'jumlah_user',
            'mataKuliahs'
        ));
    }

    public function storeKelas(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'bagian' => 'nullable|string|max:255',
            'tingkat' => 'nullable|string|max:255',
            'mata_pelajaran' => 'nullable|string|max:255',
            'ruang' => 'nullable|string|max:255',
        ]);

        do {
            $kode_mk = strtolower(\Illuminate\Support\Str::random(7));
        } while (MataKuliah::where('kode_mk', $kode_mk)->exists());

        $tingkatInput = $request->input('tingkat');
        $semester = 1;
        if ($tingkatInput) {
            if (preg_match('/\d+/', $tingkatInput, $matches)) {
                $semester = (int)$matches[0];
            }
        }

        MataKuliah::create([
            'kode_mk' => $kode_mk,
            'nama_mk' => $request->nama_kelas,
            'semester' => $semester,
            'bagian' => $request->bagian,
            'tingkat' => $request->tingkat,
            'mata_pelajaran' => $request->mata_pelajaran,
            'ruang' => $request->ruang,
        ]);

        return redirect()->back()->with('success', 'Kelas berhasil dibuat');
    }

    public function destroyKelas($id)
    {
        $kelas = MataKuliah::findOrFail($id);
        $kelas->materials()->delete();
        $kelas->delete();

        return redirect()->back()->with('success', 'Kelas berhasil dihapus');
    }

    public function storeTugas(Request $request, $id)
    {
        $data = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'notebook_url' => 'nullable|url',
            'file' => 'nullable|file',
            'deadline' => 'nullable|date'
        ]);

        if ($request->hasFile('file')) {
            $data['file'] = $request->file('file')->store('assignments','public');
        }

        Assignment::create($data);

        return redirect()->back()->with('success', 'Tugas berhasil ditambahkan ke kelas ini');
    }
}