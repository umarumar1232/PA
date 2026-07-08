<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'role', // Pastikan role ada di sini
        'foto',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    

    // Relasi ke Submission
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    // Relasi ke Mahasiswa (One-to-One)
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'user_id', 'user_id');
    }

    // Relasi ke Pengajar (One-to-One)
    public function pengajar()
    {
        return $this->hasOne(Pengajar::class, 'user_id', 'user_id');
    }

    // Relasi ke Kelas (Many-to-Many via kelas_mahasiswa) - Hanya yang statusnya accepted
    public function enrolledClasses()
    {
        return $this->belongsToMany(MataKuliah::class, 'kelas_mahasiswa', 'user_id', 'mata_kuliah_id', 'user_id', 'id')
                    ->wherePivot('status', 'accepted');
    }

    // Relasi ke Kelas yang pending (undangan)
    public function pendingClasses()
    {
        return $this->belongsToMany(MataKuliah::class, 'kelas_mahasiswa', 'user_id', 'mata_kuliah_id', 'user_id', 'id')
                    ->wherePivot('status', 'pending');
    }
}
