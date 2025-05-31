<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalNotification extends Model
{
    protected $fillable = ["users_senders_id", "tasks_id", "notification"];
    use HasFactory;

    public function users()
    {
        return $this->belongsTo(User::class, 'users_senders_id');
    }

    public function tasks()
    {
        return $this->belongsTo(Tasks::class, 'tasks_id');
    }
}
