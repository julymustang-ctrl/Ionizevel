<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ContentElementController;
use App\Http\Controllers\Admin\TranslationController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Frontend Routes (SEO-friendly Hierarchical URLs - Ionize Style)
// Ana sayfa
Route::get('/', [FrontendController::class, 'home'])->name('home');

// Dil ile ana sayfa
Route::get('/{lang}', [FrontendController::class, 'home'])
    ->where('lang', '[a-z]{2}')
    ->name('home.lang');

// Tek seviye sayfa (geriye uyumluluk)
Route::get('/{lang}/{url}', [FrontendController::class, 'page'])
    ->where('lang', '[a-z]{2}')
    ->where('url', '[a-z0-9\-]+')
    ->name('page');

// İki seviye: parent/child veya page/article
Route::get('/{lang}/{segment1}/{segment2}', [FrontendController::class, 'catchAll'])
    ->where('lang', '[a-z]{2}')
    ->where('segment1', '[a-z0-9\-]+')
    ->where('segment2', '[a-z0-9\-]+')
    ->name('page.level2');

// Üç seviye: parent/child/grandchild veya parent/child/article
Route::get('/{lang}/{segment1}/{segment2}/{segment3}', [FrontendController::class, 'catchAll'])
    ->where('lang', '[a-z]{2}')
    ->name('page.level3');

// Dört ve daha fazla seviye (catch-all)
Route::get('/{lang}/{segments}', [FrontendController::class, 'catchAll'])
    ->where('lang', '[a-z]{2}')
    ->where('segments', '.*')
    ->name('page.catchall');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes
Route::prefix('admin')->middleware('admin')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Sayfalar
    Route::resource('pages', PageController::class)->names([
        'index' => 'admin.pages.index',
        'create' => 'admin.pages.create',
        'store' => 'admin.pages.store',
        'edit' => 'admin.pages.edit',
        'update' => 'admin.pages.update',
        'destroy' => 'admin.pages.destroy',
    ]);
    Route::post('pages/reorder', [PageController::class, 'reorder'])->name('admin.pages.reorder');
    Route::post('pages/{id}/duplicate', [PageController::class, 'duplicate'])->name('admin.pages.duplicate');
    Route::post('pages/{id}/toggle-online', [PageController::class, 'toggleOnline'])->name('admin.pages.toggle-online');

    // Modules (nwidart/laravel-modules)
    Route::get('modules', [\App\Http\Controllers\Admin\ModuleController::class, 'index'])->name('admin.modules.index');
    Route::get('modules/{name}', [\App\Http\Controllers\Admin\ModuleController::class, 'show'])->name('admin.modules.show');
    Route::post('modules/{name}/enable', [\App\Http\Controllers\Admin\ModuleController::class, 'enable'])->name('admin.modules.enable');
    Route::post('modules/{name}/disable', [\App\Http\Controllers\Admin\ModuleController::class, 'disable'])->name('admin.modules.disable');
    Route::post('modules/{name}/migrate', [\App\Http\Controllers\Admin\ModuleController::class, 'migrate'])->name('admin.modules.migrate');

    // Makaleler
    Route::resource('articles', ArticleController::class)->names([
        'index' => 'admin.articles.index',
        'create' => 'admin.articles.create',
        'store' => 'admin.articles.store',
        'edit' => 'admin.articles.edit',
        'update' => 'admin.articles.update',
        'destroy' => 'admin.articles.destroy',
    ]);

    // Kategoriler
    Route::resource('categories', CategoryController::class)->names([
        'index' => 'admin.categories.index',
        'create' => 'admin.categories.create',
        'store' => 'admin.categories.store',
        'edit' => 'admin.categories.edit',
        'update' => 'admin.categories.update',
        'destroy' => 'admin.categories.destroy',
    ]);
    Route::post('categories/reorder', [CategoryController::class, 'reorder'])->name('admin.categories.reorder');

    // Medya
    Route::resource('media', MediaController::class)->names([
        'index' => 'admin.media.index',
        'create' => 'admin.media.create',
        'store' => 'admin.media.store',
        'edit' => 'admin.media.edit',
        'update' => 'admin.media.update',
        'destroy' => 'admin.media.destroy',
    ]);
    Route::get('media/json', [MediaController::class, 'json'])->name('admin.media.json');
    Route::post('media/upload-ajax', [MediaController::class, 'uploadAjax'])->name('admin.media.upload-ajax');
    Route::get('media/{id}/details', [MediaController::class, 'details'])->name('admin.media.details');
    Route::post('media/create-folder', [MediaController::class, 'createFolder'])->name('admin.media.create-folder');
    Route::post('media/move-file', [MediaController::class, 'moveFile'])->name('admin.media.move-file');
    Route::post('media/bulk-delete', [MediaController::class, 'bulkDelete'])->name('admin.media.bulk-delete');
    Route::post('media/bulk-move', [MediaController::class, 'bulkMove'])->name('admin.media.bulk-move');
    Route::post('media/bulk-download', [MediaController::class, 'bulkDownload'])->name('admin.media.bulk-download');
    Route::post('media/bulk-rename', [MediaController::class, 'bulkRename'])->name('admin.media.bulk-rename');

    // Menüler
    Route::resource('menus', MenuController::class)->names([
        'index' => 'admin.menus.index',
        'create' => 'admin.menus.create',
        'store' => 'admin.menus.store',
        'edit' => 'admin.menus.edit',
        'update' => 'admin.menus.update',
        'destroy' => 'admin.menus.destroy',
    ]);

    // Diller
    Route::resource('languages', LanguageController::class)->names([
        'index' => 'admin.languages.index',
        'create' => 'admin.languages.create',
        'store' => 'admin.languages.store',
        'edit' => 'admin.languages.edit',
        'update' => 'admin.languages.update',
        'destroy' => 'admin.languages.destroy',
    ]);

    // Kullanıcılar
    Route::resource('users', UserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);

    // Content Elements
    Route::resource('elements', ContentElementController::class)->names([
        'index' => 'admin.elements.index',
        'create' => 'admin.elements.create',
        'store' => 'admin.elements.store',
        'edit' => 'admin.elements.edit',
        'update' => 'admin.elements.update',
        'destroy' => 'admin.elements.destroy',
    ]);
    Route::post('elements/get', [ContentElementController::class, 'getElements'])->name('admin.elements.get');
    Route::post('elements/save', [ContentElementController::class, 'saveElements'])->name('admin.elements.save');
    Route::post('elements/add', [ContentElementController::class, 'addElement'])->name('admin.elements.add');

    // Ayarlar
    Route::get('settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('admin.settings.update');

    // Theme Manager
    Route::get('settings/theme', [SettingController::class, 'theme'])->name('admin.theme.index');
    Route::post('settings/theme', [SettingController::class, 'updateTheme'])->name('admin.theme.update');
    Route::get('settings/theme/edit/{file}', [SettingController::class, 'editFile'])->name('admin.theme.edit-file');
    Route::post('settings/theme/save', [SettingController::class, 'saveFile'])->name('admin.theme.save-file');
    Route::get('settings/theme/create', [SettingController::class, 'createTheme'])->name('admin.theme.create');

    // Tools
    Route::get('tools/diagnose', function () {
        return view('admin.tools.diagnose');
    })->name('admin.tools.diagnose');

    Route::post('cache/clear', function () {
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('view:clear');
        return response()->json(['success' => true]);
    })->name('admin.cache.clear');

    // Translations
    Route::resource('translations', TranslationController::class)->names([
        'index' => 'admin.translations.index',
        'create' => 'admin.translations.create',
        'store' => 'admin.translations.store',
        'edit' => 'admin.translations.edit',
        'update' => 'admin.translations.update',
        'destroy' => 'admin.translations.destroy',
    ]);
    Route::post('translations/update-ajax', [TranslationController::class, 'updateAjax'])->name('admin.translations.update-ajax');
    Route::get('translations/import', [TranslationController::class, 'import'])->name('admin.translations.import');

    // Roles
    Route::resource('roles', RoleController::class)->names([
        'index' => 'admin.roles.index',
        'create' => 'admin.roles.create',
        'store' => 'admin.roles.store',
        'edit' => 'admin.roles.edit',
        'update' => 'admin.roles.update',
        'destroy' => 'admin.roles.destroy',
    ]);

    // Advanced Settings
    Route::get('settings/advanced', [SettingsController::class, 'advanced'])->name('admin.settings.advanced');
    Route::post('settings/clear-cache', [SettingsController::class, 'clearCache'])->name('admin.settings.clear-cache');
    Route::post('settings/optimize-db', [SettingsController::class, 'optimizeDb'])->name('admin.settings.optimize-db');
    Route::post('settings/clear-logs', [SettingsController::class, 'clearLogs'])->name('admin.settings.clear-logs');
    Route::post('settings/clear-sessions', [SettingsController::class, 'clearSessions'])->name('admin.settings.clear-sessions');
    Route::post('settings/tracker', [SettingsController::class, 'trackerSettings'])->name('admin.settings.tracker');
    Route::post('settings/backup-db', [SettingsController::class, 'backupDb'])->name('admin.settings.backup-db');

    // Help
    Route::get('help', function () {
        return view('admin.help.index');
    })->name('admin.help');
});

// Secure Media Download Routes (Public - SHA-1 Hash)
Route::get('/download/{id}/{hash}', [\App\Http\Controllers\MediaDownloadController::class, 'download'])->name('media.download');
Route::get('/view/{id}/{hash}', [\App\Http\Controllers\MediaDownloadController::class, 'view'])->name('media.view');
Route::get('/download/{id}/{expires}/{hash}', [\App\Http\Controllers\MediaDownloadController::class, 'downloadTimed'])->name('media.download.timed');
