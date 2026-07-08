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
        'jadwal',
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
        return $this->hasManyThrough(
            Assignment::class,
            Material::class,
            'matakuliah_id', // foreign key di tabel materials
            'material_id',   // foreign key di tabel assignments
            'id',            // local key di tabel mata_kuliahs
            'id'             // local key di tabel materials
        );
    }

    /**
     * Semua mahasiswa yang terdaftar di kelas ini
     */
    public function enrolledStudents()
    {
        return $this->belongsToMany(User::class, 'kelas_mahasiswa', 'mata_kuliah_id', 'user_id', 'id', 'user_id')
                    ->wherePivot('status', 'accepted');
    }

    /**
     * Semua mahasiswa yang diinvite tapi pending
     */
    public function pendingStudents()
    {
        return $this->belongsToMany(User::class, 'kelas_mahasiswa', 'mata_kuliah_id', 'user_id', 'id', 'user_id')
                    ->wherePivot('status', 'pending');
    }

    /**
     * Semua pengajar (dosen/admin) untuk kelas ini
     */
    public function teachers()
    {
        return $this->belongsToMany(User::class, 'kelas_pengajar', 'mata_kuliah_id', 'user_id', 'id', 'user_id')
                    ->withPivot('role', 'status')
                    ->withTimestamps();
    }
}
