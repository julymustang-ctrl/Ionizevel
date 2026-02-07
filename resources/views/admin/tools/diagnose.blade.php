@extends('layouts.admin')

@section('title', 'System Diagnose')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span>›</span>
        <span>Tools</span>
        <span>›</span>
        <span>System Diagnose</span>
    </div>
@endsection

@section('content')
<style>
    .diagnose-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 15px;
    }

    .diagnose-panel {
        background: white;
        border: 1px solid var(--border-color);
    }

    .diagnose-header {
        padding: 12px 15px;
        background: var(--bg-gray-light);
        border-bottom: 1px solid var(--border-color);
        font-weight: 600;
        font-size: 12px;
        color: #36607D;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .diagnose-header svg {
        width: 18px;
        height: 18px;
        color: var(--primary);
    }

    .diagnose-body {
        padding: 15px;
    }

    .diagnose-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #eee;
        font-size: 11px;
    }

    .diagnose-item:last-child { border-bottom: none; }

    .diagnose-item .label { color: #666; }
    .diagnose-item .value { font-weight: 500; color: #333; }

    .status-ok { color: var(--success); }
    .status-warning { color: var(--warning); }
    .status-error { color: var(--danger); }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 2px 8px;
        border-radius: 3px;
        font-size: 10px;
    }

    .status-badge.ok { background: #d4edda; color: #155724; }
    .status-badge.warning { background: #fff3cd; color: #856404; }
    .status-badge.error { background: #f8d7da; color: #721c24; }

    .folder-list {
        max-height: 200px;
        overflow-y: auto;
    }

    .folder-item {
        display: flex;
        justify-content: space-between;
        padding: 5px 0;
        font-size: 10px;
        border-bottom: 1px solid #f5f5f5;
    }
</style>

<div class="page-header">
    <div class="page-header-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="var(--primary)">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
        </svg>
    </div>
    <div>
        <h2>System Diagnose</h2>
        <div class="subtitle">Server and application status check</div>
    </div>
</div>

<div class="diagnose-grid">
    <!-- PHP Info -->
    <div class="diagnose-panel">
        <div class="diagnose-header">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
            </svg>
            PHP Information
        </div>
        <div class="diagnose-body">
            <div class="diagnose-item">
                <span class="label">PHP Version</span>
                <span class="value">{{ phpversion() }}</span>
            </div>
            <div class="diagnose-item">
                <span class="label">Required Version</span>
                <span class="value">8.2+</span>
            </div>
            <div class="diagnose-item">
                <span class="label">Status</span>
                <span class="status-badge {{ version_compare(phpversion(), '8.2', '>=') ? 'ok' : 'error' }}">
                    {{ version_compare(phpversion(), '8.2', '>=') ? '✓ OK' : '✗ Update Required' }}
                </span>
            </div>
            <div class="diagnose-item">
                <span class="label">Memory Limit</span>
                <span class="value">{{ ini_get('memory_limit') }}</span>
            </div>
            <div class="diagnose-item">
                <span class="label">Max Execution Time</span>
                <span class="value">{{ ini_get('max_execution_time') }}s</span>
            </div>
            <div class="diagnose-item">
                <span class="label">Upload Max Filesize</span>
                <span class="value">{{ ini_get('upload_max_filesize') }}</span>
            </div>
            <div class="diagnose-item">
                <span class="label">Post Max Size</span>
                <span class="value">{{ ini_get('post_max_size') }}</span>
            </div>
        </div>
    </div>

    <!-- Laravel Info -->
    <div class="diagnose-panel">
        <div class="diagnose-header">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Laravel Information
        </div>
        <div class="diagnose-body">
            <div class="diagnose-item">
                <span class="label">Laravel Version</span>
                <span class="value">{{ app()->version() }}</span>
            </div>
            <div class="diagnose-item">
                <span class="label">Environment</span>
                <span class="value">{{ app()->environment() }}</span>
            </div>
            <div class="diagnose-item">
                <span class="label">Debug Mode</span>
                <span class="status-badge {{ config('app.debug') ? 'warning' : 'ok' }}">
                    {{ config('app.debug') ? 'Enabled' : 'Disabled' }}
                </span>
            </div>
            <div class="diagnose-item">
                <span class="label">Cache Driver</span>
                <span class="value">{{ config('cache.default') }}</span>
            </div>
            <div class="diagnose-item">
                <span class="label">Session Driver</span>
                <span class="value">{{ config('session.driver') }}</span>
            </div>
            <div class="diagnose-item">
                <span class="label">Queue Driver</span>
                <span class="value">{{ config('queue.default') }}</span>
            </div>
        </div>
    </div>

    <!-- Database Info -->
    <div class="diagnose-panel">
        <div class="diagnose-header">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
            </svg>
            Database Information
        </div>
        <div class="diagnose-body">
            @php
                try {
                    $dbConnection = true;
                    $pdo = DB::connection()->getPdo();
                    $dbVersion = $pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
                    $dbDriver = config('database.default');
                    $dbName = config('database.connections.' . $dbDriver . '.database');
                } catch (\Exception $e) {
                    $dbConnection = false;
                    $dbVersion = 'N/A';
                    $dbName = 'N/A';
                    $dbDriver = 'N/A';
                }
            @endphp
            <div class="diagnose-item">
                <span class="label">Connection</span>
                <span class="status-badge {{ $dbConnection ? 'ok' : 'error' }}">
                    {{ $dbConnection ? '✓ Connected' : '✗ Failed' }}
                </span>
            </div>
            <div class="diagnose-item">
                <span class="label">Driver</span>
                <span class="value">{{ $dbDriver }}</span>
            </div>
            <div class="diagnose-item">
                <span class="label">Database</span>
                <span class="value">{{ $dbName }}</span>
            </div>
            <div class="diagnose-item">
                <span class="label">Version</span>
                <span class="value">{{ $dbVersion }}</span>
            </div>
            <div class="diagnose-item">
                <span class="label">Tables</span>
                <span class="value">{{ count(DB::select('SHOW TABLES')) }}</span>
            </div>
        </div>
    </div>

    <!-- PHP Extensions -->
    <div class="diagnose-panel">
        <div class="diagnose-header">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
            </svg>
            PHP Extensions
        </div>
        <div class="diagnose-body">
            @php
                $requiredExtensions = ['pdo', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath', 'fileinfo', 'gd'];
            @endphp
            @foreach($requiredExtensions as $ext)
                <div class="diagnose-item">
                    <span class="label">{{ strtoupper($ext) }}</span>
                    <span class="status-badge {{ extension_loaded($ext) ? 'ok' : 'error' }}">
                        {{ extension_loaded($ext) ? '✓ Loaded' : '✗ Missing' }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Writable Folders -->
    <div class="diagnose-panel">
        <div class="diagnose-header">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
            </svg>
            Writable Folders
        </div>
        <div class="diagnose-body">
            @php
                $folders = [
                    'storage' => storage_path(),
                    'storage/app' => storage_path('app'),
                    'storage/framework' => storage_path('framework'),
                    'storage/logs' => storage_path('logs'),
                    'bootstrap/cache' => base_path('bootstrap/cache'),
                    'public/storage' => public_path('storage'),
                ];
            @endphp
            <div class="folder-list">
                @foreach($folders as $name => $path)
                    <div class="folder-item">
                        <span>{{ $name }}</span>
                        <span class="status-badge {{ is_writable($path) ? 'ok' : 'error' }}">
                            {{ is_writable($path) ? '✓ Writable' : '✗ Not Writable' }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Server Info -->
    <div class="diagnose-panel">
        <div class="diagnose-header">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
            </svg>
            Server Information
        </div>
        <div class="diagnose-body">
            <div class="diagnose-item">
                <span class="label">Server Software</span>
                <span class="value">{{ $_SERVER['SERVER_SOFTWARE'] ?? 'N/A' }}</span>
            </div>
            <div class="diagnose-item">
                <span class="label">Server Protocol</span>
                <span class="value">{{ $_SERVER['SERVER_PROTOCOL'] ?? 'N/A' }}</span>
            </div>
            <div class="diagnose-item">
                <span class="label">Document Root</span>
                <span class="value" style="font-size: 9px;">{{ $_SERVER['DOCUMENT_ROOT'] ?? 'N/A' }}</span>
            </div>
            <div class="diagnose-item">
                <span class="label">Server Time</span>
                <span class="value">{{ now()->format('Y-m-d H:i:s') }}</span>
            </div>
            <div class="diagnose-item">
                <span class="label">Timezone</span>
                <span class="value">{{ config('app.timezone') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
