@extends('layouts.admin')

@section('title', 'Help & Documentation')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span>›</span>
        <span>Help</span>
    </div>
@endsection

@section('content')
<style>
    .help-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }

    .help-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        padding: 20px;
    }

    .help-card h3 {
        font-size: 14px;
        margin-bottom: 15px;
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .help-card h3 .icon {
        font-size: 20px;
    }

    .help-card ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .help-card li {
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
        font-size: 12px;
    }

    .help-card li:last-child {
        border-bottom: none;
    }

    .help-card a {
        color: #333;
    }

    .help-card a:hover {
        color: var(--primary);
    }

    .shortcut-table {
        width: 100%;
        font-size: 12px;
    }

    .shortcut-table td {
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .shortcut-table td:first-child {
        width: 120px;
    }

    .shortcut-table kbd {
        display: inline-block;
        padding: 2px 6px;
        background: #f5f5f5;
        border: 1px solid #ccc;
        border-radius: 3px;
        font-family: monospace;
        font-size: 11px;
    }

    .version-info {
        background: var(--bg-gray-light);
        padding: 15px;
        border-radius: 6px;
        margin-top: 20px;
    }

    .version-info h4 {
        font-size: 12px;
        margin-bottom: 10px;
    }

    .version-info p {
        font-size: 11px;
        color: #666;
        margin: 5px 0;
    }
</style>

<div class="help-grid">
    <!-- Keyboard Shortcuts -->
    <div class="help-card">
        <h3><span class="icon">⌨️</span> Keyboard Shortcuts</h3>
        <table class="shortcut-table">
            <tr>
                <td><kbd>Ctrl</kbd> + <kbd>S</kbd></td>
                <td>Save current form</td>
            </tr>
            <tr>
                <td><kbd>Ctrl</kbd> + <kbd>N</kbd></td>
                <td>Create new item</td>
            </tr>
            <tr>
                <td><kbd>Esc</kbd></td>
                <td>Close modal/dialog</td>
            </tr>
            <tr>
                <td><kbd>Ctrl</kbd> + <kbd>/</kbd></td>
                <td>Show shortcuts help</td>
            </tr>
        </table>
    </div>

    <!-- Quick Links -->
    <div class="help-card">
        <h3><span class="icon">🔗</span> Quick Links</h3>
        <ul>
            <li><a href="{{ route('admin.pages.create') }}">Create New Page</a></li>
            <li><a href="{{ route('admin.articles.create') }}">Create New Article</a></li>
            <li><a href="{{ route('admin.media.index') }}">Media Manager</a></li>
            <li><a href="{{ route('admin.settings.index') }}">Website Settings</a></li>
            <li><a href="{{ route('admin.settings.advanced') }}">Advanced Settings</a></li>
        </ul>
    </div>

    <!-- Content Management -->
    <div class="help-card">
        <h3><span class="icon">📄</span> Content Management</h3>
        <ul>
            <li><strong>Pages:</strong> Create hierarchical site structure with drag-drop ordering</li>
            <li><strong>Articles:</strong> Add content to pages with categories and tags</li>
            <li><strong>Categories:</strong> Organize content with nested categories</li>
            <li><strong>Media:</strong> Upload and manage images, documents, videos</li>
            <li><strong>Menus:</strong> Create navigation menus for your site</li>
        </ul>
    </div>

    <!-- Tag Library -->
    <div class="help-card">
        <h3><span class="icon">🏷️</span> Tag Library (Blade DSL)</h3>
        <ul>
            <li><code>@ion_page('slug')</code> - Get page by slug</li>
            <li><code>@ion_articles($page)</code> - List page articles</li>
            <li><code>@ion_navigation('main')</code> - Render navigation</li>
            <li><code>@ion_medias($item)</code> - Get item media</li>
            <li><code>@ion_categories</code> - List all categories</li>
            <li><code>@ion_languages</code> - List all languages</li>
            <li><code>@ion_setting('key')</code> - Get setting value</li>
            <li><code>@ion_media_download($media)</code> - Secure download link</li>
        </ul>
    </div>

    <!-- Multilingual -->
    <div class="help-card">
        <h3><span class="icon">🌐</span> Multilingual Content</h3>
        <ul>
            <li>All content supports multiple languages</li>
            <li>Language tabs appear in page/article editors</li>
            <li>Each language has its own URL slug</li>
            <li>Translations UI for interface strings</li>
            <li>Import translations from Laravel lang files</li>
        </ul>
    </div>

    <!-- Permissions -->
    <div class="help-card">
        <h3><span class="icon">🔒</span> Permissions & Roles</h3>
        <ul>
            <li><strong>Super Admin:</strong> Full system access (level 10000)</li>
            <li><strong>Admin:</strong> Site management access</li>
            <li><strong>Editor:</strong> Content editing access</li>
            <li><strong>User:</strong> Limited access</li>
            <li>Page-level ACL for frontend access control</li>
            <li>Permission matrix for granular control</li>
        </ul>
    </div>
</div>

<div class="version-info">
    <h4>System Information</h4>
    <p><strong>Ionizevel CMS:</strong> 1.0.0 (Laravel Port)</p>
    <p><strong>Based on:</strong> Ionize CMS Philosophy</p>
    <p><strong>Laravel:</strong> {{ app()->version() }}</p>
    <p><strong>PHP:</strong> {{ phpversion() }}</p>
    <p><strong>License:</strong> MIT</p>
</div>
@endsection
