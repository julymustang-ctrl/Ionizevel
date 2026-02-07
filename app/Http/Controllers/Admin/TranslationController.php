<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Translation;
use App\Models\Language;

class TranslationController extends Controller
{
    /**
     * Çeviri listesi
     */
    public function index(Request $request)
    {
        $languages = Language::online()->orderBy('ordering')->get();
        $search = $request->input('search');
        $group = $request->input('group', 'all');
        
        $query = Translation::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('key', 'like', "%{$search}%")
                  ->orWhere('value', 'like', "%{$search}%");
            });
        }
        
        if ($group !== 'all') {
            $query->where('group', $group);
        }
        
        $translations = $query->orderBy('group')->orderBy('key')->paginate(50);
        
        // Grupları al
        $groups = Translation::select('group')->distinct()->pluck('group');

        return view('admin.translations.index', compact('translations', 'languages', 'groups', 'search', 'group'));
    }

    /**
     * Yeni çeviri formu
     */
    public function create()
    {
        $languages = Language::online()->orderBy('ordering')->get();
        $groups = Translation::select('group')->distinct()->pluck('group');
        
        return view('admin.translations.create', compact('languages', 'groups'));
    }

    /**
     * Çeviri kaydet
     */
    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|max:255',
            'group' => 'required|string|max:50',
        ]);

        $languages = Language::online()->get();
        
        foreach ($languages as $lang) {
            Translation::updateOrCreate(
                [
                    'key' => $request->key,
                    'group' => $request->group,
                    'lang' => $lang->lang,
                ],
                [
                    'value' => $request->input("value_{$lang->lang}", ''),
                ]
            );
        }

        return redirect()->route('admin.translations.index')
            ->with('success', 'Translation saved successfully.');
    }

    /**
     * Çeviriyi düzenle
     */
    public function edit($id)
    {
        $translation = Translation::findOrFail($id);
        $languages = Language::online()->orderBy('ordering')->get();
        
        // Aynı key'e sahip tüm dil çevirilerini al
        $allTranslations = Translation::where('key', $translation->key)
            ->where('group', $translation->group)
            ->get()
            ->keyBy('lang');

        return view('admin.translations.edit', compact('translation', 'languages', 'allTranslations'));
    }

    /**
     * Çeviri güncelle
     */
    public function update(Request $request, $id)
    {
        $translation = Translation::findOrFail($id);
        
        $languages = Language::online()->get();
        
        foreach ($languages as $lang) {
            Translation::updateOrCreate(
                [
                    'key' => $translation->key,
                    'group' => $translation->group,
                    'lang' => $lang->lang,
                ],
                [
                    'value' => $request->input("value_{$lang->lang}", ''),
                ]
            );
        }

        return redirect()->route('admin.translations.index')
            ->with('success', 'Translation updated successfully.');
    }

    /**
     * Çeviri sil
     */
    public function destroy($id)
    {
        $translation = Translation::findOrFail($id);
        
        // Aynı key'e sahip tüm çevirileri sil
        Translation::where('key', $translation->key)
            ->where('group', $translation->group)
            ->delete();

        return redirect()->route('admin.translations.index')
            ->with('success', 'Translation deleted successfully.');
    }

    /**
     * AJAX ile çeviri güncelle
     */
    public function updateAjax(Request $request)
    {
        $key = $request->input('key');
        $group = $request->input('group');
        $lang = $request->input('lang');
        $value = $request->input('value');

        Translation::updateOrCreate(
            ['key' => $key, 'group' => $group, 'lang' => $lang],
            ['value' => $value]
        );

        return response()->json(['success' => true]);
    }

    /**
     * Lang dosyalarından import et
     */
    public function import()
    {
        $langPath = resource_path('lang');
        $languages = Language::online()->get();
        $imported = 0;

        foreach ($languages as $language) {
            $langDir = $langPath . '/' . $language->lang;
            if (is_dir($langDir)) {
                foreach (glob($langDir . '/*.php') as $file) {
                    $group = basename($file, '.php');
                    $translations = include $file;
                    
                    if (is_array($translations)) {
                        $this->importArray($translations, $group, $language->lang, '');
                        $imported++;
                    }
                }
            }
        }

        return redirect()->route('admin.translations.index')
            ->with('success', "Imported translations from {$imported} files.");
    }

    private function importArray($array, $group, $lang, $prefix = '')
    {
        foreach ($array as $key => $value) {
            $fullKey = $prefix ? "{$prefix}.{$key}" : $key;
            
            if (is_array($value)) {
                $this->importArray($value, $group, $lang, $fullKey);
            } else {
                Translation::updateOrCreate(
                    ['key' => $fullKey, 'group' => $group, 'lang' => $lang],
                    ['value' => $value]
                );
            }
        }
    }
}
