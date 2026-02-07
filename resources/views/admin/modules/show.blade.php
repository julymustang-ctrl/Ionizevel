@extends('layouts.admin')

@section('title', 'Module: ' . $moduleInfo['name'])

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span>›</span>
        <a href="{{ route('admin.modules.index') }}">Modules</a>
        <span>›</span>
        <span>{{ $moduleInfo['name'] }}</span>
    </div>
@endsection

@section('content')
<style>
    .module-detail-grid {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 20px;
    }

    .info-table {
        width: 100%;
        font-size: 12px;
    }

    .info-table td {
        padding: 10px;
        border-bottom: 1px solid #eee;
    }

    .info-table td:first-child {
        width: 140px;
        color: #666;
        font-weight: 500;
    }

    .file-list {
        list-style: none;
        padding: 0;
        margin: 0;
        font-size: 11px;
    }

    .file-list li {
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .file-list li:last-child {
        border-bottom: none;
    }

    .file-icon {
        opacity: 0.6;
    }
</style>

<div class="module-detail-grid">
    <div>
        <!-- Info Panel -->
        <div class="panel">
            <div class="panel-header">
                <span class="panel-title">Module Information</span>
            </div>
            <div class="panel-body">
                <table class="info-table">
                    <tr>
                        <td>Name</td>
                        <td><strong>{{ $moduleInfo['name'] }}</strong></td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>
                            @if($moduleInfo['enabled'])
                                <span style="color: #28a745;">✓ Enabled</span>
                            @else
                                <span style="color: #dc3545;">✕ Disabled</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Version</td>
                        <td>{{ $moduleInfo['version'] }}</td>
                    </tr>
                    <tr>
                        <td>Description</td>
                        <td>{{ $moduleInfo['description'] ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td>Path</td>
                        <td><code style="font-size: 10px;">{{ str_replace('\\', '/', $moduleInfo['path']) }}</code></td>
                    </tr>
                    <tr>
                        <td>Has Routes</td>
                        <td>{{ $hasRoutes ? 'Yes' : 'No' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Controllers -->
        <div class="panel" style="margin-top: 20px;">
            <div class="panel-header">
                <span class="panel-title">Controllers ({{ count($controllers) }})</span>
            </div>
            <div class="panel-body">
                @if(count($controllers) > 0)
                    <ul class="file-list">
                        @foreach($controllers as $controller)
                            <li>
                                <span class="file-icon">📄</span>
                                {{ basename($controller) }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p style="color: #999; font-size: 12px;">No controllers found.</p>
                @endif
            </div>
        </div>

        <!-- Migrations -->
        <div class="panel" style="margin-top: 20px;">
            <div class="panel-header">
                <span class="panel-title">Migrations ({{ count($migrations) }})</span>
            </div>
            <div class="panel-body">
                @if(count($migrations) > 0)
                    <ul class="file-list">
                        @foreach($migrations as $migration)
                            <li>
                                <span class="file-icon">📋</span>
                                {{ basename($migration) }}
                            </li>
                        @endforeach
                    </ul>
                    
                    <form action="{{ route('admin.modules.migrate', $moduleInfo['name']) }}" method="POST" style="margin-top: 15px;">
                        @csrf
                        <button type="submit" class="btn btn-primary">Run Migrations</button>
                    </form>
                @else
                    <p style="color: #999; font-size: 12px;">No migrations found.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Actions Sidebar -->
    <div>
        <div class="panel">
            <div class="panel-header">
                <span class="panel-title">Actions</span>
            </div>
            <div class="panel-body">
                @if($moduleInfo['enabled'])
                    <form action="{{ route('admin.modules.disable', $moduleInfo['name']) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger" style="width: 100%; margin-bottom: 10px;">
                            Disable Module
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.modules.enable', $moduleInfo['name']) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success" style="width: 100%; margin-bottom: 10px;">
                            Enable Module
                        </button>
                    </form>
                @endif

                <a href="{{ route('admin.modules.index') }}" class="btn" style="width: 100%;">
                    ← Back to Modules
                </a>
            </div>
        </div>

        <!-- Usage -->
        <div class="panel" style="margin-top: 20px;">
            <div class="panel-header">
                <span class="panel-title">Usage in Pages</span>
            </div>
            <div class="panel-body" style="font-size: 11px;">
                <p style="margin-bottom: 10px;">To use this module on a page:</p>
                <ol style="padding-left: 20px; margin: 0;">
                    <li style="margin-bottom: 5px;">Edit a page</li>
                    <li style="margin-bottom: 5px;">Set <strong>Link Type</strong> to "Module"</li>
                    <li style="margin-bottom: 5px;">Set <strong>Used by Module</strong> to:</li>
                </ol>
                <code style="display: block; margin-top: 10px; padding: 10px; background: #f5f5f5; font-size: 10px;">
                    {{ $moduleInfo['name'] }}
                </code>
                <p style="margin-top: 10px; color: #999;">
                    Or with specific controller:<br>
                    <code style="font-size: 10px;">{{ $moduleInfo['name'] }}@Controller@action</code>
                </p>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <script>window.toast.success('{{ session('success') }}');</script>
@endif
@if(session('error'))
    <script>window.toast.error('{{ session('error') }}');</script>
@endif
@endsection
