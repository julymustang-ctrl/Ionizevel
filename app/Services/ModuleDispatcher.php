<?php

namespace App\Services;

use Illuminate\Http\Request;
use Nwidart\Modules\Facades\Module;
use App\Models\Page;

/**
 * Ionize Module Dispatcher
 * 
 * Page type:module durumunda kontrolü ilgili modüle devreder.
 * Ionize'ın dinamik modül yönlendirme mekanizmasını uygular.
 */
class ModuleDispatcher
{
    /**
     * Sayfa modül türündeyse, ilgili modüle yönlendir
     *
     * @param Page $page
     * @param Request $request
     * @param string $lang
     * @return mixed|null
     */
    public function dispatch(Page $page, Request $request, string $lang)
    {
        // Sayfa modül türünde değilse null dön
        if (!$this->isModulePage($page)) {
            return null;
        }

        // Modül bilgilerini al
        $moduleInfo = $this->getModuleInfo($page);
        
        if (!$moduleInfo) {
            return null;
        }

        // Modül aktif mi kontrol et
        if (!Module::isEnabled($moduleInfo['module'])) {
            return null;
        }

        // Modül controller'ını çağır
        return $this->callModuleController(
            $moduleInfo['module'],
            $moduleInfo['controller'],
            $moduleInfo['action'],
            $page,
            $request,
            $lang
        );
    }

    /**
     * Sayfa modül türünde mi?
     */
    protected function isModulePage(Page $page): bool
    {
        return $page->link_type === 'module' || !empty($page->used_by_module);
    }

    /**
     * Sayfa modül bilgilerini al
     */
    protected function getModuleInfo(Page $page): ?array
    {
        // used_by_module alanından modül bilgisi al
        // Format: "ModuleName" veya "ModuleName@Controller@action"
        $moduleString = $page->used_by_module;
        
        if (empty($moduleString)) {
            return null;
        }

        $parts = explode('@', $moduleString);
        
        return [
            'module' => $parts[0] ?? null,
            'controller' => $parts[1] ?? ($parts[0] . 'Controller'),
            'action' => $parts[2] ?? 'index',
        ];
    }

    /**
     * Modül controller'ını çağır
     */
    protected function callModuleController(
        string $moduleName,
        string $controller,
        string $action,
        Page $page,
        Request $request,
        string $lang
    ) {
        // Controller namespace'i oluştur
        $namespace = "Modules\\{$moduleName}\\App\\Http\\Controllers\\{$controller}";
        
        // Controller var mı kontrol et
        if (!class_exists($namespace)) {
            // Alternatif namespace dene (eski format)
            $namespace = "Modules\\{$moduleName}\\Http\\Controllers\\{$controller}";
            
            if (!class_exists($namespace)) {
                \Log::warning("Module controller not found: {$namespace}");
                return null;
            }
        }

        // Controller'ı oluştur ve metodu çağır
        $controllerInstance = app()->make($namespace);
        
        if (!method_exists($controllerInstance, $action)) {
            \Log::warning("Module action not found: {$namespace}@{$action}");
            return null;
        }

        // Sayfa ve dil bilgisini controller'a aktar
        return $controllerInstance->$action($request, $page, $lang);
    }

    /**
     * Aktif modülleri listele
     */
    public static function getActiveModules(): array
    {
        $modules = Module::allEnabled();
        $list = [];

        foreach ($modules as $module) {
            $list[] = [
                'name' => $module->getName(),
                'path' => $module->getPath(),
                'enabled' => true,
            ];
        }

        return $list;
    }

    /**
     * Tüm modülleri listele (aktif/pasif)
     */
    public static function getAllModules(): array
    {
        $modules = Module::all();
        $list = [];

        foreach ($modules as $module) {
            $list[] = [
                'name' => $module->getName(),
                'path' => $module->getPath(),
                'enabled' => Module::isEnabled($module->getName()),
                'description' => $module->get('description', ''),
            ];
        }

        return $list;
    }

    /**
     * Modül-sayfa eşlemesi için seçenekler
     */
    public static function getModuleOptions(): array
    {
        $modules = Module::allEnabled();
        $options = ['' => '-- Select Module --'];

        foreach ($modules as $module) {
            $moduleName = $module->getName();
            $options[$moduleName] = $moduleName;
            
            // Controller'ları da listele
            $controllerPath = $module->getPath() . '/app/Http/Controllers';
            if (is_dir($controllerPath)) {
                foreach (glob($controllerPath . '/*Controller.php') as $file) {
                    $controllerName = basename($file, '.php');
                    $options["{$moduleName}@{$controllerName}"] = "{$moduleName} → {$controllerName}";
                }
            }
        }

        return $options;
    }
}
