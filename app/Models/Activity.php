<?php

namespace App\Models;

class Activity extends Model
{
    protected $fillable = ['user_id','type','subject_type','subject_id'];

    public function subject()
    {
        return $this->morphTo();
    }
}
