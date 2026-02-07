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
        // Sadece root kategorileri al, children ile birlikte
        $categories = Category::with(['translations', 'children.translations', 'children.children.translations'])
            ->roots()
            ->orderBy('ordering')
            ->get();
            
        return view('admin.categories.index', compact('categories'));
    }

    public function create(Request $request)
    {
        $languages = Language::online()->orderBy('ordering')->get();
        $parentId = $request->query('parent', 0);
        $parent = $parentId ? Category::find($parentId) : null;
        $allCategories = Category::with('translations')->orderBy('ordering')->get();
        
        return view('admin.categories.create', compact('languages', 'parent', 'allCategories'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:50']);

        $category = Category::create([
            'name' => $request->name,
            'id_parent' => $request->id_parent ?? 0,
            'icon' => $request->icon,
            'ordering' => Category::where('id_parent', $request->id_parent ?? 0)->max('ordering') + 1,
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
        $allCategories = Category::with('translations')
            ->where('id_category', '!=', $id)
            ->orderBy('ordering')
            ->get();
        
        return view('admin.categories.edit', compact('category', 'languages', 'allCategories'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'id_parent' => $request->id_parent ?? 0,
            'icon' => $request->icon,
        ]);

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
        
        // Alt kategorileri de sil
        foreach ($category->children as $child) {
            $child->translations()->delete();
            $child->delete();
        }
        
        $category->translations()->delete();
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori silindi.');
    }

    /**
     * Drag-drop sıralama için
     */
    public function reorder(Request $request)
    {
        $order = $request->input('order', []);
        
        foreach ($order as $item) {
            Category::where('id_category', $item['id'])->update([
                'ordering' => $item['ordering']
            ]);
        }

        return response()->json(['success' => true]);
    }
}

