<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'created_by' => new UserResource($this->whenLoaded('user')),
            'created_users' => UserResource::collection($this->whenLoaded('users')),
            'teacherClassrooms' => ClassroomResource::collection($this->whenLoaded('classrooms')),
            'studentClassrooms' => ClassroomResource::collection($this->whenLoaded('studentClassrooms')),
            'levels' => LevelResource::collection($this->whenLoaded('levels'))
        ];
    }
}