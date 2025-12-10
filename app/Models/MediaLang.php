<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaLang extends Model
{
    use HasFactory;

    protected $table = 'media_lang';
    public $incrementing = false;

    protected $fillable = [
        'id_media',
        'lang',
        'title',
        'alt',
        'description',
    ];

    public function media()
    {
        return $this->belongsTo(Media::class, 'id_media', 'id_media');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'lang', 'lang');
    }
}
