<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $data = [
            ['name' => 'Pertemuan 1'],
            ['name' => 'Pertemuan 2'],
            ['name' => 'Pertemuan 3'],
            ['name' => 'Pertemuan 4'],
            ['name' => 'Pertemuan 5'],
            ['name' => 'Pertemuan 6'],
            ['name' => 'Pertemuan 7'],
            ['name' => 'Pertemuan 8'],
            ['name' => 'Pertemuan 9'],
            ['name' => 'Pertemuan 10'],
            ['name' => 'Pertemuan 11'],
            ['name' => 'Pertemuan 12'],
            ['name' => 'Pertemuan 13'],
            ['name' => 'Pertemuan 14'],
            ['name' => 'UTS'],
            ['name' => 'UAS'],
        ];

        \App\Models\Category::insert($data);
    }
}
