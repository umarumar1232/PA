<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $table = 'assignments';

    protected $fillable = [
        'material_id',
        'type',
        'title',
        'description',
        'notebook_url',
        'file',
        'deadline',
    ];
    // Relasi ke Material
    public function material()
    {
        return $this->belongsTo(\App\Models\Material::class);
    }
    // Relasi ke Submission
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}