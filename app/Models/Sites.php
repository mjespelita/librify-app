<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sites extends Model
{
    /** @use HasFactory<\Database\Factories\SitesFactory> */
protected $fillable = ["name","phonenumber", "google_map_link", "users_id", "isTrash"];
    use HasFactory;

    public function onsites()
    {
        return $this->hasMany(Onsites::class);
    }

    public function damages()
    {
        return $this->hasMany(Damages::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function deployedTechnicians()
    {
        return $this->hasMany(Deployedtechnicians::class);
    }
}
