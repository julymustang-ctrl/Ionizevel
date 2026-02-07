<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Page;
use App\Models\PageAcl;

class AuthPageMiddleware
{
    /**
     * Sayfa bazlı erişim kontrolü
     * Ionize'daki ion_page_acl benzeri sistem
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Sadece frontend sayfa istekleri için kontrol et
        $route = $request->route();
        if (!$route) {
            return $next($request);
        }
        
        // URL'den sayfa bul
        $lang = $route->parameter('lang');
        $url = $route->parameter('url') ?? $route->parameter('segment1');
        
        if (!$url) {
            return $next($request);
        }
        
        // Sayfayı bul
        $page = Page::findByUrl($url, $lang ?? 'tr');
        
        if (!$page) {
            return $next($request);
        }
        
        // ACL kontrolü
        $aclRoles = PageAcl::where('id_page', $page->id_page)->pluck('id_role')->toArray();
        
        // ACL tanımlı değilse, herkes erişebilir
        if (empty($aclRoles)) {
            return $next($request);
        }
        
        // Giriş yapmış kullanıcı kontrolü
        $user = auth()->user();
        
        if (!$user) {
            // Giriş yapmamış, login sayfasına yönlendir
            return redirect()->route('login')->with('error', 'Bu sayfaya erişmek için giriş yapmalısınız.');
        }
        
        // Kullanıcının rolü ACL'de var mı?
        if (!in_array($user->id_role, $aclRoles)) {
            // Yetkisiz erişim
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }
        
        return $next($request);
    }
}
