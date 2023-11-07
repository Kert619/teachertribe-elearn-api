<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassroomResource extends JsonResource
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
            'courses' => CourseResource::collection($this->whenLoaded('courses')),
            'teacher' => new UserResource($this->whenLoaded('user')),
            'students' => UserResource::collection($this->whenLoaded('students'))
        ];
    }
}
