<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    /**
     * Website ayarları
     */
    public function index()
    {
        $settings = Setting::all()->keyBy('name');
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Website ayarlarını kaydet
     */
    public function update(Request $request)
    {
        $settingsToSave = [
            'site_name', 'site_description', 'site_baseline', 
            'admin_email', 'contact_email', 
            'google_analytics', 'files_path', 'media_path',
            'maintenance_mode', 'cache_enabled', 
            'editor_type', 'editor_toolbar'
        ];

        foreach ($settingsToSave as $name) {
            if ($request->has($name)) {
                Setting::updateOrCreate(
                    ['name' => $name],
                    ['value' => $request->input($name)]
                );
            }
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings saved successfully.');
    }

    /**
     * Advanced settings view
     */
    public function advanced()
    {
        return view('admin.settings.advanced');
    }

    /**
     * Clear all cache
     */
    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            
            return redirect()->route('admin.settings.advanced')
                ->with('success', 'All cache cleared successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.advanced')
                ->with('error', 'Failed to clear cache: ' . $e->getMessage());
        }
    }

    /**
     * Optimize database tables
     */
    public function optimizeDb()
    {
        try {
            $tables = DB::select('SHOW TABLES');
            $dbName = config('database.connections.mysql.database');
            
            foreach ($tables as $table) {
                $tableName = $table->{"Tables_in_{$dbName}"};
                DB::statement("OPTIMIZE TABLE `{$tableName}`");
            }
            
            return redirect()->route('admin.settings.advanced')
                ->with('success', 'Database tables optimized successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.advanced')
                ->with('error', 'Failed to optimize: ' . $e->getMessage());
        }
    }

    /**
     * Clear log files
     */
    public function clearLogs()
    {
        try {
            $logPath = storage_path('logs');
            $files = glob($logPath . '/*.log');
            
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            
            return redirect()->route('admin.settings.advanced')
                ->with('success', 'Log files cleared!');
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.advanced')
                ->with('error', 'Failed to clear logs: ' . $e->getMessage());
        }
    }

    /**
     * Clear session files
     */
    public function clearSessions()
    {
        try {
            $sessionPath = storage_path('framework/sessions');
            $files = glob($sessionPath . '/*');
            
            foreach ($files as $file) {
                if (is_file($file) && basename($file) !== '.gitignore') {
                    unlink($file);
                }
            }
            
            return redirect()->route('admin.settings.advanced')
                ->with('success', 'Sessions cleared! Users will need to log in again.');
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.advanced')
                ->with('error', 'Failed to clear sessions: ' . $e->getMessage());
        }
    }

    /**
     * Tracker settings save
     */
    public function trackerSettings(Request $request)
    {
        Setting::updateOrCreate(
            ['name' => 'tracker_enabled'],
            ['value' => $request->boolean('tracker_enabled') ? '1' : '0']
        );
        
        Setting::updateOrCreate(
            ['name' => 'log_admin_actions'],
            ['value' => $request->boolean('log_admin_actions') ? '1' : '0']
        );
        
        Setting::updateOrCreate(
            ['name' => 'log_retention_days'],
            ['value' => $request->input('log_retention_days', 30)]
        );
        
        Setting::updateOrCreate(
            ['name' => 'tracker_exclude_ips'],
            ['value' => $request->input('tracker_exclude_ips', '')]
        );

        return redirect()->route('admin.settings.advanced')
            ->with('success', 'Tracker settings saved!');
    }

    /**
     * Backup database
     */
    public function backupDb()
    {
        try {
            $dbName = config('database.connections.mysql.database');
            $backupPath = storage_path('app/backups');
            
            if (!is_dir($backupPath)) {
                mkdir($backupPath, 0755, true);
            }
            
            $filename = $dbName . '_' . date('Y-m-d_His') . '.sql';
            $fullPath = $backupPath . '/' . $filename;
            
            // Use mysqldump if available
            $host = config('database.connections.mysql.host');
            $user = config('database.connections.mysql.username');
            $pass = config('database.connections.mysql.password');
            
            $command = sprintf(
                'mysqldump --host=%s --user=%s --password=%s %s > %s',
                escapeshellarg($host),
                escapeshellarg($user),
                escapeshellarg($pass),
                escapeshellarg($dbName),
                escapeshellarg($fullPath)
            );
            
            exec($command, $output, $returnVar);
            
            if ($returnVar === 0 && file_exists($fullPath)) {
                return redirect()->route('admin.settings.advanced')
                    ->with('success', "Database backup created: {$filename}");
            } else {
                return redirect()->route('admin.settings.advanced')
                    ->with('error', 'Backup failed. Please check mysqldump is available.');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.advanced')
                ->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }
}
