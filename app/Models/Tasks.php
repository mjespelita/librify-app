<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    /** @use HasFactory<\Database\Factories\TasksFactory> */
protected $fillable = ["name","status","projects_id","projects_workspaces_id","isTrash","deadline", "priority", "isScheduled", "isPrivate"];
    use HasFactory;

    public function projects()
    {
        return $this->belongsTo(Projects::class, 'projects_id');
    }

    public function workspaces()
    {
        return $this->belongsTo(Workspaces::class, 'projects_workspaces_id');
    }

    public function taskAssignments()
    {
        return $this->hasMany(Taskassignments::class);
    }

    public function comments()
    {
        return $this->hasMany(Comments::class);
    }

    public function taskTimeLogs()
    {
        return $this->hasMany(Tasktimelogs::class);
    }

    public function internalNotifications()
    {
        return $this->hasMany(InternalNotification::class);
    }
}
