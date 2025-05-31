<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workspaceusers extends Model
{
    /** @use HasFactory<\Database\Factories\WorkspaceusersFactory> */
protected $fillable = ["users_id","workspaces_id","isTrash"];
    use HasFactory;

    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function workspaces()
    {
        return $this->belongsTo(Workspaces::class, 'workspaces_id');
    }
}
