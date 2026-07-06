<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Material;
use App\Models\Assignment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 0. BUAT MATA KULIAH DEFAULT
        $mkId = DB::table('mata_kuliahs')->insertGetId([
            'kode_mk' => 'MK001',
            'nama_mk' => 'Pemrograman Web Lanjut',
            'semester' => 4,
            'bagian' => 'Kelas A',
            'jadwal' => 'Senin, 08:00 - 10:30',
            'mata_pelajaran' => 'Web Programming',
            'ruang' => 'Lab Komputer 1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 1. ADMIN / DOSEN (Owner Kelas)
        $dosen = User::firstOrCreate(
            ['email' => 'admin@mail.com'],
            [
                'nama' => 'Admin Dosen',
                'password' => Hash::make('password'),
                'role' => 'admin'
            ]
        );
        DB::table('kelas_pengajar')->insertOrIgnore([
            'user_id' => $dosen->user_id,
            'mata_kuliah_id' => $mkId,
            'role' => 'owner',
            'status' => 'accepted',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. ILB / Instruktur Laboratorium
        $ilb = User::firstOrCreate(
            ['email' => 'ilb@mail.com'],
            [
                'nama' => 'Instruktur Lab 1',
                'password' => Hash::make('password'),
                'role' => 'dosen' // Assuming 'dosen' role can also be used for ILB or if there's a specific role, adjust accordingly.
            ]
        );
        DB::table('kelas_pengajar')->insertOrIgnore([
            'user_id' => $ilb->user_id,
            'mata_kuliah_id' => $mkId,
            'role' => 'co-teacher',
            'status' => 'accepted',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. MAHASISWA (5 orang)
        for ($i = 1; $i <= 5; $i++) {
            $user = User::firstOrCreate(
                ['email' => 'mahasiswa'.$i.'@mail.com'],
                [
                    'nama' => 'Mahasiswa '.$i,
                    'password' => Hash::make('password'),
                    'role' => 'mahasiswa'
                ]
            );

            \App\Models\Mahasiswa::firstOrCreate(
                ['user_id' => $user->user_id],
                ['nim' => 'NIM00'.$i]
            );

            DB::table('kelas_mahasiswa')->insertOrIgnore([
                'user_id' => $user->user_id,
                'mata_kuliah_id' => $mkId,
                'status' => 'accepted',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 4. BUAT CATEGORY (Pertemuan) & MATERI & TUGAS/KUIS
        for ($i = 1; $i <= 5; $i++) {
            $category = Category::firstOrCreate([
                'name' => 'Pertemuan ' . $i
            ]);

            $material = Material::create([
                'title' => 'Materi Pertemuan ' . $i,
                'description' => 'Ini adalah materi untuk pertemuan ke-' . $i,
                'category_id' => $category->id,
                'matakuliah_id' => $mkId,
            ]);

            // 5 Tugas di setiap pertemuan (1 tugas per pertemuan)
            Assignment::create([
                'material_id' => $material->id,
                'type' => 'assignment',
                'title' => 'Tugas ' . $i,
                'description' => 'Kerjakan tugas pertemuan ' . $i,
                'deadline' => now()->addDays(7),
            ]);

            // 2 Quiz (diletakkan di Pertemuan 3 dan Pertemuan 5)
            if ($i == 3 || $i == 5) {
                Assignment::create([
                    'material_id' => $material->id,
                    'type' => 'quiz',
                    'title' => 'Kuis Pertemuan ' . $i,
                    'description' => 'Kerjakan kuis pilihan ganda pertemuan ' . $i,
                    'deadline' => now()->addDays(3),
                ]);
            }
        }
    }
}