<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ElementDefinition;
use App\Models\ElementField;
use App\Models\PageElement;

class ContentElementController extends Controller
{
    /**
     * Element tanımları listesi
     */
    public function index()
    {
        $elements = ElementDefinition::with('fields')
            ->orderBy('ordering')
            ->get();

        return view('admin.elements.index', compact('elements'));
    }

    /**
     * Yeni element tanımı formu
     */
    public function create()
    {
        $fieldTypes = ElementField::TYPES;
        return view('admin.elements.create', compact('fieldTypes'));
    }

    /**
     * Element tanımı kaydet
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:element_definitions,name',
            'title' => 'required|string|max:255',
        ]);

        $element = ElementDefinition::create([
            'name' => $request->input('name'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'active' => $request->has('active'),
            'ordering' => ElementDefinition::max('ordering') + 1,
        ]);

        // Alan ekle
        if ($request->has('fields')) {
            foreach ($request->fields as $index => $field) {
                ElementField::create([
                    'element_definition_id' => $element->id,
                    'name' => $field['name'],
                    'label' => $field['label'],
                    'type' => $field['type'],
                    'options' => isset($field['options']) ? json_decode($field['options'], true) : null,
                    'default_value' => $field['default_value'] ?? null,
                    'translatable' => isset($field['translatable']),
                    'required' => isset($field['required']),
                    'ordering' => $index,
                ]);
            }
        }

        return redirect()->route('admin.elements.index')
            ->with('success', 'Content Element created successfully.');
    }

    /**
     * Element tanımı düzenleme formu
     */
    public function edit($id)
    {
        $element = ElementDefinition::with('fields')->findOrFail($id);
        $fieldTypes = ElementField::TYPES;

        return view('admin.elements.edit', compact('element', 'fieldTypes'));
    }

    /**
     * Element tanımı güncelle
     */
    public function update(Request $request, $id)
    {
        $element = ElementDefinition::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:element_definitions,name,' . $id,
            'title' => 'required|string|max:255',
        ]);

        $element->update([
            'name' => $request->input('name'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'active' => $request->has('active'),
        ]);

        // Mevcut alanları sil ve yeniden oluştur
        $element->fields()->delete();

        if ($request->has('fields')) {
            foreach ($request->fields as $index => $field) {
                ElementField::create([
                    'element_definition_id' => $element->id,
                    'name' => $field['name'],
                    'label' => $field['label'],
                    'type' => $field['type'],
                    'options' => isset($field['options']) ? json_decode($field['options'], true) : null,
                    'default_value' => $field['default_value'] ?? null,
                    'translatable' => isset($field['translatable']),
                    'required' => isset($field['required']),
                    'ordering' => $index,
                ]);
            }
        }

        return redirect()->route('admin.elements.index')
            ->with('success', 'Content Element updated successfully.');
    }

    /**
     * Element tanımı sil
     */
    public function destroy($id)
    {
        $element = ElementDefinition::findOrFail($id);
        $element->fields()->delete();
        $element->elements()->delete();
        $element->delete();

        return redirect()->route('admin.elements.index')
            ->with('success', 'Content Element deleted successfully.');
    }

    /**
     * Sayfa/Makale için element değerlerini al (AJAX)
     */
    public function getElements(Request $request)
    {
        $parentType = $request->input('parent_type'); // page or article
        $parentId = $request->input('parent_id');
        $lang = $request->input('lang', 'tr');

        $elements = PageElement::with('definition.fields')
            ->forParent($parentType, $parentId)
            ->forLang($lang)
            ->orderBy('ordering')
            ->get();

        return response()->json($elements);
    }

    /**
     * Sayfa/Makale için element değerlerini kaydet (AJAX)
     */
    public function saveElements(Request $request)
    {
        $parentType = $request->input('parent_type');
        $parentId = $request->input('parent_id');
        $lang = $request->input('lang');
        $elements = $request->input('elements', []);

        // Mevcut elementleri sil
        PageElement::forParent($parentType, $parentId)
            ->forLang($lang)
            ->delete();

        // Yeni elementleri kaydet
        foreach ($elements as $index => $elementData) {
            PageElement::create([
                'element_definition_id' => $elementData['definition_id'],
                'parent_type' => $parentType,
                'parent_id' => $parentId,
                'lang' => $lang,
                'ordering' => $index,
                'data' => $elementData['data'],
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Element ekle (AJAX - yeni boş element)
     */
    public function addElement(Request $request)
    {
        $definitionId = $request->input('definition_id');
        $definition = ElementDefinition::with('fields')->findOrFail($definitionId);

        return response()->json([
            'definition' => $definition,
            'fields' => $definition->fields,
        ]);
    }
}
