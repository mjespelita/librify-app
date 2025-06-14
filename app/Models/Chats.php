<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chats extends Model
{
    /** @use HasFactory<\Database\Factories\ChatsFactory> */
protected $fillable = ["name","is_group","users_id","isTrash"];
    use HasFactory;

    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function chatParticipants()
    {
        return $this->hasMany(Chatparticipants::class);
    }

    public function messages()
    {
        return $this->hasMany(Messages::class);
    }
}
