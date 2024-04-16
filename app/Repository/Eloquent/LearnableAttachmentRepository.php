<?php

namespace App\Repository\Eloquent;

use App\Models\LearnableAttachment;
use App\Repository\LearnableAttachmentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class LearnableAttachmentRepository extends Repository implements LearnableAttachmentRepositoryInterface
{
    public function __construct(LearnableAttachment $model)
    {
        parent::__construct($model);
    }
}
