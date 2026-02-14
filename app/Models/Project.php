<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Team;
use App\Models\Task;

class Project extends Model
{
    protected $fillable = ['team_id','name','description','status'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
