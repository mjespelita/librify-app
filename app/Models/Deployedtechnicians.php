<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deployedtechnicians extends Model
{
    /** @use HasFactory<\Database\Factories\DeployedtechniciansFactory> */
protected $fillable = ["sites_id","technicians_id","isTrash"];
    use HasFactory;

    public function users()
    {
        return $this->belongsTo(User::class, 'technicians_id');
    }

    public function sites()
    {
        return $this->belongsTo(Sites::class, 'sites_id');
    }
}
