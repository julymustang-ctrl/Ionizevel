@extends('layouts.admin')

@section('title', 'Advanced Settings')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span>›</span>
        <span>Advanced Settings</span>
    </div>
@endsection

@section('content')
<style>
    .settings-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .status-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
        font-size: 11px;
    }

    .status-item .label {
        color: #666;
    }

    .status-item .value {
        font-weight: 500;
    }

    .status-item .value.good {
        color: #28a745;
    }

    .status-item .value.warning {
        color: #ffc107;
    }

    .status-item .value.danger {
        color: #dc3545;
    }

    .action-button {
        display: block;
        padding: 15px;
        background: var(--bg-gray-light);
        border: 1px solid var(--border-color);
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        margin-bottom: 10px;
    }

    .action-button:hover {
        background: #e9ecef;
        border-color: var(--primary);
    }

    .action-button .title {
        font-weight: 600;
        margin-bottom: 5px;
    }

    .action-button .desc {
        font-size: 10px;
        color: #666;
    }
</style>

<div class="settings-grid">
    <!-- Database Optimization -->
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">Database Optimization</span>
        </div>
        <div class="panel-body">
            @php
                $dbInfo = [
                    'driver' => config('database.default'),
                    'version' => '',
                    'size' => 0,
                ];
                
                try {
                    $dbInfo['version'] = \DB::selectOne('SELECT VERSION() as version')->version ?? 'N/A';
                    
                    $tables = \DB::select('SHOW TABLE STATUS');
                    $totalSize = 0;
                    $tableCount = count($tables);
                    foreach ($tables as $table) {
                        $totalSize += ($table->Data_length + $table->Index_length);
                    }
                    $dbInfo['size'] = $totalSize;
                } catch (\Exception $e) {
                    $dbInfo['version'] = 'Error';
                }
            @endphp

            <div class="status-item">
                <span class="label">Database Driver</span>
                <span class="value">{{ strtoupper($dbInfo['driver']) }}</span>
            </div>
            
            <div class="status-item">
                <span class="label">Database Version</span>
                <span class="value">{{ $dbInfo['version'] }}</span>
            </div>

            <div class="status-item">
                <span class="label">Database Size</span>
                <span class="value">{{ number_format($dbInfo['size'] / 1024 / 1024, 2) }} MB</span>
            </div>

            <div class="status-item">
                <span class="label">Table Count</span>
                <span class="value">{{ $tableCount ?? 0 }}</span>
            </div>

            <hr style="margin: 15px 0;">

            <form action="{{ route('admin.settings.optimize-db') }}" method="POST">
                @csrf
                <div class="action-button" onclick="this.closest('form').submit()">
                    <div class="title">🔧 Optimize Tables</div>
                    <div class="desc">Run OPTIMIZE TABLE on all database tables</div>
                </div>
            </form>

            <form action="{{ route('admin.settings.clear-cache') }}" method="POST">
                @csrf
                <div class="action-button" onclick="this.closest('form').submit()">
                    <div class="title">🗑️ Clear All Cache</div>
                    <div class="desc">Clear application, route, config, and view cache</div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tracker Settings -->
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">Tracker & Logging</span>
        </div>
        <div class="panel-body">
            <form action="{{ route('admin.settings.tracker') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="tracker_enabled" value="1" 
                               {{ config('app.tracker_enabled', false) ? 'checked' : '' }}>
                        Enable Visitor Tracking
                    </label>
                    <small style="display: block; color: #999; margin-top: 5px;">
                        Track page views and visitor information
                    </small>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="log_admin_actions" value="1"
                               {{ config('app.log_admin_actions', true) ? 'checked' : '' }}>
                        Log Admin Actions
                    </label>
                    <small style="display: block; color: #999; margin-top: 5px;">
                        Record all admin panel activities
                    </small>
                </div>

                <div class="form-group">
                    <label>Log Retention (Days)</label>
                    <input type="number" name="log_retention_days" class="form-control" 
                           value="{{ config('app.log_retention_days', 30) }}" min="1" max="365">
                </div>

                <div class="form-group">
                    <label>Exclude IPs from Tracking</label>
                    <textarea name="tracker_exclude_ips" class="form-control" rows="3" 
                              placeholder="One IP per line">{{ config('app.tracker_exclude_ips', '') }}</textarea>
                </div>

                <button type="submit" class="btn btn-success">Save Tracker Settings</button>
            </form>
        </div>
    </div>

    <!-- System Maintenance -->
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">System Maintenance</span>
        </div>
        <div class="panel-body">
            @php
                $logSize = 0;
                $logPath = storage_path('logs');
                if (is_dir($logPath)) {
                    foreach (glob($logPath . '/*.log') as $file) {
                        $logSize += filesize($file);
                    }
                }
            @endphp

            <div class="status-item">
                <span class="label">Log Files Size</span>
                <span class="value {{ $logSize > 50*1024*1024 ? 'danger' : ($logSize > 10*1024*1024 ? 'warning' : 'good') }}">
                    {{ number_format($logSize / 1024 / 1024, 2) }} MB
                </span>
            </div>

            <div class="status-item">
                <span class="label">Session Driver</span>
                <span class="value">{{ config('session.driver') }}</span>
            </div>

            <div class="status-item">
                <span class="label">Cache Driver</span>
                <span class="value">{{ config('cache.default') }}</span>
            </div>

            <hr style="margin: 15px 0;">

            <form action="{{ route('admin.settings.clear-logs') }}" method="POST">
                @csrf
                <div class="action-button" onclick="if(confirm('Clear all log files?')) this.closest('form').submit()">
                    <div class="title">📄 Clear Log Files</div>
                    <div class="desc">Delete all log files in storage/logs</div>
                </div>
            </form>

            <form action="{{ route('admin.settings.clear-sessions') }}" method="POST">
                @csrf
                <div class="action-button" onclick="if(confirm('Clear all sessions? Users will be logged out.')) this.closest('form').submit()">
                    <div class="title">🔑 Clear Sessions</div>
                    <div class="desc">Remove all session files (users will be logged out)</div>
                </div>
            </form>
        </div>
    </div>

    <!-- Backup -->
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">Backup</span>
        </div>
        <div class="panel-body">
            <form action="{{ route('admin.settings.backup-db') }}" method="POST">
                @csrf
                <div class="action-button" onclick="this.closest('form').submit()">
                    <div class="title">💾 Backup Database</div>
                    <div class="desc">Create SQL dump of all tables</div>
                </div>
            </form>

            <p style="font-size: 10px; color: #999; margin-top: 15px;">
                Note: For full backups including files, use command line tools or your hosting backup system.
            </p>
        </div>
    </div>
</div>

@if(session('success'))
    <script>alert('{{ session('success') }}');</script>
@endif
@if(session('error'))
    <script>alert('Error: {{ session('error') }}');</script>
@endif
@endsection
