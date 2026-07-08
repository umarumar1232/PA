<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $fillable = [
        'assignment_id',
        'question',
        'options',
        'correct_option',
        'points',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
        ];
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
}
