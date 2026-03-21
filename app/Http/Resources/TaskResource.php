<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->whenLoaded('category', fn($category) => [
                'id' => $this->category->uuid,
                'name' => $this->category->name,
            ]),
            'task_date' => new DateTimeResource($this->task_date,includeTime: false)->resource($request),
            'is_recurring' => new DateTimeResource($this->is_recurring)->resource($request),
            'completed_at' => new DateTimeResource($this->completed_at)->resource($request),
            'created_at' => new DateTimeResource($this->created_at)->resource($request),
            'updated_at' => new DateTimeResource($this->updated_at)->resource($request),
        ];
    }
}
