<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Tasks extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\TasksFactory> */
protected $fillable = ["name","status","projects_id","projects_workspaces_id","isTrash","deadline", "priority", "isScheduled", "isPrivate"];
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $auditExclude = ['id'];

    public function generateTags(): array
    {
        return [
            'id:' . $this->id,
        ];
    }

    public function transformAudit(array $data): array
    {
        // Handle 'new_values' project name transformation
        if (isset($data['new_values']['projects_id'])) {
            $project = \App\Models\projects::find($data['new_values']['projects_id']);
            $data['new_values']['project_name'] = optional($project)->name;
            unset($data['new_values']['projects_id']); // Hide the ID
        }

        // Handle 'old_values' project name transformation (for updates)
        if (isset($data['old_values']['projects_id'])) {
            $project = \App\Models\projects::find($data['old_values']['projects_id']);
            $data['old_values']['project_name'] = optional($project)->name;
            unset($data['old_values']['projects_id']); // Hide the ID
        }


        // Handle 'new_values' workspace name transformation
        if (isset($data['new_values']['projects_workspaces_id'])) {
            $workspace = \App\Models\Workspaces::find($data['new_values']['projects_workspaces_id']);
            $data['new_values']['workspace_name'] = optional($workspace)->name;
            unset($data['new_values']['projects_workspaces_id']); // Hide the ID
        }

        // Handle 'old_values' workspace name transformation (for updates)
        if (isset($data['old_values']['projects_workspaces_id'])) {
            $workspace = \App\Models\Workspaces::find($data['old_values']['projects_workspaces_id']);
            $data['old_values']['workspace_name'] = optional($workspace)->name;
            unset($data['old_values']['projects_workspaces_id']); // Hide the ID
        }

        return $data;
    }

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
