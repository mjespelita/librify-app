<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements MustVerifyEmail, Auditable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    use \OwenIt\Auditing\Auditable;

    protected $auditExclude = ['id', 'password'];

    public function generateTags(): array
    {
        return [
            'id:' . $this->id,
        ];
    }

    public function onsites()
    {
        return $this->hasMany(Onsites::class);
    }

    public function damages()
    {
        return $this->hasMany(Damages::class);
    }

    public function sites()
    {
        return $this->hasMany(Sites::class);
    }

    public function deployedTechnicians()
    {
        return $this->hasMany(Deployedtechnicians::class);
    }

    public function workspaceUsers()
    {
        return $this->hasMany(Workspaceusers::class);
    }

    public function taskAssignments()
    {
        return $this->hasMany(Taskassignments::class);
    }

    public function projects()
    {
        return $this->hasMany(Projects::class);
    }

    public function comments()
    {
        return $this->hasMany(Comments::class);
    }

    public function taskTimeLogs()
    {
        return $this->hasMany(Tasktimelogs::class);
    }

    public function internalNotifications()
    {
        return $this->hasMany(InternalNotification::class);
    }
}
