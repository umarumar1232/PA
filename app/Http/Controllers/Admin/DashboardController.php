<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Material;
use App\Models\Assignment;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $jumlah_siswa = User::where('role', 'mahasiswa')->count();
        $jumlah_guru = User::where('role', 'admin')->count();
        $jumlah_kelas = Material::count();
        $jumlah_mapel = Assignment::count();
        $jumlah_user = User::count();

        return view('admin.home.index', compact(
            'jumlah_siswa',
            'jumlah_guru',
            'jumlah_kelas',
            'jumlah_mapel',
            'jumlah_user'
    ));
    }
}