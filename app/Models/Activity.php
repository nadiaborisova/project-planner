<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['user_id','type','subject_type','subject_id'];

    public function subject()
    {
        return $this->morphTo();
    }
}
