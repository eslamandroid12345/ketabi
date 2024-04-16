<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearnableAttachment extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function attachmentPath():Attribute{
        return Attribute::make(get: fn()=> url($this->path));
    }
}
