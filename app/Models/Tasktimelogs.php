<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasktimelogs extends Model
{
    /** @use HasFactory<\Database\Factories\TasktimelogsFactory> */
    protected $fillable = [
        "start_time",
        "pause_time",
        "stop_time",
        "elapsed_time", // Renamed from total_time to track accumulated time
        "users_id",
        "tasks_id",
        "tasks_projects_id",
        "tasks_projects_workspaces_id",
        "isTrash",
        "status" // Added to track whether the timer is running, paused, or stopped
    ];
    
    use HasFactory;

    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function taska()
    {
        return $this->belongsTo(Tasks::class, 'tasks_id');
    }

    public function projects()
    {
        return $this->belongsTo(Projects::class, 'tasks_projects_id');
    }

    public function workspaces()
    {
        return $this->belongsTo(Workspaces::class, 'tasks_projects_workspaces_id');
    }
}
