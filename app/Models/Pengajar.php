<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajar extends Model
{
    use HasFactory;

    protected $table = 'pengajars';
    protected $primaryKey = 'id_pengajar';

    protected $fillable = [
        'user_id',
    ];

    // Relasi balik ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relasi ke Dosen (One-to-One)
    public function dosen()
    {
        return $this->hasOne(Dosen::class, 'id_pengajar', 'id_pengajar');
    }

    // Relasi ke ILB (One-to-One)
    public function ilb()
    {
        return $this->hasOne(Ilb::class, 'id_pengajar', 'id_pengajar');
    }
}