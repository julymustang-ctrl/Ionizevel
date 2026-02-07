<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Nwidart\Modules\Facades\Module;
use App\Services\ModuleDispatcher;

class ModuleController extends Controller
{
    /**
     * Modül listesi
     */
    public function index()
    {
        $modules = ModuleDispatcher::getAllModules();
        return view('admin.modules.index', compact('modules'));
    }

    /**
     * Modül detayları
     */
    public function show($name)
    {
        $module = Module::find($name);
        
        if (!$module) {
            return redirect()->route('admin.modules.index')
                ->with('error', "Module '{$name}' not found.");
        }

        $moduleInfo = [
            'name' => $module->getName(),
            'path' => $module->getPath(),
            'enabled' => Module::isEnabled($name),
            'description' => $module->get('description', ''),
            'version' => $module->get('version', '1.0.0'),
            'providers' => $module->get('providers', []),
            'aliases' => $module->get('aliases', []),
        ];

        // Modül routes
        $routesPath = $module->getPath() . '/routes/web.php';
        $hasRoutes = file_exists($routesPath);

        // Modül migrations
        $migrationsPath = $module->getPath() . '/database/migrations';
        $migrations = is_dir($migrationsPath) ? glob($migrationsPath . '/*.php') : [];

        // Modül controllers
        $controllersPath = $module->getPath() . '/app/Http/Controllers';
        $controllers = is_dir($controllersPath) ? glob($controllersPath . '/*Controller.php') : [];

        return view('admin.modules.show', compact('moduleInfo', 'hasRoutes', 'migrations', 'controllers'));
    }

    /**
     * Modülü aktifleştir
     */
    public function enable($name)
    {
        $module = Module::find($name);
        
        if (!$module) {
            return redirect()->route('admin.modules.index')
                ->with('error', "Module '{$name}' not found.");
        }

        $module->enable();

        return redirect()->route('admin.modules.index')
            ->with('success', "Module '{$name}' enabled successfully.");
    }

    /**
     * Modülü devre dışı bırak
     */
    public function disable($name)
    {
        $module = Module::find($name);
        
        if (!$module) {
            return redirect()->route('admin.modules.index')
                ->with('error', "Module '{$name}' not found.");
        }

        $module->disable();

        return redirect()->route('admin.modules.index')
            ->with('success', "Module '{$name}' disabled successfully.");
    }

    /**
     * Modül migration çalıştır
     */
    public function migrate($name)
    {
        $module = Module::find($name);
        
        if (!$module) {
            return redirect()->route('admin.modules.show', $name)
                ->with('error', "Module '{$name}' not found.");
        }

        try {
            \Artisan::call('module:migrate', ['module' => $name]);
            
            return redirect()->route('admin.modules.show', $name)
                ->with('success', 'Migrations run successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.modules.show', $name)
                ->with('error', 'Migration failed: ' . $e->getMessage());
        }
    }

    /**
     * API: Modül listesi
     */
    public function apiList()
    {
        return response()->json([
            'modules' => ModuleDispatcher::getAllModules()
        ]);
    }

    /**
     * API: Sayfa için modül seçenekleri
     */
    public function apiOptions()
    {
        return response()->json([
            'options' => ModuleDispatcher::getModuleOptions()
        ]);
    }
}
