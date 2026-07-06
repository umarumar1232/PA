<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Assignment;
use App\Models\Category;

class Material extends Model
{
    protected $table = 'materials';

    protected $fillable = [
        'title',
        'description',
        'file',
        'video_url',
        'category_id',
        'matakuliah_id'
    ];
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'material_id');
    }
    public function students()
    {
        return $this->belongsToMany(
            User::class,
            'material_id', // foreign key di enrollments untuk material
            'user_id'   // foreign key di enrollments untuk user
        );
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'matakuliah_id');
    }
}
