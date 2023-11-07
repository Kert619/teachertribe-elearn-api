<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function roles(){
        return $this->belongsToMany(Role::class, 'permission_roles');
    }

    public function assignRole($roles){
        if(!is_array($roles)){
            $roles = [$roles];
        }

        $roleIds = array_map(function($role){
            return $role->id;
        }, $roles);

        $this->roles()->sync($roleIds);
    }
}
