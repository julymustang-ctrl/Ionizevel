<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'key',
        'lang',
        'value',
    ];

    /**
     * Belirli bir çeviriyi getir
     */
    public static function get(string $key, string $lang = null, string $group = 'general'): ?string
    {
        $lang = $lang ?? app()->getLocale();
        
        $translation = static::where('key', $key)
            ->where('lang', $lang)
            ->where('group', $group)
            ->first();
            
        return $translation?->value;
    }

    /**
     * Çeviri yoksa default değeri döndür
     */
    public static function getOrDefault(string $key, string $default, string $lang = null, string $group = 'general'): string
    {
        return static::get($key, $lang, $group) ?? $default;
    }
}
