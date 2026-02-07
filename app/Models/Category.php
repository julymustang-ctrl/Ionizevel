<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'id_category';

    protected $fillable = [
        'name',
        'id_parent',
        'ordering',
        'icon',
    ];

    /**
     * İlişki: Kategori dil içerikleri
     */
    public function translations()
    {
        return $this->hasMany(CategoryLang::class, 'id_category', 'id_category');
    }

    /**
     * İlişki: Kategorideki makaleler
     */
    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_category', 'id_category', 'id_article');
    }

    /**
     * İlişki: Üst kategori
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'id_parent', 'id_category');
    }

    /**
     * İlişki: Alt kategoriler
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'id_parent', 'id_category')->orderBy('ordering');
    }

    /**
     * Recursive children
     */
    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    /**
     * Belirli bir dildeki çeviriyi getir
     */
    public function translate(string $lang)
    {
        return $this->translations()->where('lang', $lang)->first();
    }

    /**
     * Scope: Kök kategoriler (parent yok)
     */
    public function scopeRoots($query)
    {
        return $query->where('id_parent', 0)->orWhereNull('id_parent');
    }

    /**
     * Hiyerarşik tree oluştur
     */
    public static function getTree(): array
    {
        $all = self::with(['translations', 'children.translations', 'children.children.translations'])
            ->roots()
            ->orderBy('ordering')
            ->get();

        return $all->toArray();
    }

    /**
     * Derinlik seviyesini getir
     */
    public function getDepth(): int
    {
        $depth = 0;
        $parent = $this->parent;
        while ($parent) {
            $depth++;
            $parent = $parent->parent;
        }
        return $depth;
    }

    /**
     * Title için helper
     */
    public function getTitle(string $lang = 'tr'): string
    {
        $translation = $this->translate($lang);
        return $translation?->title ?? $this->name;
    }
}

