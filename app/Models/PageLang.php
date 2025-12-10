<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageLang extends Model
{
    use HasFactory;

    protected $table = 'page_lang';
    public $incrementing = false;

    protected $fillable = [
        'id_page',
        'lang',
        'url',
        'link',
        'title',
        'subtitle',
        'nav_title',
        'subnav_title',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'online',
    ];

    protected $casts = [
        'online' => 'boolean',
    ];

    /**
     * İlişki: Ana sayfa
     */
    public function page()
    {
        return $this->belongsTo(Page::class, 'id_page', 'id_page');
    }

    /**
     * İlişki: Dil
     */
    public function language()
    {
        return $this->belongsTo(Language::class, 'lang', 'lang');
    }
}
