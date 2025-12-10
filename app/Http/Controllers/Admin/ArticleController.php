<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\ArticleLang;
use App\Models\Category;
use App\Models\Language;

class ArticleController extends Controller
{
    /**
     * Makale listesi
     */
    public function index()
    {
        $articles = Article::with(['translations', 'categories'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Yeni makale formu
     */
    public function create()
    {
        $categories = Category::with('translations')->orderBy('ordering')->get();
        $languages = Language::online()->orderBy('ordering')->get();

        return view('admin.articles.create', compact('categories', 'languages'));
    }

    /**
     * Makale kaydet
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $article = Article::create([
            'name' => $request->name,
            'author' => auth()->user()->username,
            'indexed' => $request->boolean('indexed'),
            'comment_allow' => $request->boolean('comment_allow'),
        ]);

        // Dil içeriklerini kaydet
        $languages = Language::online()->get();
        foreach ($languages as $lang) {
            ArticleLang::create([
                'id_article' => $article->id_article,
                'lang' => $lang->lang,
                'title' => $request->input("title_{$lang->lang}") ?? $request->name,
                'url' => $request->input("url_{$lang->lang}") ?? \Str::slug($request->name),
                'content' => $request->input("content_{$lang->lang}"),
                'meta_title' => $request->input("meta_title_{$lang->lang}"),
                'meta_description' => $request->input("meta_description_{$lang->lang}"),
                'online' => $request->boolean("online_{$lang->lang}", true),
            ]);
        }

        // Kategorileri ekle
        if ($request->has('categories')) {
            $article->categories()->sync($request->categories);
        }

        return redirect()->route('admin.articles.index')
            ->with('success', 'Makale başarıyla oluşturuldu.');
    }

    /**
     * Makale düzenleme formu
     */
    public function edit($id)
    {
        $article = Article::with(['translations', 'categories'])->findOrFail($id);
        $categories = Category::with('translations')->orderBy('ordering')->get();
        $languages = Language::online()->orderBy('ordering')->get();

        return view('admin.articles.edit', compact('article', 'categories', 'languages'));
    }

    /**
     * Makale güncelle
     */
    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $article->update([
            'name' => $request->name,
            'updater' => auth()->user()->username,
            'indexed' => $request->boolean('indexed'),
            'comment_allow' => $request->boolean('comment_allow'),
            'publish_on' => $request->publish_on,
            'publish_off' => $request->publish_off,
        ]);

        // Dil içeriklerini güncelle
        $languages = Language::online()->get();
        foreach ($languages as $lang) {
            ArticleLang::updateOrCreate(
                ['id_article' => $article->id_article, 'lang' => $lang->lang],
                [
                    'title' => $request->input("title_{$lang->lang}") ?? $article->name,
                    'url' => $request->input("url_{$lang->lang}") ?? \Str::slug($article->name),
                    'subtitle' => $request->input("subtitle_{$lang->lang}"),
                    'content' => $request->input("content_{$lang->lang}"),
                    'meta_title' => $request->input("meta_title_{$lang->lang}"),
                    'meta_description' => $request->input("meta_description_{$lang->lang}"),
                    'meta_keywords' => $request->input("meta_keywords_{$lang->lang}"),
                    'online' => $request->boolean("online_{$lang->lang}", true),
                ]
            );
        }

        // Kategorileri güncelle
        if ($request->has('categories')) {
            $article->categories()->sync($request->categories);
        } else {
            $article->categories()->detach();
        }

        return redirect()->route('admin.articles.index')
            ->with('success', 'Makale başarıyla güncellendi.');
    }

    /**
     * Makale sil
     */
    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->translations()->delete();
        $article->categories()->detach();
        $article->tags()->detach();
        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Makale başarıyla silindi.');
    }
}
