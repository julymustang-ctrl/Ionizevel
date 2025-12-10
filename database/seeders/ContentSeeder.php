<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\Page;
use App\Models\PageLang;
use App\Models\Article;
use App\Models\ArticleLang;
use App\Models\Category;
use App\Models\CategoryLang;
use App\Models\Language;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        // Menü oluştur
        $mainMenu = Menu::firstOrCreate(
            ['name' => 'main'],
            ['title' => 'Ana Menü', 'ordering' => 1]
        );

        // Ana sayfa oluştur
        $homePage = Page::firstOrCreate(
            ['name' => 'home'],
            [
                'id_menu' => $mainMenu->id_menu,
                'id_parent' => 0,
                'online' => true,
                'home' => true,
                'appears' => true,
                'ordering' => 1,
                'author' => 'admin',
            ]
        );

        // Ana sayfa dil içerikleri
        foreach (Language::online()->get() as $lang) {
            PageLang::firstOrCreate(
                ['id_page' => $homePage->id_page, 'lang' => $lang->lang],
                [
                    'title' => $lang->lang == 'tr' ? 'Ana Sayfa' : 'Home',
                    'url' => $lang->lang == 'tr' ? 'ana-sayfa' : 'home',
                    'subtitle' => $lang->lang == 'tr' ? 'Laravel tabanlı modern CMS' : 'Modern Laravel-based CMS',
                    'meta_title' => $lang->lang == 'tr' ? 'Ionizevel - Ana Sayfa' : 'Ionizevel - Home',
                    'meta_description' => $lang->lang == 'tr' ? 'Ionizevel CMS ile web sitenizi kolayca yönetin.' : 'Easily manage your website with Ionizevel CMS.',
                    'online' => true,
                ]
            );
        }

        // Hakkımızda sayfası
        $aboutPage = Page::firstOrCreate(
            ['name' => 'about'],
            [
                'id_menu' => $mainMenu->id_menu,
                'id_parent' => 0,
                'online' => true,
                'home' => false,
                'appears' => true,
                'ordering' => 2,
                'author' => 'admin',
            ]
        );

        foreach (Language::online()->get() as $lang) {
            PageLang::firstOrCreate(
                ['id_page' => $aboutPage->id_page, 'lang' => $lang->lang],
                [
                    'title' => $lang->lang == 'tr' ? 'Hakkımızda' : 'About Us',
                    'url' => $lang->lang == 'tr' ? 'hakkimizda' : 'about-us',
                    'meta_title' => $lang->lang == 'tr' ? 'Hakkımızda - Ionizevel' : 'About Us - Ionizevel',
                    'online' => true,
                ]
            );
        }

        // Blog sayfası
        $blogPage = Page::firstOrCreate(
            ['name' => 'blog'],
            [
                'id_menu' => $mainMenu->id_menu,
                'id_parent' => 0,
                'online' => true,
                'home' => false,
                'appears' => true,
                'ordering' => 3,
                'author' => 'admin',
            ]
        );

        foreach (Language::online()->get() as $lang) {
            PageLang::firstOrCreate(
                ['id_page' => $blogPage->id_page, 'lang' => $lang->lang],
                [
                    'title' => 'Blog',
                    'url' => 'blog',
                    'meta_title' => 'Blog - Ionizevel',
                    'online' => true,
                ]
            );
        }

        // Kategori oluştur
        $category = Category::firstOrCreate(
            ['name' => 'genel'],
            ['ordering' => 1]
        );

        foreach (Language::online()->get() as $lang) {
            CategoryLang::firstOrCreate(
                ['id_category' => $category->id_category, 'lang' => $lang->lang],
                ['title' => $lang->lang == 'tr' ? 'Genel' : 'General']
            );
        }

        // Örnek makale
        $article = Article::firstOrCreate(
            ['name' => 'ornek-makale'],
            [
                'author' => 'admin',
                'indexed' => true,
                'comment_allow' => true,
            ]
        );

        foreach (Language::online()->get() as $lang) {
            ArticleLang::firstOrCreate(
                ['id_article' => $article->id_article, 'lang' => $lang->lang],
                [
                    'title' => $lang->lang == 'tr' ? 'Örnek Makale' : 'Sample Article',
                    'url' => $lang->lang == 'tr' ? 'ornek-makale' : 'sample-article',
                    'subtitle' => $lang->lang == 'tr' ? 'Bu bir örnek makaledir' : 'This is a sample article',
                    'content' => $lang->lang == 'tr'
                        ? '<p>Bu içerik örnek amaçlıdır. Admin panelinden düzenleyebilirsiniz.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>'
                        : '<p>This content is for demonstration purposes. You can edit it from the admin panel.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>',
                    'meta_title' => $lang->lang == 'tr' ? 'Örnek Makale - Ionizevel' : 'Sample Article - Ionizevel',
                    'online' => true,
                ]
            );
        }

        // Makaleyi kategoriye bağla
        $article->categories()->syncWithoutDetaching([$category->id_category]);

        // Makaleyi blog sayfasına bağla
        $blogPage->articles()->syncWithoutDetaching([$article->id_article]);

        $this->command->info('Demo içerik başarıyla oluşturuldu!');
    }
}
