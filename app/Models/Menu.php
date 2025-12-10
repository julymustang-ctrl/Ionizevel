<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';
    protected $primaryKey = 'id_menu';

    protected $fillable = [
        'name',
        'title',
        'ordering',
    ];

    /**
     * İlişki: Menüdeki sayfalar
     */
    public function pages()
    {
        return $this->hasMany(Page::class, 'id_menu', 'id_menu')
            ->orderBy('ordering');
    }

    /**
     * İlişki: Kök sayfalar (üst sayfası olmayan)
     */
    public function rootPages()
    {
        return $this->hasMany(Page::class, 'id_menu', 'id_menu')
            ->where('id_parent', 0)
            ->orderBy('ordering');
    }
}
