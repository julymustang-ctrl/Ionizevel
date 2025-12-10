<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleLang extends Model
{
    use HasFactory;

    protected $table = 'article_lang';
    public $incrementing = false;

    protected $fillable = [
        'id_article',
        'lang',
        'url',
        'title',
        'subtitle',
        'meta_title',
        'content',
        'meta_keywords',
        'meta_description',
        'online',
    ];

    protected $casts = [
        'online' => 'boolean',
    ];

    /**
     * İlişki: Ana makale
     */
    public function article()
    {
        return $this->belongsTo(Article::class, 'id_article', 'id_article');
    }

    /**
     * İlişki: Dil
     */
    public function language()
    {
        return $this->belongsTo(Language::class, 'lang', 'lang');
    }
}
