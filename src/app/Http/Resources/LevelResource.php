<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LevelResource extends JsonResource
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
            'description' => $this->description,
            'initial_output' => $this->initial_output,
            'expected_output' => $this->expected_output,
            'is_passed' => $this->is_passed,
            'phase' => new PhaseResource($this->whenLoaded('phase'))
        ];
    }
}
