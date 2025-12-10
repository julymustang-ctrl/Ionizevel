<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $table = 'pages';
    protected $primaryKey = 'id_page';

    protected $fillable = [
        'id_parent',
        'id_menu',
        'id_type',
        'id_subnav',
        'name',
        'ordering',
        'level',
        'online',
        'home',
        'author',
        'updater',
        'publish_on',
        'publish_off',
        'logical_date',
        'appears',
        'has_url',
        'view',
        'view_single',
        'article_list_view',
        'article_view',
        'article_order',
        'article_order_direction',
        'link',
        'link_type',
        'link_id',
        'pagination',
        'pagination_nb',
        'priority',
        'used_by_module',
        'deny_code',
    ];

    protected $casts = [
        'online' => 'boolean',
        'home' => 'boolean',
        'appears' => 'boolean',
        'has_url' => 'boolean',
        'pagination' => 'boolean',
        'publish_on' => 'datetime',
        'publish_off' => 'datetime',
        'logical_date' => 'datetime',
    ];

    /**
     * İlişki: Sayfa dil içerikleri
     */
    public function translations()
    {
        return $this->hasMany(PageLang::class, 'id_page', 'id_page');
    }

    /**
     * İlişki: Sayfanın üst sayfası
     */
    public function parent()
    {
        return $this->belongsTo(Page::class, 'id_parent', 'id_page');
    }

    /**
     * İlişki: Alt sayfalar
     */
    public function children()
    {
        return $this->hasMany(Page::class, 'id_parent', 'id_page');
    }

    /**
     * İlişki: Menü
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu');
    }

    /**
     * İlişki: Makaleler
     */
    public function articles()
    {
        return $this->belongsToMany(Article::class, 'page_article', 'id_page', 'id_article')
            ->withPivot('online', 'view', 'ordering', 'id_type', 'main_parent')
            ->orderBy('ordering');
    }

    /**
     * İlişki: Medyalar
     */
    public function media()
    {
        return $this->belongsToMany(Media::class, 'page_media', 'id_page', 'id_media')
            ->withPivot('online', 'ordering', 'lang_display')
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
     * Scope: Yayında olan sayfalar
     */
    public function scopeOnline($query)
    {
        return $query->where('online', true);
    }

    /**
     * Scope: Ana menüdeki sayfalar
     */
    public function scopeInMenu($query, $menuId = 1)
    {
        return $query->where('id_menu', $menuId);
    }
}
