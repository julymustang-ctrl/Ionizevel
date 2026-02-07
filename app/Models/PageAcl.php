<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageAcl extends Model
{
    use HasFactory;

    protected $table = 'page_acl';

    protected $fillable = [
        'id_page',
        'id_role',
        'access_type',
    ];

    /**
     * İlişki: Sayfa
     */
    public function page()
    {
        return $this->belongsTo(Page::class, 'id_page', 'id_page');
    }

    /**
     * İlişki: Rol
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id_role');
    }

    /**
     * Belirli bir sayfanın erişim izinli rollerini getir
     */
    public static function getAllowedRoles(int $pageId): array
    {
        return static::where('id_page', $pageId)
            ->where('access_type', 'allow')
            ->pluck('id_role')
            ->toArray();
    }

    /**
     * Sayfa için ACL tanımla
     */
    public static function setPageAccess(int $pageId, array $roleIds): void
    {
        // Mevcut ACL'leri temizle
        static::where('id_page', $pageId)->delete();
        
        // Yeni ACL'leri ekle
        foreach ($roleIds as $roleId) {
            static::create([
                'id_page' => $pageId,
                'id_role' => $roleId,
                'access_type' => 'allow',
            ]);
        }
    }
}
