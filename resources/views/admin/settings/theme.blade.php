@extends('layouts.admin')

@section('title', 'Theme Manager')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span>›</span>
        <span>Settings</span>
        <span>›</span>
        <span>Theme</span>
    </div>
@endsection

@section('actions')
    <button type="submit" form="themeForm" class="btn btn-success">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        Save
    </button>
@endsection

@section('content')
<style>
    .theme-manager-layout {
        display: grid;
        grid-template-columns: 250px 1fr;
        gap: 0;
        min-height: 600px;
    }

    /* Theme Selector Sidebar */
    .theme-sidebar {
        background: var(--sidebar-bg);
        border-right: 1px solid rgba(255,255,255,0.1);
        padding: 15px;
    }

    .theme-sidebar h3 {
        color: var(--text-light);
        font-size: 11px;
        text-transform: uppercase;
        margin-bottom: 15px;
        letter-spacing: 0.5px;
    }

    .theme-list {
        list-style: none;
    }

    .theme-list-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        margin-bottom: 5px;
        background: rgba(255,255,255,0.05);
        color: var(--text-light);
        cursor: pointer;
        font-size: 12px;
        border-left: 3px solid transparent;
        transition: all 0.15s;
    }

    .theme-list-item:hover {
        background: rgba(255,255,255,0.1);
    }

    .theme-list-item.active {
        background: rgba(9, 142, 209, 0.2);
        border-left-color: var(--primary);
        color: white;
    }

    .theme-list-item svg {
        width: 18px;
        height: 18px;
        opacity: 0.7;
    }

    .theme-list-item .name {
        flex: 1;
    }

    .theme-list-item .badge {
        font-size: 9px;
        padding: 2px 6px;
        background: var(--success);
        color: white;
        border-radius: 3px;
    }

    /* Main Content - File Browser */
    .theme-content {
        background: white;
    }

    .theme-content-header {
        padding: 15px 20px;
        background: var(--bg-gray-light);
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .theme-content-header h3 {
        margin: 0;
        font-size: 14px;
        color: #36607D;
    }

    .theme-path {
        font-size: 11px;
        color: #666;
    }

    /* View Files Table */
    .view-files-table {
        width: 100%;
        border-collapse: collapse;
    }

    .view-files-table th {
        text-align: left;
        padding: 12px 15px;
        font-size: 11px;
        font-weight: 600;
        color: #36607D;
        background: var(--bg-gray-light);
        border-bottom: 2px solid var(--border-color);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .view-files-table td {
        padding: 10px 15px;
        font-size: 11px;
        border-bottom: 1px solid #eee;
        vertical-align: middle;
    }

    .view-files-table tbody tr:hover {
        background: #f8f9fa;
    }

    .file-icon {
        width: 20px;
        height: 20px;
        margin-right: 8px;
        vertical-align: middle;
    }

    .file-icon.blade { color: #f05340; }
    .file-icon.folder { color: #f0ad4e; }
    .file-icon.css { color: #264de4; }
    .file-icon.js { color: #f7df1e; }

    .file-name {
        font-family: monospace;
        color: #333;
    }

    .folder-path {
        color: #888;
        font-family: monospace;
        font-size: 10px;
    }

    .logical-name-input {
        width: 150px;
        padding: 5px 8px;
        border: 1px solid #ccc;
        font-size: 11px;
        font-family: monospace;
    }

    .logical-name-input:focus {
        border-color: var(--primary);
        outline: none;
        background: #f5f8ff;
    }

    .file-type {
        display: inline-block;
        padding: 3px 8px;
        font-size: 10px;
        border-radius: 3px;
        text-transform: uppercase;
    }

    .file-type.page { background: #e3f2fd; color: #1976d2; }
    .file-type.article { background: #e8f5e9; color: #388e3c; }
    .file-type.partial { background: #fff3e0; color: #f57c00; }
    .file-type.layout { background: #f3e5f5; color: #7b1fa2; }
    .file-type.element { background: #fce4ec; color: #c2185b; }

    .type-select {
        padding: 4px 8px;
        font-size: 10px;
        border: 1px solid #ccc;
    }

    /* Folder Row */
    .folder-row td {
        background: #fafafa;
        font-weight: 500;
    }

    .folder-row .file-name {
        color: #666;
    }

    /* Actions */
    .file-actions {
        display: flex;
        gap: 5px;
    }

    .file-actions .btn {
        padding: 3px 8px;
        font-size: 10px;
    }

    /* Theme Settings Panel */
    .theme-settings {
        padding: 20px;
        border-top: 1px solid var(--border-color);
        background: #fafafa;
    }

    .theme-settings h4 {
        font-size: 12px;
        color: #36607D;
        margin-bottom: 15px;
    }

    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
    }
</style>

<form id="themeForm" action="{{ route('admin.theme.update') }}" method="POST">
    @csrf

    <div class="theme-manager-layout">
        <!-- Theme Selector Sidebar -->
        <div class="theme-sidebar">
            <h3>Available Themes</h3>
            <ul class="theme-list">
                @foreach($themes as $theme)
                    <li class="theme-list-item {{ $theme['name'] === $activeTheme ? 'active' : '' }}"
                        onclick="selectTheme('{{ $theme['name'] }}')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                        </svg>
                        <span class="name">{{ $theme['title'] }}</span>
                        @if($theme['name'] === $activeTheme)
                            <span class="badge">Active</span>
                        @endif
                    </li>
                @endforeach
            </ul>

            <input type="hidden" name="theme" id="selectedTheme" value="{{ $activeTheme }}">

            <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.1);">
                <button type="button" class="btn" style="width: 100%;" onclick="createNewTheme()">
                    + Create New Theme
                </button>
            </div>
        </div>

        <!-- View Files Browser -->
        <div class="theme-content">
            <div class="theme-content-header">
                <div>
                    <h3>Theme Views</h3>
                    <div class="theme-path">
                        resources/views/themes/<strong>{{ $activeTheme }}</strong>/
                    </div>
                </div>
                <div>
                    <button type="button" class="btn" onclick="refreshFiles()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Refresh
                    </button>
                </div>
            </div>

            <table class="view-files-table">
                <thead>
                    <tr>
                        <th style="width: 30%;">File</th>
                        <th style="width: 20%;">Folder</th>
                        <th style="width: 25%;">Logical Name</th>
                        <th style="width: 15%;">Type</th>
                        <th style="width: 10%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($viewFiles as $file)
                        @if($file['is_folder'])
                            <tr class="folder-row">
                                <td colspan="5">
                                    <svg class="file-icon folder" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                    </svg>
                                    <span class="file-name">{{ $file['name'] }}/</span>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td>
                                    <svg class="file-icon blade" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="file-name">{{ $file['name'] }}</span>
                                </td>
                                <td>
                                    <span class="folder-path">{{ $file['folder'] ?: '/' }}</span>
                                </td>
                                <td>
                                    <input type="text" 
                                           name="logical_names[{{ $file['path'] }}]" 
                                           class="logical-name-input"
                                           value="{{ $file['logical_name'] }}"
                                           placeholder="e.g. page_default">
                                </td>
                                <td>
                                    <select name="types[{{ $file['path'] }}]" class="type-select">
                                        <option value="page" {{ $file['type'] === 'page' ? 'selected' : '' }}>Page</option>
                                        <option value="article" {{ $file['type'] === 'article' ? 'selected' : '' }}>Article</option>
                                        <option value="partial" {{ $file['type'] === 'partial' ? 'selected' : '' }}>Partial</option>
                                        <option value="layout" {{ $file['type'] === 'layout' ? 'selected' : '' }}>Layout</option>
                                        <option value="element" {{ $file['type'] === 'element' ? 'selected' : '' }}>Element</option>
                                    </select>
                                </td>
                                <td>
                                    <div class="file-actions">
                                        <a href="{{ route('admin.theme.edit-file', ['file' => base64_encode($file['path'])]) }}" 
                                           class="btn btn-sm" title="Edit">
                                            ✎
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>

            <!-- Theme Settings -->
            <div class="theme-settings">
                <h4>Theme Settings</h4>
                <div class="settings-grid">
                    <div class="form-group">
                        <label>Default Page View</label>
                        <select name="default_page_view" class="form-control">
                            <option value="">-- Select --</option>
                            @foreach($viewFiles as $file)
                                @if(!$file['is_folder'] && $file['type'] === 'page')
                                    <option value="{{ $file['logical_name'] }}" 
                                            {{ ($settings['default_page_view']->content ?? '') === $file['logical_name'] ? 'selected' : '' }}>
                                        {{ $file['logical_name'] }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Default Article View</label>
                        <select name="default_article_view" class="form-control">
                            <option value="">-- Select --</option>
                            @foreach($viewFiles as $file)
                                @if(!$file['is_folder'] && $file['type'] === 'article')
                                    <option value="{{ $file['logical_name'] }}"
                                            {{ ($settings['default_article_view']->content ?? '') === $file['logical_name'] ? 'selected' : '' }}>
                                        {{ $file['logical_name'] }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    function selectTheme(themeName) {
        document.getElementById('selectedTheme').value = themeName;
        // Reload page with selected theme
        window.location.href = '{{ route("admin.theme.index") }}?theme=' + themeName;
    }

    function refreshFiles() {
        window.location.reload();
    }

    function createNewTheme() {
        const name = prompt('Enter new theme name (lowercase, no spaces):');
        if (name) {
            window.location.href = '{{ route("admin.theme.create") }}?name=' + name;
        }
    }
</script>
@endpush
@endsection
