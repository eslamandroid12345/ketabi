<?php

namespace App\Models;

use App\Http\Traits\LanguageToggle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    use HasFactory,LanguageToggle;
    protected $guarded = [];
    public function getNameAttribute(){
        return $this->t('name');
    }
}
