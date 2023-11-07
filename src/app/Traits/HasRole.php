<?php

namespace App\Traits;

use App\Models\Permission;
use App\Models\Role;

trait HasRole{
    public function hasRole($role){
        if (is_string($role)) {
            return $this->roles->contains('code', $role);
        }

        if ($role instanceof Role) {
          return $this->roles->contains('code', $role->code);
        }

        return false;
    }

    public function hasPermissionTo($permission){
        if(is_string($permission)){
            foreach ($this->roles as $role) {
               if($role->permissions->contains('code', $permission)){
                return true;
               }
            }
        }

        if($permission instanceof Permission){
           return $this->roles->contains('code', $permission->code);
        }

        return false;
    }

    public function isSuperAdmin(){
        return $this->roles->contains('code', 'super-admin');
    }

    public function isAdmin(){
       return $this->roles->contains('code', 'admin');
    }
}