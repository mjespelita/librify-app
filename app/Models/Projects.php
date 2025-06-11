<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Projects extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\ProjectsFactory> */
protected $fillable = ["name","workspaces_id","isTrash"];
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
        // Handle 'new_values' workspace name transformation
        if (isset($data['new_values']['workspaces_id'])) {
            $workspace = \App\Models\Workspaces::find($data['new_values']['workspaces_id']);
            $data['new_values']['workspace_name'] = optional($workspace)->name;
            unset($data['new_values']['workspaces_id']); // Hide the ID
        }

        // Handle 'old_values' workspace name transformation (for updates)
        if (isset($data['old_values']['workspaces_id'])) {
            $workspace = \App\Models\Workspaces::find($data['old_values']['workspaces_id']);
            $data['old_values']['workspace_name'] = optional($workspace)->name;
            unset($data['old_values']['workspaces_id']); // Hide the ID
        }

        return $data;
    }

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
