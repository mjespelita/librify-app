<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    /** @use HasFactory<\Database\Factories\CommentsFactory> */
protected $fillable = ["comment","tasks_id","tasks_projects_id","tasks_projects_workspaces_id","users_id","hasImage", "isTrash"];
    use HasFactory;

    public function tasks()
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

    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function files()
    {
        return $this->hasMany(CommentFiles::class);
    }
}
