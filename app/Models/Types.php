<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Types extends Model
{
    /** @use HasFactory<\Database\Factories\TypesFactory> */
protected $fillable = ["name","isTrash"];
    use HasFactory;

    public function items()
    {
        return $this->hasMany(Items::class);
    }

    public function onsites()
    {
        return $this->hasMany(Onsites::class);
    }

    public function damages()
    {
        return $this->hasMany(Damages::class);
    }
}
