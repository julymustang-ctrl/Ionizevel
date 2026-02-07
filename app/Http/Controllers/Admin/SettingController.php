<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Language;
use Illuminate\Support\Facades\File;

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
            'site_name',
            'baseline',
            'admin_email',
            'default_lang',
            'media_path',
            'max_upload_size',
            'maintenance',
            'maintenance_message',
            'google_analytics',
            'google_tag_manager',
        ];

        foreach ($settingsToUpdate as $name) {
            if ($request->has($name)) {
                Setting::set($name, $request->input($name));
            }
        }

        // Handle language-specific SEO settings
        $languages = Language::online()->get();
        foreach ($languages as $lang) {
            $seoFields = ["meta_title_{$lang->lang}", "meta_description_{$lang->lang}", "meta_keywords_{$lang->lang}"];
            foreach ($seoFields as $field) {
                if ($request->has($field)) {
                    Setting::set($field, $request->input($field));
                }
            }
        }

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully.');
    }

    /**
     * Theme Manager - Shows available themes and their view files
     */
    public function theme(Request $request)
    {
        $settings = Setting::whereNull('lang')->get()->keyBy('name');
        
        // Get active theme from request or settings
        $activeTheme = $request->get('theme', $settings['theme']->content ?? 'default');
        
        // Available themes - scan themes directory
        $themesPath = resource_path('views/themes');
        $themes = [];
        
        // If themes directory doesn't exist, use frontend as default
        if (!File::isDirectory($themesPath)) {
            // Use frontend views as default theme
            $themes[] = [
                'name' => 'frontend',
                'title' => 'Frontend (Default)',
                'path' => resource_path('views/frontend'),
            ];
            $activeTheme = 'frontend';
        } else {
            foreach (File::directories($themesPath) as $themeDir) {
                $themeName = basename($themeDir);
                $themes[] = [
                    'name' => $themeName,
                    'title' => ucfirst(str_replace(['_', '-'], ' ', $themeName)),
                    'path' => $themeDir,
                ];
            }
        }
        
        // Also add frontend as a theme option
        if (File::isDirectory(resource_path('views/frontend'))) {
            array_unshift($themes, [
                'name' => 'frontend',
                'title' => 'Frontend (Default)',
                'path' => resource_path('views/frontend'),
            ]);
        }
        
        // Get view files for active theme
        $viewFiles = $this->scanThemeViews($activeTheme);
        
        return view('admin.settings.theme', compact('settings', 'themes', 'activeTheme', 'viewFiles'));
    }

    /**
     * Scan theme directory for view files
     */
    protected function scanThemeViews(string $themeName): array
    {
        $viewFiles = [];
        
        // Determine theme path
        $themePath = resource_path("views/themes/{$themeName}");
        if (!File::isDirectory($themePath)) {
            $themePath = resource_path("views/{$themeName}");
        }
        
        if (!File::isDirectory($themePath)) {
            return $viewFiles;
        }
        
        // Get all blade files recursively
        $files = File::allFiles($themePath);
        
        // Get saved logical names from settings
        $logicalNames = json_decode(Setting::get('theme_logical_names', '{}'), true) ?: [];
        $fileTypes = json_decode(Setting::get('theme_file_types', '{}'), true) ?: [];
        
        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') continue;
            
            $relativePath = str_replace($themePath . DIRECTORY_SEPARATOR, '', $file->getPathname());
            $relativePath = str_replace('\\', '/', $relativePath);
            $folder = dirname($relativePath);
            $fileName = $file->getFilename();
            
            // Auto-detect type based on filename
            $autoType = $this->detectFileType($fileName, $folder);
            
            // Generate default logical name from filename
            $defaultLogicalName = str_replace('.blade.php', '', $fileName);
            $defaultLogicalName = str_replace('.php', '', $defaultLogicalName);
            
            $viewFiles[] = [
                'name' => $fileName,
                'folder' => $folder === '.' ? '' : $folder,
                'path' => $relativePath,
                'logical_name' => $logicalNames[$relativePath] ?? $defaultLogicalName,
                'type' => $fileTypes[$relativePath] ?? $autoType,
                'is_folder' => false,
            ];
        }
        
        // Sort: folders first, then files
        usort($viewFiles, function ($a, $b) {
            if ($a['folder'] !== $b['folder']) {
                return strcmp($a['folder'], $b['folder']);
            }
            return strcmp($a['name'], $b['name']);
        });
        
        return $viewFiles;
    }

    /**
     * Auto-detect file type based on filename and folder
     */
    protected function detectFileType(string $fileName, string $folder): string
    {
        $fileNameLower = strtolower($fileName);
        $folderLower = strtolower($folder);
        
        // Check folder first
        if (str_contains($folderLower, 'layout')) return 'layout';
        if (str_contains($folderLower, 'partial')) return 'partial';
        if (str_contains($folderLower, 'element')) return 'element';
        
        // Check filename
        if (str_contains($fileNameLower, 'article')) return 'article';
        if (str_contains($fileNameLower, 'page')) return 'page';
        if (str_contains($fileNameLower, 'header') || str_contains($fileNameLower, 'footer') || 
            str_contains($fileNameLower, 'sidebar') || str_contains($fileNameLower, 'nav')) {
            return 'partial';
        }
        if (str_contains($fileNameLower, 'layout') || str_contains($fileNameLower, 'main') || 
            str_contains($fileNameLower, 'master') || str_contains($fileNameLower, 'app')) {
            return 'layout';
        }
        
        return 'page'; // default
    }

    /**
     * Update theme settings including logical names
     */
    public function updateTheme(Request $request)
    {
        // Save active theme
        if ($request->has('theme')) {
            Setting::set('theme', $request->input('theme'));
        }
        
        // Save logical names
        if ($request->has('logical_names')) {
            Setting::set('theme_logical_names', json_encode($request->input('logical_names')));
        }
        
        // Save file types
        if ($request->has('types')) {
            Setting::set('theme_file_types', json_encode($request->input('types')));
        }
        
        // Save default views
        if ($request->has('default_page_view')) {
            Setting::set('default_page_view', $request->input('default_page_view'));
        }
        if ($request->has('default_article_view')) {
            Setting::set('default_article_view', $request->input('default_article_view'));
        }

        return redirect()->route('admin.theme.index')->with('success', 'Theme settings updated successfully.');
    }

    /**
     * Edit a theme view file
     */
    public function editFile(Request $request, string $file)
    {
        $filePath = base64_decode($file);
        $settings = Setting::whereNull('lang')->get()->keyBy('name');
        $activeTheme = $settings['theme']->content ?? 'frontend';
        
        // Build full path
        $themePath = resource_path("views/themes/{$activeTheme}");
        if (!File::isDirectory($themePath)) {
            $themePath = resource_path("views/{$activeTheme}");
        }
        
        $fullPath = $themePath . '/' . $filePath;
        
        if (!File::exists($fullPath)) {
            return redirect()->route('admin.theme.index')->with('error', 'File not found.');
        }
        
        $content = File::get($fullPath);
        $fileName = basename($filePath);
        
        return view('admin.settings.theme-edit', compact('filePath', 'content', 'fileName', 'activeTheme'));
    }

    /**
     * Save edited theme file
     */
    public function saveFile(Request $request)
    {
        $filePath = $request->input('file_path');
        $content = $request->input('content');
        
        $settings = Setting::whereNull('lang')->get()->keyBy('name');
        $activeTheme = $settings['theme']->content ?? 'frontend';
        
        // Build full path
        $themePath = resource_path("views/themes/{$activeTheme}");
        if (!File::isDirectory($themePath)) {
            $themePath = resource_path("views/{$activeTheme}");
        }
        
        $fullPath = $themePath . '/' . $filePath;
        
        // Create backup
        if (File::exists($fullPath)) {
            File::copy($fullPath, $fullPath . '.backup');
        }
        
        File::put($fullPath, $content);
        
        return redirect()->route('admin.theme.index')->with('success', 'File saved successfully.');
    }

    /**
     * Create a new theme
     */
    public function createTheme(Request $request)
    {
        $themeName = $request->get('name');
        
        if (!$themeName) {
            return redirect()->route('admin.theme.index')->with('error', 'Theme name is required.');
        }
        
        $themeName = preg_replace('/[^a-z0-9_-]/', '', strtolower($themeName));
        $themePath = resource_path("views/themes/{$themeName}");
        
        if (File::isDirectory($themePath)) {
            return redirect()->route('admin.theme.index')->with('error', 'Theme already exists.');
        }
        
        // Create theme directory structure
        File::makeDirectory($themePath, 0755, true);
        File::makeDirectory($themePath . '/layouts', 0755, true);
        File::makeDirectory($themePath . '/partials', 0755, true);
        
        // Create basic template files
        File::put($themePath . '/page.blade.php', $this->getDefaultPageTemplate());
        File::put($themePath . '/article.blade.php', $this->getDefaultArticleTemplate());
        File::put($themePath . '/layouts/main.blade.php', $this->getDefaultLayoutTemplate());
        
        return redirect()->route('admin.theme.index', ['theme' => $themeName])
            ->with('success', 'Theme created successfully.');
    }

    protected function getDefaultPageTemplate(): string
    {
        return <<<'BLADE'
@extends('themes.'.config('app.theme', 'default').'.layouts.main')

@section('content')
<div class="page-content">
    <h1>{{ $page->translate($lang)->title ?? $page->name }}</h1>
    
    @if($page->articles->count() > 0)
        <div class="articles">
            @foreach($page->articles as $article)
                <article>
                    <h2>{{ $article->translate($lang)->title ?? $article->name }}</h2>
                    {!! $article->translate($lang)->content ?? '' !!}
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection
BLADE;
    }

    protected function getDefaultArticleTemplate(): string
    {
        return <<<'BLADE'
@extends('themes.'.config('app.theme', 'default').'.layouts.main')

@section('content')
<article class="article-content">
    <h1>{{ $article->translate($lang)->title ?? $article->name }}</h1>
    
    @if($article->translate($lang)->subtitle)
        <p class="subtitle">{{ $article->translate($lang)->subtitle }}</p>
    @endif
    
    <div class="content">
        {!! $article->translate($lang)->content ?? '' !!}
    </div>
</article>
@endsection
BLADE;
    }

    protected function getDefaultLayoutTemplate(): string
    {
        return <<<'BLADE'
<!DOCTYPE html>
<html lang="{{ $lang ?? 'en' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Site Title')</title>
</head>
<body>
    <header>
        <!-- Header content -->
    </header>
    
    <main>
        @yield('content')
    </main>
    
    <footer>
        <!-- Footer content -->
    </footer>
</body>
</html>
BLADE;
    }
}
