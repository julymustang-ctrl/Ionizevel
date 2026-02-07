@extends('layouts.admin')

@section('title', 'Modules')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span>›</span>
        <span>Modules</span>
    </div>
@endsection

@section('content')
<style>
    .modules-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }

    .module-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 20px;
        transition: all 0.2s;
    }

    .module-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .module-card.disabled {
        opacity: 0.6;
    }

    .module-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .module-name {
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .module-status {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 600;
    }

    .module-status.enabled {
        background: #d4edda;
        color: #155724;
    }

    .module-status.disabled {
        background: #f8d7da;
        color: #721c24;
    }

    .module-description {
        font-size: 12px;
        color: #666;
        margin-bottom: 15px;
        min-height: 36px;
    }

    .module-meta {
        font-size: 11px;
        color: #999;
        border-top: 1px solid #eee;
        padding-top: 10px;
    }

    .module-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .toggle-btn {
        flex: 1;
    }

    .empty-state {
        text-align: center;
        padding: 60px;
        color: #999;
    }

    .create-module-btn {
        margin-bottom: 20px;
    }
</style>

<div class="panel" style="margin-bottom: 20px;">
    <div class="panel-body" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <strong>Laravel Modules</strong>
            <span style="color: #666; font-size: 11px; margin-left: 10px;">
                nwidart/laravel-modules v12
            </span>
        </div>
        <div>
            <code style="font-size: 10px; background: #f5f5f5; padding: 5px 10px;">
                php artisan module:make ModuleName
            </code>
        </div>
    </div>
</div>

@if(count($modules) > 0)
    <div class="modules-grid">
        @foreach($modules as $module)
            <div class="module-card {{ $module['enabled'] ? '' : 'disabled' }}">
                <div class="module-header">
                    <div class="module-name">{{ $module['name'] }}</div>
                    <span class="module-status {{ $module['enabled'] ? 'enabled' : 'disabled' }}">
                        {{ $module['enabled'] ? 'Enabled' : 'Disabled' }}
                    </span>
                </div>
                
                <div class="module-description">
                    {{ $module['description'] ?: 'No description available.' }}
                </div>

                <div class="module-meta">
                    📁 {{ str_replace('\\', '/', $module['path']) }}
                </div>

                <div class="module-actions">
                    @if($module['enabled'])
                        <form action="{{ route('admin.modules.disable', $module['name']) }}" method="POST" class="toggle-btn">
                            @csrf
                            <button type="submit" class="btn btn-danger" style="width: 100%;">
                                Disable
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.modules.enable', $module['name']) }}" method="POST" class="toggle-btn">
                            @csrf
                            <button type="submit" class="btn btn-success" style="width: 100%;">
                                Enable
                            </button>
                        </form>
                    @endif
                    
                    <a href="{{ route('admin.modules.show', $module['name']) }}" class="btn">
                        Settings
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="empty-state">
        <p style="font-size: 48px; margin-bottom: 15px;">📦</p>
        <p style="font-size: 14px; margin-bottom: 5px;">No modules installed yet.</p>
        <p style="font-size: 12px;">Run <code>php artisan module:make ModuleName</code> to create a new module.</p>
    </div>
@endif

@if(session('success'))
    <script>window.toast.success('{{ session('success') }}');</script>
@endif
@if(session('error'))
    <script>window.toast.error('{{ session('error') }}');</script>
@endif
@endsection
