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
        'ordering',
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
     * Belirli bir dildeki çeviriyi getir
     */
    public function translate(string $lang)
    {
        return $this->translations()->where('lang', $lang)->first();
    }
}
