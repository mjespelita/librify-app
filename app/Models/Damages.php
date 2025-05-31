<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Damages extends Model
{
    /** @use HasFactory<\Database\Factories\DamagesFactory> */
protected $fillable = ["items_id","items_types_id","technicians_id","sites_id","quantity", "serial_numbers", "updated_by", "isTrash"];
    use HasFactory;

    public function items()
    {
        return $this->belongsTo(Items::class, 'items_id');
    }

    public function types()
    {
        return $this->belongsTo(Types::class, 'items_types_id');
    }

    public function technicians()
    {
        return $this->belongsTo(User::class, 'technicians_id');
    }

    public function sites()
    {
        return $this->belongsTo(Sites::class, 'sites_id');
    }
}
