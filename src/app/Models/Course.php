<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function phases(){
        return $this->hasMany(Phase::class);
    }

    public function classrooms(){
        return $this->belongsToMany(Classroom::class, 'classrooms_courses')->as('classrooms');
    }
}
