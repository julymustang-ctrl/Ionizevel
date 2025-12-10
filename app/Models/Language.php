<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $table = 'languages';
    protected $primaryKey = 'lang';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'lang',
        'name',
        'online',
        'def',
        'ordering',
        'direction',
    ];

    protected $casts = [
        'online' => 'boolean',
        'def' => 'boolean',
    ];

    /**
     * Scope: Aktif diller
     */
    public function scopeOnline($query)
    {
        return $query->where('online', true);
    }

    /**
     * Varsayılan dili getir
     */
    public static function getDefault()
    {
        return static::where('def', true)->first();
    }
}
