<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';
    protected $primaryKey = 'id_setting';

    protected $fillable = [
        'name',
        'content',
        'lang',
    ];

    /**
     * Belirli bir ayarı getir
     */
    public static function get(string $name, string $lang = null, $default = null)
    {
        $query = static::where('name', $name);
        
        if ($lang) {
            $query->where('lang', $lang);
        } else {
            $query->whereNull('lang');
        }

        $setting = $query->first();
        return $setting ? $setting->content : $default;
    }

    /**
     * Bir ayarı kaydet veya güncelle
     */
    public static function set(string $name, string $content, string $lang = null): void
    {
        static::updateOrCreate(
            ['name' => $name, 'lang' => $lang],
            ['content' => $content]
        );
    }
}
