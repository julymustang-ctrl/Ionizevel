<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryLang extends Model
{
    use HasFactory;

    protected $table = 'category_lang';
    public $incrementing = false;

    protected $fillable = [
        'id_category',
        'lang',
        'title',
        'subtitle',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category', 'id_category');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'lang', 'lang');
    }
}
