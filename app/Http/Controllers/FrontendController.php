<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Article;
use App\Models\Language;
use App\Models\Setting;
use App\Models\Menu;

class FrontendController extends Controller
{
    protected $lang;
    protected $languages;

    public function __construct()
    {
        // Varsayılan dili al
        $this->languages = Language::online()->orderBy('ordering')->get();
        $defaultLang = Language::where('def', true)->first();
        $this->lang = $defaultLang ? $defaultLang->lang : 'tr';
    }

    /**
     * Ana sayfa
     */
    public function home(Request $request, $lang = null)
    {
        $this->setLang($lang);

        // Ana sayfayı bul
        $page = Page::where('home', true)->first();

        if (!$page) {
            // Ana sayfa yoksa ilk sayfayı göster
            $page = Page::where('online', true)->first();
        }

        if (!$page) {
            return view('frontend.welcome', $this->getViewData());
        }

        return $this->renderPage($page);
    }

    /**
     * Hiyerarşik sayfa göster (SEO URL ile)
     * Destekler: /lang/parent/child/grandchild
     */
    public function page(Request $request, $lang, $url = null)
    {
        $this->setLang($lang);

        // Hiyerarşik URL arama - yeni method
        $page = Page::findByUrl($url, $this->lang);

        // Eğer bulunamazsa, eski yöntemle dene (geriye uyumluluk)
        if (!$page) {
            $page = Page::whereHas('translations', function($q) use ($url) {
                $q->where('lang', $this->lang)->where('url', $url);
            })->where('online', true)->first();
        }

        if (!$page) {
            abort(404);
        }

        // Sayfa online değilse
        if (!$page->online) {
            abort(404);
        }

        // Page Type kontrolü (Ionize felsefesi)
        return $this->handlePageType($page);
    }

    /**
     * Catch-all route for deeply nested pages
     * Örn: /en/about/team/management/board
     */
    public function catchAll(Request $request, $lang, $segment1 = null, $segment2 = null, $segment3 = null, $segments = null)
    {
        $this->setLang($lang);
        
        // Tüm segmentleri birleştir
        $allSegments = array_filter([$segment1, $segment2, $segment3]);
        
        // Eğer catch-all segments varsa, onu da ekle
        if ($segments) {
            $extraSegments = array_filter(explode('/', $segments));
            $allSegments = array_merge($allSegments, $extraSegments);
        }
        
        if (empty($allSegments)) {
            abort(404);
        }
        
        // Segmentlerden URL oluştur
        $url = implode('/', $allSegments);
        
        // Önce tam URL ile sayfa ara
        $page = Page::findByUrl($url, $this->lang);
        
        if ($page) {
            return $this->handlePageType($page);
        }
        
        // Sayfa bulunamadı, son segment makale olabilir
        if (count($allSegments) >= 2) {
            $potentialArticleUrl = array_pop($allSegments);
            $pageUrl = implode('/', $allSegments);
            
            $page = Page::findByUrl($pageUrl, $this->lang);
            
            if ($page) {
                // Makale ara
                $article = Article::whereHas('translations', function($q) use ($potentialArticleUrl) {
                    $q->where('lang', $this->lang)->where('url', $potentialArticleUrl);
                })->whereHas('pages', function($q) use ($page) {
                    $q->where('pages.id_page', $page->id_page);
                })->first();
                
                if ($article) {
                    return $this->renderArticle($page, $article);
                }
            }
        }
        
        abort(404);
    }

    /**
     * Page Type'a göre işlem yap (default, module, link)
     */
    protected function handlePageType(Page $page)
    {
        $pageType = $page->getPageType();

        switch ($pageType) {
            case 'module':
                // Modül controller'a devret
                return $this->handleModulePage($page);
            
            case 'link':
                // Harici veya dahili link yönlendirmesi
                return $this->handleLinkPage($page);
            
            default:
                // Normal sayfa render
                return $this->renderPage($page);
        }
    }

    /**
     * Module tipi sayfa işleme
     * nwidart/laravel-modules ile entegre
     */
    protected function handleModulePage(Page $page)
    {
        // ModuleDispatcher ile modül controller'a devret
        $dispatcher = app(\App\Services\ModuleDispatcher::class);
        $response = $dispatcher->dispatch($page, request(), $this->lang);
        
        // Modül bir response döndürdüyse kullan
        if ($response !== null) {
            return $response;
        }
        
        // Modül bulunamazsa veya response yoksa normal sayfa olarak göster
        return $this->renderPage($page);
    }

    /**
     * Link tipi sayfa işleme
     */
    protected function handleLinkPage(Page $page)
    {
        $link = $page->link;
        
        if ($page->link_type === 'external' && $link) {
            return redirect()->away($link);
        }
        
        if ($page->link_type === 'internal' && $page->link_id) {
            // Dahili sayfa linkine yönlendir
            $targetPage = Page::find($page->link_id);
            if ($targetPage) {
                return redirect($targetPage->getFullUrl($this->lang));
            }
        }
        
        // Link geçersizse 404
        abort(404);
    }

    /**
     * Makale göster
     */
    public function article(Request $request, $lang, $pageUrl, $articleUrl)
    {
        $this->setLang($lang);

        // Hiyerarşik sayfa arama
        $page = Page::findByUrl($pageUrl, $this->lang);
        
        // Geriye uyumluluk
        if (!$page) {
            $page = Page::whereHas('translations', function($q) use ($pageUrl) {
                $q->where('lang', $this->lang)->where('url', $pageUrl);
            })->where('online', true)->first();
        }

        if (!$page) {
            abort(404);
        }

        // Makaleyi bul
        $article = Article::whereHas('translations', function($q) use ($articleUrl) {
            $q->where('lang', $this->lang)->where('url', $articleUrl);
        })->first();

        if (!$article) {
            abort(404);
        }

        return $this->renderArticle($page, $article);
    }

    /**
     * Sayfayı render et
     */
    protected function renderPage(Page $page)
    {
        $translation = $page->translate($this->lang);
        $articles = $page->articles()->with('translations')->get();

        // Menüleri al
        $menus = $this->getMenus();
        
        // Breadcrumb oluştur
        $breadcrumb = $page->getBreadcrumb($this->lang);

        $viewData = array_merge($this->getViewData(), [
            'page' => $page,
            'translation' => $translation,
            'articles' => $articles,
            'menus' => $menus,
            'breadcrumb' => $breadcrumb,
        ]);

        // View şablonunu belirle (Theme Manager'dan gelen logical name)
        $view = $page->view ?: 'page';
        $viewPath = "frontend.{$view}";

        if (!view()->exists($viewPath)) {
            $viewPath = 'frontend.page';
        }

        return view($viewPath, $viewData);
    }

    /**
     * Makaleyi render et
     */
    protected function renderArticle(Page $page, Article $article)
    {
        $pageTranslation = $page->translate($this->lang);
        $articleTranslation = $article->translate($this->lang);

        $menus = $this->getMenus();
        
        // Breadcrumb oluştur (sayfa breadcrumb'ına makale ekle)
        $breadcrumb = $page->getBreadcrumb($this->lang);
        $breadcrumb[] = [
            'id' => $article->id_article,
            'title' => $articleTranslation->title ?? $article->name,
            'url' => $page->getFullUrl($this->lang) . '/' . ($articleTranslation->url ?? ''),
        ];

        $viewData = array_merge($this->getViewData(), [
            'page' => $page,
            'pageTranslation' => $pageTranslation,
            'article' => $article,
            'translation' => $articleTranslation,
            'menus' => $menus,
            'breadcrumb' => $breadcrumb,
        ]);

        // Makale view'ı
        $view = $page->article_view ?: 'article';
        $viewPath = "frontend.{$view}";

        if (!view()->exists($viewPath)) {
            $viewPath = 'frontend.article';
        }

        return view($viewPath, $viewData);
    }

    /**
     * Menüleri al (hiyerarşik yapı ile)
     */
    protected function getMenus()
    {
        return Menu::with(['pages' => function($q) {
            $q->where('online', true)
              ->where('appears', true)
              ->where('id_parent', 0) // Sadece root sayfalar
              ->orderBy('ordering')
              ->with(['translations', 'children' => function($cq) {
                  $cq->where('online', true)
                    ->where('appears', true)
                    ->orderBy('ordering')
                    ->with('translations');
              }]);
        }])->orderBy('ordering')->get();
    }

    /**
     * View data
     */
    protected function getViewData()
    {
        return [
            'currentLang' => $this->lang,
            'lang' => $this->lang,
            'languages' => $this->languages,
            'siteName' => Setting::get('site_name', 'Ionizevel'),
        ];
    }

    /**
     * Dil ayarla
     */
    protected function setLang($lang)
    {
        if ($lang && $this->languages->contains('lang', $lang)) {
            $this->lang = $lang;
        }
        app()->setLocale($this->lang);
    }
}
