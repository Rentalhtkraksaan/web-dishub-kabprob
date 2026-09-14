<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'whatsapp',
        'referral_code',
        'password',
        'role',
        'is_hidden',
        'is_active',
        'avatar',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isDeveloper()
    {
        return $this->role === 'developer';
    }

    public function isAnggota()
    {
        return in_array($this->role, ['anggota', 'staf']);
    }

    public function isStaf()
    {
        return in_array($this->role, ['anggota', 'staf']);
    }

    public function isMasterSuperAdmin(): bool
    {
        return $this->id === 1 || (bool)$this->is_hidden || strtolower((string)$this->username) === 'aditya';
    }

    public function canManageUser(?User $target): bool
    {
        if (!$target || $this->id === $target->id) {
            return false;
        }

        // Master Super Admin has full authority over all subordinates (except cannot delete/demote self)
        if ($this->isMasterSuperAdmin()) {
            return !$target->isMasterSuperAdmin();
        }

        // Master Super Admin is 100% immune from anyone else
        if ($target->isMasterSuperAdmin()) {
            return false;
        }

        // Anti-Coup Rule: Subordinate cannot manage the account that created it
        if ($this->created_by === $target->id) {
            return false;
        }

        // Sub-Super Admin (non-master) cannot edit, demote, or deactivate peer Super Admins
        if ($target->isSuperAdmin()) {
            return false;
        }

        // Regular Admin can only manage Anggota accounts they personally created
        if ($this->isAdmin()) {
            if ($target->isSuperAdmin() || $target->isDeveloper() || $target->isAdmin()) {
                return false;
            }
            return $target->created_by === $this->id;
        }

        // Sub-Super Admin can manage regular Admin and Anggota
        if ($this->isSuperAdmin()) {
            return true;
        }

        return false;
    }

    public function canDelete()
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
