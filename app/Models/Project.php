<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Team;
use App\Models\Task;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['team_id','name','description','status'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function activities()
    {
        return $this->hasManyThrough(Activity::class, Task::class, 'project_id', 'subject_id')
            ->where('subject_type', Task::class)
            ->latest();
    }
}
