<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectsFactory> */
protected $fillable = ["name","workspaces_id","isTrash"];
    use HasFactory;

    public function workspaces()
    {
        return $this->belongsTo(Workspaces::class, 'workspaces_id');
    }

    public function tasks()
    {
        return $this->hasMany(Tasks::class);
    }

    public function taskAssignments()
    {
        return $this->hasMany(Taskassignments::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comments::class);
    }

    public function taskTimeLogs()
    {
        return $this->hasMany(Tasktimelogs::class);
    }
}
