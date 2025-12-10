<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $table = 'articles';
    protected $primaryKey = 'id_article';

    protected $fillable = [
        'name',
        'author',
        'updater',
        'publish_on',
        'publish_off',
        'logical_date',
        'indexed',
        'id_category',
        'comment_allow',
        'comment_autovalid',
        'comment_expire',
        'flag',
        'has_url',
    ];

    protected $casts = [
        'indexed' => 'boolean',
        'comment_allow' => 'boolean',
        'comment_autovalid' => 'boolean',
        'has_url' => 'boolean',
        'publish_on' => 'datetime',
        'publish_off' => 'datetime',
        'logical_date' => 'datetime',
        'comment_expire' => 'datetime',
    ];

    /**
     * İlişki: Makale dil içerikleri
     */
    public function translations()
    {
        return $this->hasMany(ArticleLang::class, 'id_article', 'id_article');
    }

    /**
     * İlişki: Sayfalar
     */
    public function pages()
    {
        return $this->belongsToMany(Page::class, 'page_article', 'id_article', 'id_page')
            ->withPivot('online', 'view', 'ordering', 'id_type', 'main_parent')
            ->orderBy('ordering');
    }

    /**
     * İlişki: Kategoriler
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'article_category', 'id_article', 'id_category');
    }

    /**
     * İlişki: Etiketler
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'article_tag', 'id_article', 'id_tag');
    }

    /**
     * İlişki: Medyalar
     */
    public function media()
    {
        return $this->belongsToMany(Media::class, 'article_media', 'id_article', 'id_media')
            ->withPivot('online', 'ordering', 'url', 'lang_display')
            ->orderBy('ordering');
    }

    /**
     * Belirli bir dildeki çeviriyi getir
     */
    public function translate(string $lang)
    {
        return $this->translations()->where('lang', $lang)->first();
    }

    /**
     * Scope: Yayınlanmış makaleler
     */
    public function scopePublished($query)
    {
        return $query->whereHas('translations', function ($q) {
            $q->where('online', true);
        });
    }
}
