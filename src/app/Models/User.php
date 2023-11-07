<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\HasRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRole;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function users(){
        return $this->hasMany(User::class);
    }

    public function roles(){
        return $this->belongsToMany(Role::class, 'role_users');
    }

    public function classrooms(){
        return $this->hasMany(Classroom::class);
    }

    public function studentClassrooms(){
        return $this->belongsToMany(Classroom::class,'classrooms_students')->as('student_classrooms')->withTimestamps();
    }

    public function levels(){
        return $this->belongsToMany(Level::class, 'levels_users')->as('levels_passed')->withTimestamps();
    }

    public function permissions(){
        $roles = $this->roles;

        $permissions = $roles->flatMap(function($role){
            return $role->permissions->map(function($permission){
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'code' => $permission->code
                ];
            });
        })->toArray();

        return $permissions;
    }

    public function assignRole($roles){
        if(!is_array($roles)){
            $roles = [$roles];
        }

        $roleIds = array_map(function($role){
            return $role->id;
        },$roles);

        $this->roles()->attach($roleIds);
    }
}
