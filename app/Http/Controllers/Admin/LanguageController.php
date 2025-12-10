<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;

class LanguageController extends Controller
{
    public function index()
    {
        $languages = Language::orderBy('ordering')->paginate(20);
        return view('admin.languages.index', compact('languages'));
    }

    public function create()
    {
        return view('admin.languages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'lang' => 'required|string|max:3|unique:languages,lang',
            'name' => 'required|string|max:40',
        ]);

        Language::create([
            'lang' => $request->lang,
            'name' => $request->name,
            'online' => $request->boolean('online'),
            'def' => $request->boolean('def'),
            'ordering' => Language::max('ordering') + 1,
            'direction' => $request->direction ?? 1,
        ]);

        return redirect()->route('admin.languages.index')->with('success', 'Dil eklendi.');
    }

    public function edit($id)
    {
        $language = Language::findOrFail($id);
        return view('admin.languages.edit', compact('language'));
    }

    public function update(Request $request, $id)
    {
        $language = Language::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:40',
        ]);

        $language->update([
            'name' => $request->name,
            'online' => $request->boolean('online'),
            'def' => $request->boolean('def'),
            'direction' => $request->direction ?? 1,
        ]);

        // Varsayılan dil değiştirildiğinde diğerlerini güncelle
        if ($request->boolean('def')) {
            Language::where('lang', '!=', $id)->update(['def' => false]);
        }

        return redirect()->route('admin.languages.index')->with('success', 'Dil güncellendi.');
    }

    public function destroy($id)
    {
        $language = Language::findOrFail($id);

        if ($language->def) {
            return redirect()->route('admin.languages.index')
                ->with('error', 'Varsayılan dil silinemez.');
        }

        $language->delete();
        return redirect()->route('admin.languages.index')->with('success', 'Dil silindi.');
    }
}
