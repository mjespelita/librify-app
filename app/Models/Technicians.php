<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technicians extends Model
{
    /** @use HasFactory<\Database\Factories\TechniciansFactory> */
protected $fillable = ["name","email","password","isTrash"];
    use HasFactory;

    
}
