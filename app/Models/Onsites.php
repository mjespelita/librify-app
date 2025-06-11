<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Onsites extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\OnsitesFactory> */
protected $fillable = ["items_id","items_types_id","technicians_id", "sites_id", "quantity", "serial_numbers","updated_by","isTrash"];
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
        // Handle 'new_values' item name transformation
        if (isset($data['new_values']['items_id'])) {
            $item = \App\Models\Items::find($data['new_values']['items_id']);
            $data['new_values']['item_name'] = optional($item)->name;
            unset($data['new_values']['items_id']); // Hide the ID
        }

        // Handle 'old_values' item name transformation (for updates)
        if (isset($data['old_values']['items_id'])) {
            $item = \App\Models\Items::find($data['old_values']['items_id']);
            $data['old_values']['item_name'] = optional($item)->name;
            unset($data['old_values']['items_id']); // Hide the ID
        }

        // TYPES

        // Handle 'new_values' type name transformation
        if (isset($data['new_values']['items_types_id'])) {
            $type = \App\Models\Types::find($data['new_values']['items_types_id']);
            $data['new_values']['type_name'] = optional($type)->name;
            unset($data['new_values']['items_types_id']); // Hide the ID
        }

        // Handle 'old_values' type name transformation (for updates)
        if (isset($data['old_values']['items_types_id'])) {
            $type = \App\Models\Types::find($data['old_values']['items_types_id']);
            $data['old_values']['type_name'] = optional($type)->name;
            unset($data['old_values']['items_types_id']); // Hide the ID
        }

        // TECHNICIANS

        // Handle 'new_values' technician name transformation
        if (isset($data['new_values']['technicians_id'])) {
            $technician = \App\Models\User::find($data['new_values']['technicians_id']);
            $data['new_values']['technician_name'] = optional($technician)->name;
            unset($data['new_values']['technicians_id']); // Hide the ID
        }

        // Handle 'old_values' technician name transformation (for updates)
        if (isset($data['old_values']['technicians_id'])) {
            $technician = \App\Models\User::find($data['old_values']['technicians_id']);
            $data['old_values']['technician_name'] = optional($technician)->name;
            unset($data['old_values']['technicians_id']); // Hide the ID
        }

        // SITES

        // Handle 'new_values' site name transformation
        if (isset($data['new_values']['sites_id'])) {
            $site = \App\Models\Sites::find($data['new_values']['sites_id']);
            $data['new_values']['site_name'] = optional($site)->name;
            unset($data['new_values']['sites_id']); // Hide the ID
        }

        // Handle 'old_values' site name transformation (for updates)
        if (isset($data['old_values']['sites_id'])) {
            $site = \App\Models\Sites::find($data['old_values']['sites_id']);
            $data['old_values']['site_name'] = optional($site)->name;
            unset($data['old_values']['sites_id']); // Hide the ID
        }

        // USERS

        // Handle 'new_values' user name transformation
        if (isset($data['new_values']['updated_by'])) {
            $user = \App\Models\User::find($data['new_values']['updated_by']);
            $data['new_values']['updated_by'] = optional($user)->name;
            unset($data['new_values']['updated_by']); // Hide the ID
        }

        // Handle 'old_values' user name transformation (for updates)
        if (isset($data['old_values']['updated_by'])) {
            $user = \App\Models\User::find($data['old_values']['updated_by']);
            $data['old_values']['updated_by'] = optional($user)->name;
            unset($data['old_values']['updated_by']); // Hide the ID
        }

        return $data;
    }

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
