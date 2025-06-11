<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Items extends Model implements Auditable
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

    use \OwenIt\Auditing\Auditable;

    protected $auditExclude = ['id'];

    public function generateTags(): array
    {
        return [
            'id:' . $this->id,
        ];
    }


    public function transformAudit(array $data): array
    {
        // Handle 'new_values' user name transformation
        if (isset($data['new_values']['types_id'])) {
            $types = \App\Models\Types::find($data['new_values']['types_id']);
            $data['new_values']['type_name'] = optional($types)->name;
            unset($data['new_values']['types_id']); // Hide the ID
        }

        // Handle 'old_values' types name transformation (for updates)
        if (isset($data['old_values']['types_id'])) {
            $types = \App\Models\Types::find($data['old_values']['types_id']);
            $data['old_values']['type_name'] = optional($types)->name;
            unset($data['old_values']['types_id']); // Hide the ID
        }

        return $data;
    }

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
