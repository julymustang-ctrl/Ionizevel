<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Language;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::whereNull('lang')->get()->keyBy('name');
        $languages = Language::online()->orderBy('ordering')->get();

        return view('admin.settings.index', compact('settings', 'languages'));
    }

    public function update(Request $request)
    {
        $settingsToUpdate = [
            'website_email',
            'files_path',
            'cache',
            'cache_time',
            'theme',
            'theme_admin',
            'texteditor',
            'media_thumb_size',
            'default_admin_lang',
        ];

        foreach ($settingsToUpdate as $name) {
            if ($request->has($name)) {
                Setting::set($name, $request->input($name));
            }
        }

        return redirect()->route('admin.settings.index')->with('success', 'Ayarlar güncellendi.');
    }
}
