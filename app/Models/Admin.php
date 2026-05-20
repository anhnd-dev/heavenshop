<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'admins';

    protected $fillable = [
        'full_name',
        'user_name',
        'email',
        'password',
        'avatar',
        'phone',
        'address',
        'gender',
        'status',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'status'          => 'boolean',
        'last_login_at'   => 'datetime',
        'deleted_at'      => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'role_admin',
            'admin_id',
            'role_id'
        );
    }

    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'admin_permission',
            'admin_id',
            'permission_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ACL Methods
    |--------------------------------------------------------------------------
    */

    // Check Super Admin
    public function isSuperAdmin(): bool
    {
        return $this->roles()
            ->where('slug', 'super-admin')
            ->exists();
    }

    // Check permission
    public function hasPermission(string $permission): bool
    {
        // Super Admin bypass
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Check permission từ role
        $hasRolePermission = $this->roles()
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('name', $permission);
            })
            ->exists();

        // Check permission trực tiếp
        $hasDirectPermission = $this->permissions()
            ->where('name', $permission)
            ->exists();

        return $hasRolePermission || $hasDirectPermission;
    }

    // Check roll
    public function hasRole(string $role): bool
    {
        return $this->roles()
            ->where('slug', $role)
            ->exists();
    }

    // Check nhiều quyền
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {

            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }

    // public function getAvatarUrlAttribute()
    // {
    //     if ($this->avatar) {
    //         return asset('storage/' . $this->avatar);
    //     }

    //     return asset('default-avatar.png');
    // }
}
