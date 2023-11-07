<?php

namespace App\Policies;

use App\Models\Lesson;
use App\Models\User;
class LessonPolicy
{

   public function viewAny(User $user){
      return $user->hasPermissionTo('can_view_lesson');
   }

   public function view(User $user, Lesson $lesson){
      if($user->hasPermissionTo('can_view_lesson')){
         if($user->isAdmin()){
            return true;
         }

         return $user->id == $lesson->user_id;
      }

      return false;
   }

   public function create(User $user){
      return $user->hasPermissionTo('can_create_lesson');
   }

   public function update(User $user, Lesson $lesson){
     if($user->hasPermissionTo('can_update_lesson')){
          return $user->id == $lesson->user_id;
     };

     return false;
   }

   public function delete(User $user,Lesson $lesson){
     if($user->hasPermissionTo('can_delete_lesson')){
          return $user->id == $lesson->user_id;
     };

    return false;
   }
}
