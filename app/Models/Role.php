<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $primaryKey = 'id_role';

    protected $fillable = [
        'role_level',
        'role_code',
        'role_name',
        'role_description',
    ];

    /**
     * İlişki: Rolle ilişkili kullanıcılar
     */
    public function users()
    {
        return $this->hasMany(User::class, 'id_role', 'id_role');
    }

    /**
     * İlişki: Rolün kuralları
     */
    public function rules()
    {
        return $this->hasMany(Rule::class, 'id_role', 'id_role');
    }

    /**
     * Scope: Aktif roller (seviye > 0)
     */
    public function scopeActive($query)
    {
        return $query->where('role_level', '>', 0);
    }
}
