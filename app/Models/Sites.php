<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Sites extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\SitesFactory> */
protected $fillable = ["name","phonenumber", "google_map_link", "users_id", "isTrash"];
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $auditExclude = ['id', "google_map_link"];

    public function generateTags(): array
    {
        return [
            'id:' . $this->id,
        ];
    }

    public function transformAudit(array $data): array
    {
        // Handle 'new_values' user name transformation
        if (isset($data['new_values']['users_id'])) {
            $user = \App\Models\User::find($data['new_values']['users_id']);
            $data['new_values']['user_name'] = optional($user)->name;
            unset($data['new_values']['users_id']); // Hide the ID
        }

        // Handle 'old_values' user name transformation (for updates)
        if (isset($data['old_values']['users_id'])) {
            $user = \App\Models\User::find($data['old_values']['users_id']);
            $data['old_values']['user_name'] = optional($user)->name;
            unset($data['old_values']['users_id']); // Hide the ID
        }

        return $data;
    }


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
