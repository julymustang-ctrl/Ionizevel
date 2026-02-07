<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElementDefinition extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'description',
        'active',
        'ordering',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * İlişki: Element alanları
     */
    public function fields()
    {
        return $this->hasMany(ElementField::class)->orderBy('ordering');
    }

    /**
     * İlişki: Bu elemente ait değerler
     */
    public function elements()
    {
        return $this->hasMany(PageElement::class);
    }

    /**
     * Scope: Aktif olanlar
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
