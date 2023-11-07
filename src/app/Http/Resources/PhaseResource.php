<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PhaseResource extends JsonResource
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
        'course' => new CourseResource($this->whenLoaded('course')),
        'levels' => LevelResource::collection($this->whenLoaded('levels')),
        'quizzes' => QuizResource::collection($this->whenLoaded('quizzes'))
        ];
    }
}
