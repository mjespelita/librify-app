<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Types extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\TypesFactory> */
protected $fillable = ["name","isTrash"];
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $auditExclude = ['id'];

    public function generateTags(): array
    {
        return [
            'id:' . $this->id,
        ];
    }

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
