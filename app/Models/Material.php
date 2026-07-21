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
        'matakuliah_id',
        'created_by'
    ];

    protected $casts = [
        'file' => 'array',
    ];
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'material_id');
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'matakuliah_id');
    }
}
