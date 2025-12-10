<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    use HasFactory;

    protected $table = 'rules';
    public $incrementing = false;

    protected $fillable = [
        'id_role',
        'resource',
        'actions',
        'permission',
        'id_element',
    ];

    /**
     * İlişki: Kuralın rolü
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id_role');
    }
}
