@extends('layouts.admin')

@section('title', 'Website Settings')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span>›</span>
        <span>Settings</span>
        <span>›</span>
        <span>Website Settings</span>
    </div>
@endsection

@section('actions')
    <button type="submit" form="settingsForm" class="btn btn-success">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        Save Settings
    </button>
@endsection

@section('content')
<style>
    .settings-layout {
        display: flex;
        gap: 0;
    }

    .settings-tabs {
        width: 200px;
        background: white;
        border: 1px solid var(--border-color);
        border-right: none;
    }

    .settings-tab {
        display: block;
        padding: 12px 15px;
        font-size: 11px;
        color: #666;
        cursor: pointer;
        border-bottom: 1px solid var(--border-color);
        transition: all 0.15s;
    }

    .settings-tab:hover {
        background: var(--bg-gray-light);
        color: var(--primary);
    }

    .settings-tab.active {
        background: var(--primary);
        color: white;
        border-right: 3px solid var(--primary-dark);
    }

    .settings-tab svg {
        width: 16px;
        height: 16px;
        margin-right: 8px;
        vertical-align: middle;
    }

    .settings-content {
        flex: 1;
        background: white;
        border: 1px solid var(--border-color);
    }

    .settings-panel {
        display: none;
        padding: 20px;
    }

    .settings-panel.active {
        display: block;
    }

    .settings-panel h3 {
        font-size: 14px;
        color: #36607D;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border-color);
    }

    .settings-row {
        display: grid;
        grid-template-columns: 200px 1fr;
        gap: 15px;
        margin-bottom: 15px;
        align-items: center;
    }

    .settings-row label {
        font-size: 11px;
        color: #333;
        text-align: right;
    }

    .settings-row .form-control {
        max-width: 400px;
    }

    .settings-row .help {
        font-size: 10px;
        color: #999;
        margin-top: 3px;
    }

    .settings-footer {
        padding: 15px;
        background: var(--bg-gray-light);
        border-top: 1px solid var(--border-color);
        text-align: right;
    }
</style>

<form id="settingsForm" action="{{ route('admin.settings.update') }}" method="POST">
    @csrf

    <div class="settings-layout">
        <!-- Tabs -->
        <div class="settings-tabs">
            <div class="settings-tab active" data-panel="website">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                </svg>
                Website
            </div>
            <div class="settings-tab" data-panel="seo">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                SEO
            </div>
            <div class="settings-tab" data-panel="files">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
                Files
            </div>
            <div class="settings-tab" data-panel="cache">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Cache
            </div>
            <div class="settings-tab" data-panel="editor">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editor
            </div>
            <div class="settings-tab" data-panel="maintenance">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Maintenance
            </div>
            <div class="settings-tab" data-panel="analytics">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Analytics
            </div>
        </div>

        <!-- Panels -->
        <div class="settings-content">
            <!-- Website Panel -->
            <div class="settings-panel active" id="panel-website">
                <h3>Website Settings</h3>

                <div class="settings-row">
                    <label>Site Name</label>
                    <div>
                        <input type="text" name="site_name" class="form-control"
                               value="{{ $settings['site_name']->content ?? 'Ionizevel CMS' }}">
                        <div class="help">The name of your website</div>
                    </div>
                </div>

                <div class="settings-row">
                    <label>Baseline</label>
                    <div>
                        <input type="text" name="baseline" class="form-control"
                               value="{{ $settings['baseline']->content ?? '' }}">
                        <div class="help">Site tagline or slogan</div>
                    </div>
                </div>

                <div class="settings-row">
                    <label>Admin Email</label>
                    <div>
                        <input type="email" name="admin_email" class="form-control"
                               value="{{ $settings['admin_email']->content ?? '' }}">
                        <div class="help">Administrator email address</div>
                    </div>
                </div>

                <div class="settings-row">
                    <label>Contact Email</label>
                    <div>
                        <input type="email" name="website_email" class="form-control"
                               value="{{ $settings['website_email']->content ?? '' }}">
                        <div class="help">Public contact email</div>
                    </div>
                </div>

                <div class="settings-row">
                    <label>Default Language</label>
                    <div>
                        <select name="default_lang" class="form-control">
                            @foreach($languages as $lang)
                                <option value="{{ $lang->lang }}"
                                        {{ ($settings['default_lang']->content ?? 'tr') == $lang->lang ? 'selected' : '' }}>
                                    {{ $lang->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="settings-row">
                    <label>Default Admin Language</label>
                    <div>
                        <select name="default_admin_lang" class="form-control">
                            @foreach($languages as $lang)
                                <option value="{{ $lang->lang }}"
                                        {{ ($settings['default_admin_lang']->content ?? 'tr') == $lang->lang ? 'selected' : '' }}>
                                    {{ $lang->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- SEO Panel -->
            <div class="settings-panel" id="panel-seo">
                <h3>SEO Settings</h3>

                @foreach($languages as $lang)
                    <h4 style="font-size: 12px; color: var(--primary); margin: 15px 0 10px;">{{ strtoupper($lang->lang) }}</h4>

                    <div class="settings-row">
                        <label>Meta Title ({{ strtoupper($lang->lang) }})</label>
                        <div>
                            <input type="text" name="meta_title_{{ $lang->lang }}" class="form-control"
                                   value="{{ $settings["meta_title_{$lang->lang}"]->content ?? '' }}">
                        </div>
                    </div>

                    <div class="settings-row">
                        <label>Meta Description ({{ strtoupper($lang->lang) }})</label>
                        <div>
                            <textarea name="meta_description_{{ $lang->lang }}" class="form-control" rows="2">{{ $settings["meta_description_{$lang->lang}"]->content ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="settings-row">
                        <label>Meta Keywords ({{ strtoupper($lang->lang) }})</label>
                        <div>
                            <input type="text" name="meta_keywords_{{ $lang->lang }}" class="form-control"
                                   value="{{ $settings["meta_keywords_{$lang->lang}"]->content ?? '' }}">
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Files Panel -->
            <div class="settings-panel" id="panel-files">
                <h3>Files & Media Settings</h3>

                <div class="settings-row">
                    <label>Files Path</label>
                    <div>
                        <input type="text" name="files_path" class="form-control"
                               value="{{ $settings['files_path']->content ?? 'files' }}">
                        <div class="help">Folder for uploaded files</div>
                    </div>
                </div>

                <div class="settings-row">
                    <label>Media Path</label>
                    <div>
                        <input type="text" name="media_path" class="form-control"
                               value="{{ $settings['media_path']->content ?? 'media' }}">
                        <div class="help">Folder for media files</div>
                    </div>
                </div>

                <div class="settings-row">
                    <label>Thumbnail Size</label>
                    <div>
                        <input type="number" name="media_thumb_size" class="form-control" style="width: 100px;"
                               value="{{ $settings['media_thumb_size']->content ?? '120' }}">
                        <div class="help">Thumbnail width in pixels</div>
                    </div>
                </div>

                <div class="settings-row">
                    <label>Max Upload Size</label>
                    <div>
                        <input type="text" name="max_upload_size" class="form-control" style="width: 100px;"
                               value="{{ $settings['max_upload_size']->content ?? '8M' }}">
                        <div class="help">Maximum file upload size (e.g., 8M, 16M)</div>
                    </div>
                </div>
            </div>

            <!-- Cache Panel -->
            <div class="settings-panel" id="panel-cache">
                <h3>Cache Settings</h3>

                <div class="settings-row">
                    <label>Enable Cache</label>
                    <div>
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" name="cache" value="1"
                                   {{ ($settings['cache']->content ?? '0') == '1' ? 'checked' : '' }}>
                            Enable page caching
                        </label>
                    </div>
                </div>

                <div class="settings-row">
                    <label>Cache Time</label>
                    <div>
                        <input type="number" name="cache_time" class="form-control" style="width: 100px;"
                               value="{{ $settings['cache_time']->content ?? '150' }}">
                        <div class="help">Cache duration in minutes</div>
                    </div>
                </div>

                <div class="settings-row">
                    <label>Clear Cache</label>
                    <div>
                        <button type="button" class="btn btn-info" onclick="clearCache()">Clear All Cache</button>
                    </div>
                </div>
            </div>

            <!-- Editor Panel -->
            <div class="settings-panel" id="panel-editor">
                <h3>Editor Settings</h3>

                <div class="settings-row">
                    <label>Text Editor</label>
                    <div>
                        <select name="texteditor" class="form-control">
                            <option value="tinymce" {{ ($settings['texteditor']->content ?? 'tinymce') == 'tinymce' ? 'selected' : '' }}>TinyMCE</option>
                            <option value="ckeditor" {{ ($settings['texteditor']->content ?? '') == 'ckeditor' ? 'selected' : '' }}>CKEditor</option>
                        </select>
                    </div>
                </div>

                <div class="settings-row">
                    <label>Editor Theme</label>
                    <div>
                        <select name="editor_theme" class="form-control">
                            <option value="silver">Silver (default)</option>
                            <option value="oxide">Oxide</option>
                            <option value="oxide-dark">Oxide Dark</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Maintenance Panel -->
            <div class="settings-panel" id="panel-maintenance">
                <h3>Maintenance Mode</h3>

                <div class="settings-row">
                    <label>Maintenance Mode</label>
                    <div>
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" name="maintenance" value="1"
                                   {{ ($settings['maintenance']->content ?? '0') == '1' ? 'checked' : '' }}>
                            Enable maintenance mode
                        </label>
                        <div class="help">When enabled, only admins can access the site</div>
                    </div>
                </div>

                <div class="settings-row">
                    <label>Maintenance Message</label>
                    <div>
                        <textarea name="maintenance_message" class="form-control" rows="3">{{ $settings['maintenance_message']->content ?? 'Site is under maintenance. Please check back later.' }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Analytics Panel -->
            <div class="settings-panel" id="panel-analytics">
                <h3>Analytics Settings</h3>

                <div class="settings-row">
                    <label>Google Analytics ID</label>
                    <div>
                        <input type="text" name="google_analytics" class="form-control"
                               value="{{ $settings['google_analytics']->content ?? '' }}"
                               placeholder="UA-XXXXX-X or G-XXXXXXXXXX">
                        <div class="help">Your Google Analytics tracking ID</div>
                    </div>
                </div>

                <div class="settings-row">
                    <label>Google Tag Manager</label>
                    <div>
                        <input type="text" name="google_tag_manager" class="form-control"
                               value="{{ $settings['google_tag_manager']->content ?? '' }}"
                               placeholder="GTM-XXXXXXX">
                        <div class="help">Your GTM container ID</div>
                    </div>
                </div>
            </div>

            <div class="settings-footer">
                <button type="submit" class="btn btn-success">Save Settings</button>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    // Tab switching
    document.querySelectorAll('.settings-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.settings-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.settings-panel').forEach(p => p.classList.remove('active'));

            this.classList.add('active');
            document.getElementById('panel-' + this.dataset.panel).classList.add('active');
        });
    });

    function clearCache() {
        if (confirm('Are you sure you want to clear all cache?')) {
            fetch('/admin/cache/clear', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                .then(response => response.json())
                .then(data => alert('Cache cleared successfully!'))
                .catch(error => alert('Error clearing cache'));
        }
    }
</script>
@endpush
@endsection
