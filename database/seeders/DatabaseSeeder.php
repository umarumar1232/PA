<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Material;
use App\Models\Assignment;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 0. BUAT MATA KULIAH DEFAULT
        $mkId = \Illuminate\Support\Facades\DB::table('mata_kuliahs')->insertGetId([
            'kode_mk' => 'MK001',
            'nama_mk' => 'Pemrograman Web',
            'semester' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 1. BUAT CATEGORY
        $categories = [];

        for ($i = 1; $i <= 15; $i++) {
            $categories[] = Category::firstOrCreate([
                'name' => 'Pertemuan ' . $i
            ]);
        }

        // tambah UTS & UAS
        $categories[] = Category::firstOrCreate(['name' => 'UTS']);
        $categories[] = Category::firstOrCreate(['name' => 'UAS']);

        // 2. BUAT MATERIAL + ASSIGNMENT
        foreach ($categories as $index => $category) {

            $material = Material::create([
                'title' => 'Materi ' . $category->name,
                'description' => 'Pembahasan ' . $category->name,
                'category_id' => $category->id,
                'matakuliah_id' => $mkId,
            ]);

            Assignment::create([
                'material_id' => $material->id,
                'title' => 'Tugas ' . $category->name,
                'description' => 'Kerjakan tugas ' . $category->name,
                'deadline' => now()->addDays(7),
            ]);
        }

        // ADMIN
        User::firstOrCreate(
            ['email' => 'admin@mail.com'],
            [
                'nama' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin'
            ]
        );

        // MAHASISWA (5 orang)
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
        }
    }
}