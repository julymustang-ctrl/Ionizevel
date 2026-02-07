<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageElement extends Model
{
    use HasFactory;

    protected $fillable = [
        'element_definition_id',
        'parent_type',
        'parent_id',
        'lang',
        'ordering',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    /**
     * İlişki: Element tanımı
     */
    public function definition()
    {
        return $this->belongsTo(ElementDefinition::class, 'element_definition_id');
    }

    /**
     * Polimorfik ilişki: Parent (Page veya Article)
     */
    public function parent()
    {
        if ($this->parent_type === 'page') {
            return Page::find($this->parent_id);
        } elseif ($this->parent_type === 'article') {
            return Article::find($this->parent_id);
        }
        return null;
    }

    /**
     * Alan değerini al
     */
    public function getValue(string $fieldName, $default = null)
    {
        return $this->data[$fieldName] ?? $default;
    }

    /**
     * Scope: Belirli bir parent'a ait
     */
    public function scopeForParent($query, string $type, int $id)
    {
        return $query->where('parent_type', $type)->where('parent_id', $id);
    }

    /**
     * Scope: Belirli bir dile ait
     */
    public function scopeForLang($query, string $lang)
    {
        return $query->where(function($q) use ($lang) {
            $q->where('lang', $lang)->orWhereNull('lang');
        });
    }
}
