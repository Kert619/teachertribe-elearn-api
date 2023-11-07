<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function phase(){
        return $this->belongsTo(Phase::class);
    }

    public function users(){
        return $this->belongsToMany(User::class, 'levels_users')->as('levels_passed')->withTimestamps();
    }
}
