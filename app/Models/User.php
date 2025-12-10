<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'username',
        'screen_name',
        'firstname',
        'lastname',
        'email',
        'password',
        'id_role',
        'birthdate',
        'gender',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'salt',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'join_date' => 'datetime',
            'last_visit' => 'datetime',
            'birthdate' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * İlişki: Kullanıcının rolü
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id_role');
    }

    /**
     * Kullanıcı admin mi?
     */
    public function isAdmin(): bool
    {
        return $this->role && $this->role->role_level >= 5000;
    }

    /**
     * Kullanıcı super admin mi?
     */
    public function isSuperAdmin(): bool
    {
        return $this->role && $this->role->role_level >= 10000;
    }

    /**
     * Kullanıcının belirli bir kaynağa erişim izni var mı?
     */
    public function hasPermission(string $resource, string $action = ''): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $rules = Rule::where('id_role', $this->id_role)
            ->where(function ($query) use ($resource) {
                $query->where('resource', $resource)
                      ->orWhere('resource', 'all');
            })
            ->where('permission', 1)
            ->get();

        foreach ($rules as $rule) {
            if ($rule->resource === 'all') {
                return true;
            }
            if (empty($action) || empty($rule->actions)) {
                return true;
            }
            $allowedActions = explode(',', $rule->actions);
            if (in_array($action, $allowedActions)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Tam adı döndür
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->firstname} {$this->lastname}") ?: $this->username;
    }
}
