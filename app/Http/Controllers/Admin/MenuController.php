<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::withCount('pages')->orderBy('ordering')->paginate(20);
        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.menus.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:menus,name',
            'title' => 'required|string|max:50',
        ]);

        Menu::create([
            'name' => $request->name,
            'title' => $request->title,
            'ordering' => Menu::max('ordering') + 1,
        ]);

        return redirect()->route('admin.menus.index')->with('success', 'Menü oluşturuldu.');
    }

    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        return view('admin.menus.edit', compact('menu'));
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:50|unique:menus,name,' . $id . ',id_menu',
            'title' => 'required|string|max:50',
        ]);

        $menu->update([
            'name' => $request->name,
            'title' => $request->title,
        ]);

        return redirect()->route('admin.menus.index')->with('success', 'Menü güncellendi.');
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);

        if ($menu->pages()->count() > 0) {
            return redirect()->route('admin.menus.index')
                ->with('error', 'Bu menüye bağlı sayfalar var, önce onları silin.');
        }

        $menu->delete();
        return redirect()->route('admin.menus.index')->with('success', 'Menü silindi.');
    }
}
