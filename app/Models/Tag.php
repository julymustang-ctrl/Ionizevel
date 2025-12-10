<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'tags';
    protected $primaryKey = 'id_tag';

    protected $fillable = [
        'tag_name',
    ];

    /**
     * İlişki: Etikete bağlı makaleler
     */
    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_tag', 'id_tag', 'id_article');
    }
}
