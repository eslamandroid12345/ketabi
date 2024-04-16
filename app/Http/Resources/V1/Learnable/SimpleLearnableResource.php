<?php

namespace App\Http\Resources\V1\Learnable;

use App\Http\Resources\V1\EducationalStage\EducationalStageResource;
use App\Http\Resources\V1\LearnableAttachment\LearnableAttachmentResource;
use App\Http\Resources\V1\Subject\SubjectResource;
use App\Http\Resources\V1\User\SimpleUserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SimpleLearnableResource extends JsonResource
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
            'type' => $this->whenNotNull($this->type),
            'type_value' => $this->type_value,
            'name_ar' => $this->whenNotNull($this->name_ar),
            'name_en' => $this->whenNotNull($this->name_en),
            'description_ar' => $this->whenNotNull($this->description_ar),
            'description_en' => $this->whenNotNull($this->description_en),
            'name' => $this->t('name'),
            'description' => $this->t('description'),
            'image' => $this->image_url,
            'teacher' => $this->when(in_array($this->type, ['public_package', 'private_package', 'attachment','attachment_lecture']), new SimpleUserResource($this->teacher)),
            'lectures_count' => $this->lectures_count,
            'subject' => $this->when($this->subject_id !== null, new SubjectResource($this->subject)),
            'educational_stage' => $this->when($this->educational_stage_id !== null, new EducationalStageResource($this->educationalStage)),
            'price' => $this->whenNotNull($this->price),
            'sort' => $this->whenNotNull($this->sort),
            'introduction_platform' => $this->when($this->introduction_platform !== null && $this->introduction_url !== null, $this->introduction_platform),
            'introduction_url' => $this->when($this->introduction_platform !== null && $this->introduction_url !== null, $this->introduction_url),
            'subscription_days' => $this->whenNotNull($this->subscription_days),
            'duration_in_days' => $this->whenNotNull($this->duration_in_days),
            'duration_in_hours' => $this->whenNotNull($this->duration_in_hours),
            'from' => $this->whenNotNull($this->from),
            'to' => $this->whenNotNull($this->to),
            'source_platform' => $this->source_platform,
            'source_url' => $this->source_url,
            'is_individually_sellable' => $this->whenNotNull($this->is_individually_sellable),
            'is_subscribed' => $this->is_subscribed,
            'subscription_ends_at' => $this->subscription_ends_at,
            'included_attachments' => $this->whenLoaded('learnableAttachments' , LearnableAttachmentResource::collection($this->learnableAttachments))
        ];
    }
}
