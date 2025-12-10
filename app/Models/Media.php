<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';
    protected $primaryKey = 'id_media';

    protected $fillable = [
        'type',
        'file_name',
        'path',
        'base_path',
        'copyright',
        'provider',
        'date',
        'link',
        'square_crop',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    /**
     * İlişki: Medya dil içerikleri
     */
    public function translations()
    {
        return $this->hasMany(MediaLang::class, 'id_media', 'id_media');
    }

    /**
     * İlişki: Bu medyaya bağlı sayfalar
     */
    public function pages()
    {
        return $this->belongsToMany(Page::class, 'page_media', 'id_media', 'id_page');
    }

    /**
     * İlişki: Bu medyaya bağlı makaleler
     */
    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_media', 'id_media', 'id_article');
    }

    /**
     * Belirli bir dildeki çeviriyi getir
     */
    public function translate(string $lang)
    {
        return $this->translations()->where('lang', $lang)->first();
    }

    /**
     * Tam URL'yi getir
     */
    public function getUrlAttribute(): string
    {
        return asset($this->path);
    }
}
