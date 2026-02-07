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
     * İlişki: ACL (Erişim Kontrol Listesi)
     */
    public function acl()
    {
        return $this->hasMany(PageAcl::class, 'id_page', 'id_page');
    }

    /**
     * Sayfa için erişim izinli rolleri getir
     */
    public function getAllowedRoles(): array
    {
        return $this->acl()->where('access_type', 'allow')->pluck('id_role')->toArray();
    }

    /**
     * Kullanıcının bu sayfaya erişimi var mı?
     */
    public function canAccess(?User $user): bool
    {
        $allowedRoles = $this->getAllowedRoles();
        
        // ACL tanımlı değilse herkes erişebilir
        if (empty($allowedRoles)) {
            return true;
        }
        
        // Giriş yapmamış kullanıcı
        if (!$user) {
            return false;
        }
        
        return in_array($user->id_role, $allowedRoles);
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

    /**
     * Tüm ataları al (parent'dan root'a kadar)
     */
    public function getAncestors(): array
    {
        $ancestors = [];
        $current = $this->parent;
        
        while ($current) {
            array_unshift($ancestors, $current);
            $current = $current->parent;
        }
        
        return $ancestors;
    }

    /**
     * Sayfa için hiyerarşik URL yolu oluştur
     * Örn: /hakkimizda/ekibimiz/yoneticiler
     */
    public function getUrlPath(string $lang): string
    {
        $ancestors = $this->getAncestors();
        $segments = [];
        
        foreach ($ancestors as $ancestor) {
            $translation = $ancestor->translate($lang);
            if ($translation && $translation->url) {
                $segments[] = $translation->url;
            }
        }
        
        // Kendi URL'ini ekle
        $translation = $this->translate($lang);
        if ($translation && $translation->url) {
            $segments[] = $translation->url;
        }
        
        return implode('/', $segments);
    }

    /**
     * Tam URL'i al (dil kodu dahil)
     */
    public function getFullUrl(string $lang): string
    {
        if ($this->home) {
            return '/' . $lang;
        }
        
        $path = $this->getUrlPath($lang);
        return '/' . $lang . '/' . $path;
    }

    /**
     * URL'den sayfa bul (hiyerarşik)
     */
    public static function findByUrl(string $url, string $lang): ?Page
    {
        // URL'i segmentlere ayır
        $segments = array_filter(explode('/', trim($url, '/')));
        
        if (empty($segments)) {
            // Ana sayfa
            return static::where('home', true)->first();
        }
        
        // Hiyerarşik olarak ara
        $currentParentId = 0;
        $page = null;
        
        foreach ($segments as $segment) {
            $page = static::whereHas('translations', function ($query) use ($segment, $lang) {
                $query->where('lang', $lang)->where('url', $segment);
            })->where('id_parent', $currentParentId)->first();
            
            if (!$page) {
                return null;
            }
            
            $currentParentId = $page->id_page;
        }
        
        return $page;
    }

    /**
     * Breadcrumb için ataları getir
     */
    public function getBreadcrumb(string $lang): array
    {
        $breadcrumb = [];
        $ancestors = $this->getAncestors();
        
        foreach ($ancestors as $ancestor) {
            $translation = $ancestor->translate($lang);
            $breadcrumb[] = [
                'id' => $ancestor->id_page,
                'title' => $translation->title ?? $ancestor->name,
                'url' => $ancestor->getFullUrl($lang),
            ];
        }
        
        // Kendini ekle
        $translation = $this->translate($lang);
        $breadcrumb[] = [
            'id' => $this->id_page,
            'title' => $translation->title ?? $this->name,
            'url' => $this->getFullUrl($lang),
        ];
        
        return $breadcrumb;
    }

    /**
     * Sayfa tipini kontrol et
     * @return string default|module|link
     */
    public function getPageType(): string
    {
        if ($this->link_type === 'module' || !empty($this->used_by_module)) {
            return 'module';
        }
        
        if (!empty($this->link) || $this->link_type === 'external') {
            return 'link';
        }
        
        return 'default';
    }

    /**
     * Tüm alt sayfaları recursive olarak getir
     */
    public function getAllDescendants(): \Illuminate\Support\Collection
    {
        $descendants = collect();
        
        foreach ($this->children as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($child->getAllDescendants());
        }
        
        return $descendants;
    }

    /**
     * Derinlik seviyesini hesapla
     */
    public function getDepthLevel(): int
    {
        return count($this->getAncestors());
    }
}

