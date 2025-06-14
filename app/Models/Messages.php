<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    /** @use HasFactory<\Database\Factories\MessagesFactory> */
protected $fillable = ["message","chats_id","chats_users_id","users_id","isTrash"];
    use HasFactory;

    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function chatUsersId()
    {
        return $this->belongsTo(User::class, 'chats_users_id');
    }

    public function chats()
    {
        return $this->belongsTo(Chats::class, 'chats_id');
    }
}
