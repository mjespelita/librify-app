<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    /** @use HasFactory<\Database\Factories\ItemsFactory> */
protected $fillable = [
    "itemId",
    "name",
    "model",
    "brand",
    "types_id",
    "description",
    "quantity",
    "serial_numbers",
    "unit",
    "isTrash"];
    use HasFactory;

    public function types()
    {
        return $this->belongsTo(Types::class, 'types_id');
    }

    public function logs()
    {
        return $this->hasMany(Itemlogs::class);
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
