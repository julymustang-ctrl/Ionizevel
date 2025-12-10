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
     * Sayfa göster (SEO URL ile)
     */
    public function page(Request $request, $lang, $url = null)
    {
        $this->setLang($lang);

        // URL'den sayfa bul
        $page = Page::whereHas('translations', function($q) use ($url) {
            $q->where('lang', $this->lang)->where('url', $url);
        })->where('online', true)->first();

        if (!$page) {
            abort(404);
        }

        return $this->renderPage($page);
    }

    /**
     * Makale göster
     */
    public function article(Request $request, $lang, $pageUrl, $articleUrl)
    {
        $this->setLang($lang);

        // Sayfayı bul
        $page = Page::whereHas('translations', function($q) use ($pageUrl) {
            $q->where('lang', $this->lang)->where('url', $pageUrl);
        })->where('online', true)->first();

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

        $viewData = array_merge($this->getViewData(), [
            'page' => $page,
            'translation' => $translation,
            'articles' => $articles,
            'menus' => $menus,
        ]);

        // View şablonunu belirle
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

        $viewData = array_merge($this->getViewData(), [
            'page' => $page,
            'pageTranslation' => $pageTranslation,
            'article' => $article,
            'translation' => $articleTranslation,
            'menus' => $menus,
        ]);

        return view('frontend.article', $viewData);
    }

    /**
     * Menüleri al
     */
    protected function getMenus()
    {
        return Menu::with(['pages' => function($q) {
            $q->where('online', true)
              ->where('appears', true)
              ->orderBy('ordering')
              ->with('translations');
        }])->orderBy('ordering')->get();
    }

    /**
     * View data
     */
    protected function getViewData()
    {
        return [
            'currentLang' => $this->lang,
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
