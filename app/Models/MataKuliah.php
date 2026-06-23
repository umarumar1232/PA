<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    protected $table = 'mata_kuliahs';

    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'semester',
        'bagian',
        'tingkat',
        'mata_pelajaran',
        'ruang',
    ];

    /**
     * Semua materi/pertemuan dalam mata kuliah ini
     */
    public function materials()
    {
        return $this->hasMany(Material::class, 'matakuliah_id')->with('category')->orderBy('id');
    }

    /**
     * Semua tugas via relasi materials
     */
    public function assignments()
    {
        return Assignment::whereIn(
            'material_id',
            $this->materials()->pluck('id')
        );
    }

    /**
     * Semua mahasiswa yang terdaftar di kelas ini
     */
    public function enrolledStudents()
    {
        return $this->belongsToMany(User::class, 'kelas_mahasiswa', 'mata_kuliah_id', 'user_id', 'id', 'user_id');
    }
}
