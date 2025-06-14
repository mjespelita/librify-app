<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chatparticipants extends Model
{
    /** @use HasFactory<\Database\Factories\ChatparticipantsFactory> */
protected $fillable = ["chats_id","chats_users_id","users_id","isTrash"];
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

    public function messages()
    {
        return $this->hasMany(Messages::class);
    }
}
