<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\PageLang;
use App\Models\Menu;
use App\Models\Language;

class PageController extends Controller
{
    /**
     * Sayfa listesi
     */
    public function index()
    {
        $pages = Page::with(['translations', 'menu', 'parent'])
            ->orderBy('id_menu')
            ->orderBy('ordering')
            ->paginate(20);

        $menus = Menu::orderBy('ordering')->get();

        return view('admin.pages.index', compact('pages', 'menus'));
    }

    /**
     * Yeni sayfa formu
     */
    public function create()
    {
        $menus = Menu::orderBy('ordering')->get();
        $pages = Page::with('translations')->orderBy('ordering')->get();
        $languages = Language::online()->orderBy('ordering')->get();

        return view('admin.pages.create', compact('menus', 'pages', 'languages'));
    }

    /**
     * Sayfa kaydet
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'id_menu' => 'required|exists:menus,id_menu',
        ]);

        $page = Page::create([
            'name' => $request->name,
            'id_menu' => $request->id_menu,
            'id_parent' => $request->id_parent ?? 0,
            'online' => $request->boolean('online'),
            'appears' => $request->boolean('appears', true),
            'author' => auth()->user()->username,
            'ordering' => Page::where('id_menu', $request->id_menu)->max('ordering') + 1,
        ]);

        // Dil içeriklerini kaydet
        $languages = Language::online()->get();
        foreach ($languages as $lang) {
            PageLang::create([
                'id_page' => $page->id_page,
                'lang' => $lang->lang,
                'title' => $request->input("title_{$lang->lang}") ?? $request->name,
                'url' => $request->input("url_{$lang->lang}") ?? \Str::slug($request->name),
                'meta_title' => $request->input("meta_title_{$lang->lang}"),
                'meta_description' => $request->input("meta_description_{$lang->lang}"),
                'online' => true,
            ]);
        }

        return redirect()->route('admin.pages.index')
            ->with('success', 'Sayfa başarıyla oluşturuldu.');
    }

    /**
     * Sayfa düzenleme formu
     */
    public function edit($id)
    {
        $page = Page::with('translations')->findOrFail($id);
        $menus = Menu::orderBy('ordering')->get();
        $pages = Page::with('translations')
            ->where('id_page', '!=', $id)
            ->orderBy('ordering')
            ->get();
        $languages = Language::online()->orderBy('ordering')->get();

        return view('admin.pages.edit', compact('page', 'menus', 'pages', 'languages'));
    }

    /**
     * Sayfa güncelle
     */
    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'id_menu' => 'required|exists:menus,id_menu',
        ]);

        $page->update([
            'name' => $request->name,
            'id_menu' => $request->id_menu,
            'id_parent' => $request->id_parent ?? 0,
            'online' => $request->boolean('online'),
            'appears' => $request->boolean('appears'),
            'updater' => auth()->user()->username,
            'view' => $request->view,
        ]);

        // Dil içeriklerini güncelle
        $languages = Language::online()->get();
        foreach ($languages as $lang) {
            PageLang::updateOrCreate(
                ['id_page' => $page->id_page, 'lang' => $lang->lang],
                [
                    'title' => $request->input("title_{$lang->lang}") ?? $page->name,
                    'url' => $request->input("url_{$lang->lang}") ?? \Str::slug($page->name),
                    'subtitle' => $request->input("subtitle_{$lang->lang}"),
                    'meta_title' => $request->input("meta_title_{$lang->lang}"),
                    'meta_description' => $request->input("meta_description_{$lang->lang}"),
                    'meta_keywords' => $request->input("meta_keywords_{$lang->lang}"),
                    'online' => $request->boolean("online_{$lang->lang}", true),
                ]
            );
        }

        return redirect()->route('admin.pages.index')
            ->with('success', 'Sayfa başarıyla güncellendi.');
    }

    /**
     * Sayfa sil
     */
    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->translations()->delete();
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Sayfa başarıyla silindi.');
    }
}
