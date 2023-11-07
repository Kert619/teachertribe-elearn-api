<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phase extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function levels(){
        return $this->hasMany(Level::class);
    }

    public function quizzes(){
        return $this->hasMany(Quiz::class);
    }
}
