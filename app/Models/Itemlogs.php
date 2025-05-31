<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itemlogs extends Model
{
    /** @use HasFactory<\Database\Factories\ItemlogsFactory> */
protected $fillable = ["items_id","reason","quantity","isTrash"];
    use HasFactory;

    public function items()
    {
        return $this->belongsTo(Items::class, 'items_id');
    }
}
