<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workspaces extends Model
{
    /** @use HasFactory<\Database\Factories\WorkspacesFactory> */
protected $fillable = ["name","isTrash", 'added_by_admin_type'];
    use HasFactory;

    public function projects()
    {
        return $this->hasMany(Projects::class);
    }

    public function workspaceUsers()
    {
        return $this->hasMany(Workspaceusers::class);
    }

    public function tasks()
    {
        return $this->hasMany(Tasks::class);
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
}
