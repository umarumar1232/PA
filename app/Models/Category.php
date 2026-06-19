<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function materials()
    {
        return $this->hasMany(Material::class);
    }
}
