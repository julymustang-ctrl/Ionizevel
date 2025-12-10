<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\CategoryLang;
use App\Models\Language;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('translations')->orderBy('ordering')->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $languages = Language::online()->orderBy('ordering')->get();
        return view('admin.categories.create', compact('languages'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:50']);

        $category = Category::create([
            'name' => $request->name,
            'ordering' => Category::max('ordering') + 1,
        ]);

        foreach (Language::online()->get() as $lang) {
            CategoryLang::create([
                'id_category' => $category->id_category,
                'lang' => $lang->lang,
                'title' => $request->input("title_{$lang->lang}") ?? $request->name,
            ]);
        }

        return redirect()->route('admin.categories.index')->with('success', 'Kategori oluşturuldu.');
    }

    public function edit($id)
    {
        $category = Category::with('translations')->findOrFail($id);
        $languages = Language::online()->orderBy('ordering')->get();
        return view('admin.categories.edit', compact('category', 'languages'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update(['name' => $request->name]);

        foreach (Language::online()->get() as $lang) {
            CategoryLang::updateOrCreate(
                ['id_category' => $category->id_category, 'lang' => $lang->lang],
                ['title' => $request->input("title_{$lang->lang}") ?? $category->name]
            );
        }

        return redirect()->route('admin.categories.index')->with('success', 'Kategori güncellendi.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->translations()->delete();
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori silindi.');
    }
}
