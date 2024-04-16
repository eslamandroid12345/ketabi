<?php

namespace App\Http\Resources\V1\LearnableAttachment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LearnableAttachmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->whenNotNull($this->title),
            'attachment_path' => $this->whenNotNull($this->attachmentPath),
        ];
    }
}
