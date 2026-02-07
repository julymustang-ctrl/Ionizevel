<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Models\Page;
use App\Models\Article;
use App\Models\Menu;
use App\Models\Media;
use App\Models\Category;

/**
 * Ionize Tag Library - Blade DSL
 * 
 * Bu sağlayıcı, Ionize CMS'in <ion:tag> etiket kütüphanesini
 * Laravel Blade direktifleri olarak uygular.
 * 
 * Kullanım:
 * @ion_page('slug') ... @endion_page
 * @ion_articles($page) ... @endion_articles
 * @ion_navigation('main') ... @endion_navigation
 * @ion_media($item) ... @endion_media
 */
class IonTagServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPageDirectives();
        $this->registerArticleDirectives();
        $this->registerNavigationDirectives();
        $this->registerMediaDirectives();
        $this->registerCategoryDirectives();
        $this->registerHelperDirectives();
    }

    /**
     * Sayfa direktifleri
     */
    protected function registerPageDirectives(): void
    {
        // @ion_page('slug') - Tek sayfa getir
        Blade::directive('ion_page', function ($expression) {
            return "<?php
                \$__ion_page = \\App\\Models\\Page::where('name', {$expression})->orWhere('id_page', {$expression})->first();
                if (\$__ion_page):
            ?>";
        });

        Blade::directive('endion_page', function () {
            return "<?php endif; ?>";
        });

        // @ion_pages - Tüm online sayfalar
        Blade::directive('ion_pages', function ($expression) {
            $params = $expression ?: "['menu' => 'main']";
            return "<?php
                \$__ion_params = {$params};
                \$__ion_query = \\App\\Models\\Page::where('online', 1);
                if (isset(\$__ion_params['menu'])) {
                    \$menu = \\App\\Models\\Menu::where('name', \$__ion_params['menu'])->first();
                    if (\$menu) \$__ion_query->where('id_menu', \$menu->id_menu);
                }
                if (isset(\$__ion_params['parent'])) {
                    \$__ion_query->where('id_parent', \$__ion_params['parent']);
                } else {
                    \$__ion_query->where('id_parent', 0);
                }
                \$__ion_pages = \$__ion_query->orderBy('ordering')->get();
                foreach (\$__ion_pages as \$page):
            ?>";
        });

        Blade::directive('endion_pages', function () {
            return "<?php endforeach; ?>";
        });

        // @ion_page_title - Sayfa başlığı (dil bazlı)
        Blade::directive('ion_page_title', function ($expression) {
            $lang = $expression ?: "app()->getLocale()";
            return "<?php echo \$page->translate({$lang})?->title ?? \$page->name; ?>";
        });

        // @ion_page_content - Sayfa içeriği
        Blade::directive('ion_page_content', function ($expression) {
            $lang = $expression ?: "app()->getLocale()";
            return "<?php echo \$page->translate({$lang})?->content ?? ''; ?>";
        });

        // @ion_page_url - Sayfa URL'i
        Blade::directive('ion_page_url', function ($expression) {
            $lang = $expression ?: "app()->getLocale()";
            return "<?php echo '/' . {$lang} . '/' . (\$page->translate({$lang})?->url ?? \$page->name); ?>";
        });
    }

    /**
     * Makale direktifleri
     */
    protected function registerArticleDirectives(): void
    {
        // @ion_articles($page) - Sayfa makaleleri
        Blade::directive('ion_articles', function ($expression) {
            $params = $expression ?: "\$page";
            return "<?php
                \$__ion_page_ref = {$params};
                if (\$__ion_page_ref instanceof \\App\\Models\\Page) {
                    \$__ion_articles = \$__ion_page_ref->articles()->where('online', 1)->orderBy('ordering')->get();
                } else {
                    \$__ion_articles = \\App\\Models\\Article::where('online', 1)->orderBy('ordering')->get();
                }
                foreach (\$__ion_articles as \$article):
            ?>";
        });

        Blade::directive('endion_articles', function () {
            return "<?php endforeach; ?>";
        });

        // @ion_article('slug') - Tek makale
        Blade::directive('ion_article', function ($expression) {
            return "<?php
                \$__ion_article = \\App\\Models\\Article::where('name', {$expression})->orWhere('id_article', {$expression})->first();
                if (\$__ion_article):
                    \$article = \$__ion_article;
            ?>";
        });

        Blade::directive('endion_article', function () {
            return "<?php endif; ?>";
        });

        // @ion_article_title
        Blade::directive('ion_article_title', function ($expression) {
            $lang = $expression ?: "app()->getLocale()";
            return "<?php echo \$article->translate({$lang})?->title ?? \$article->name; ?>";
        });

        // @ion_article_content
        Blade::directive('ion_article_content', function ($expression) {
            $lang = $expression ?: "app()->getLocale()";
            return "<?php echo \$article->translate({$lang})?->content ?? ''; ?>";
        });
    }

    /**
     * Navigasyon direktifleri
     */
    protected function registerNavigationDirectives(): void
    {
        // @ion_navigation('menu_name', level)
        Blade::directive('ion_navigation', function ($expression) {
            return "<?php
                \$__ion_nav_params = [{$expression}];
                \$__ion_menu_name = \$__ion_nav_params[0] ?? 'main';
                \$__ion_level = \$__ion_nav_params[1] ?? 0;
                
                \$__ion_menu = \\App\\Models\\Menu::where('name', \$__ion_menu_name)->first();
                if (\$__ion_menu) {
                    \$__ion_nav_pages = \\App\\Models\\Page::where('id_menu', \$__ion_menu->id_menu)
                        ->where('online', 1)
                        ->where('appears', 1)
                        ->where('id_parent', 0)
                        ->orderBy('ordering')
                        ->get();
                } else {
                    \$__ion_nav_pages = collect();
                }
                foreach (\$__ion_nav_pages as \$nav_page):
            ?>";
        });

        Blade::directive('endion_navigation', function () {
            return "<?php endforeach; ?>";
        });

        // @ion_nav_children - Alt navigasyon
        Blade::directive('ion_nav_children', function ($expression) {
            $parent = $expression ?: "\$nav_page";
            return "<?php
                \$__ion_parent = {$parent};
                \$__ion_children = \\App\\Models\\Page::where('id_parent', \$__ion_parent->id_page)
                    ->where('online', 1)
                    ->where('appears', 1)
                    ->orderBy('ordering')
                    ->get();
                if (\$__ion_children->count() > 0):
                    foreach (\$__ion_children as \$child_page):
            ?>";
        });

        Blade::directive('endion_nav_children', function () {
            return "<?php endforeach; endif; ?>";
        });
    }

    /**
     * Medya direktifleri
     */
    protected function registerMediaDirectives(): void
    {
        // @ion_medias($item) - Öğenin medyaları
        Blade::directive('ion_medias', function ($expression) {
            return "<?php
                \$__ion_item = {$expression};
                \$__ion_medias = \$__ion_item->media ?? collect();
                foreach (\$__ion_medias as \$media):
            ?>";
        });

        Blade::directive('endion_medias', function () {
            return "<?php endforeach; ?>";
        });

        // @ion_media_url - Medya URL'i
        Blade::directive('ion_media_url', function ($expression) {
            $media = $expression ?: "\$media";
            return "<?php echo asset({$media}->path); ?>";
        });

        // @ion_media_download - Güvenli indirme linki (SHA-1)
        Blade::directive('ion_media_download', function ($expression) {
            return "<?php
                \$__dl_media = {$expression};
                \$__dl_hash = sha1(\$__dl_media->id_media . config('app.key'));
                echo route('media.download', ['id' => \$__dl_media->id_media, 'hash' => \$__dl_hash]);
            ?>";
        });
    }

    /**
     * Kategori direktifleri
     */
    protected function registerCategoryDirectives(): void
    {
        // @ion_categories
        Blade::directive('ion_categories', function ($expression) {
            return "<?php
                \$__ion_categories = \\App\\Models\\Category::roots()->orderBy('ordering')->get();
                foreach (\$__ion_categories as \$category):
            ?>";
        });

        Blade::directive('endion_categories', function () {
            return "<?php endforeach; ?>";
        });

        // @ion_category_title
        Blade::directive('ion_category_title', function ($expression) {
            $lang = $expression ?: "app()->getLocale()";
            return "<?php echo \$category->translate({$lang})?->title ?? \$category->name; ?>";
        });
    }

    /**
     * Yardımcı direktifler
     */
    protected function registerHelperDirectives(): void
    {
        // @ion_lang - Aktif dil kodu
        Blade::directive('ion_lang', function () {
            return "<?php echo app()->getLocale(); ?>";
        });

        // @ion_languages - Tüm diller
        Blade::directive('ion_languages', function () {
            return "<?php
                \$__ion_languages = \\App\\Models\\Language::online()->orderBy('ordering')->get();
                foreach (\$__ion_languages as \$language):
            ?>";
        });

        Blade::directive('endion_languages', function () {
            return "<?php endforeach; ?>";
        });

        // @ion_setting('key') - Ayar değeri
        Blade::directive('ion_setting', function ($expression) {
            return "<?php echo \\App\\Models\\Setting::where('name', {$expression})->first()?->value ?? ''; ?>";
        });

        // @ion_translation('key') - Çeviri
        Blade::directive('ion_translation', function ($expression) {
            return "<?php echo \\App\\Models\\Translation::get({$expression}, app()->getLocale()); ?>";
        });

        // @ion_breadcrumb - Breadcrumb
        Blade::directive('ion_breadcrumb', function ($expression) {
            $page = $expression ?: "\$page";
            return "<?php
                \$__bc_page = {$page};
                \$__bc_items = \$__bc_page->getBreadcrumb();
                foreach (\$__bc_items as \$breadcrumb):
            ?>";
        });

        Blade::directive('endion_breadcrumb', function () {
            return "<?php endforeach; ?>";
        });
    }
}
