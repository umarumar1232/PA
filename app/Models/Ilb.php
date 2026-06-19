<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ilb extends Model
{
    use HasFactory;

    protected $table = 'ilbs';
    protected $primaryKey = 'ilb_id';

    protected $fillable = [
        'id_pengajar',
    ];

    // Relasi balik ke Pengajar
    public function pengajar()
    {
        return $this->belongsTo(Pengajar::class, 'id_pengajar', 'id_pengajar');
    }
}