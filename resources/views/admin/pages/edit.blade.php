@extends('layouts.admin')

@section('title', 'Sayfa Düzenle')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span>›</span>
        <a href="{{ route('admin.pages.index') }}">Pages</a>
        <span>›</span>
        <span>{{ $page->translations->first()->title ?? $page->name }}</span>
    </div>
@endsection

@section('actions')
    <button type="submit" form="pageForm" class="btn btn-success">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        Save
    </button>
@endsection

@section('content')
<style>
    .page-editor {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 15px;
    }

    .editor-main {
        background: white;
        border: 1px solid var(--border-color);
    }

    .editor-tabs {
        display: flex;
        background: var(--bg-gray-light);
        border-bottom: 1px solid var(--border-color);
    }

    .editor-tab {
        padding: 12px 20px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 500;
        color: #666;
        border-right: 1px solid var(--border-color);
        transition: all 0.15s;
    }

    .editor-tab:hover {
        background: white;
        color: var(--primary);
    }

    .editor-tab.active {
        background: white;
        color: var(--primary);
        border-bottom: 2px solid var(--primary);
        margin-bottom: -1px;
    }

    .editor-tab-content {
        display: none;
        padding: 15px;
    }

    .editor-tab-content.active {
        display: block;
    }

    /* Options Panel */
    .options-panel {
        background: white;
        border: 1px solid var(--border-color);
    }

    .options-header {
        padding: 10px 15px;
        background: var(--bg-gray-light);
        border-bottom: 1px solid var(--border-color);
        font-weight: 600;
        font-size: 12px;
        color: #36607D;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .options-header:hover {
        background: #eee;
    }

    .options-section {
        border-bottom: 1px solid var(--border-color);
    }

    .options-section-body {
        padding: 12px 15px;
        display: none;
    }

    .options-section.open .options-section-body {
        display: block;
    }

    .options-section .toggle {
        font-size: 10px;
    }

    /* Status buttons */
    .status-buttons {
        display: flex;
        gap: 8px;
        padding: 15px;
        background: var(--bg-gray-light);
        border-bottom: 1px solid var(--border-color);
    }

    /* Language content tabs */
    .lang-content-tabs {
        display: flex;
        gap: 2px;
        margin-bottom: 15px;
        background: var(--bg-gray-light);
        padding: 5px;
    }

    .lang-content-tab {
        padding: 8px 20px;
        cursor: pointer;
        font-size: 11px;
        font-weight: 500;
        background: white;
        border: 1px solid var(--border-color);
    }

    .lang-content-tab.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .lang-content-area {
        display: none;
    }

    .lang-content-area.active {
        display: block;
    }

    /* Media items */
    .media-drop-zone {
        border: 2px dashed #ccc;
        padding: 30px;
        text-align: center;
        background: #fafafa;
        margin-bottom: 15px;
    }

    .media-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 10px;
    }

    .media-list-item {
        border: 1px solid var(--border-color);
        padding: 5px;
        text-align: center;
        position: relative;
    }

    .media-list-item img {
        max-width: 100%;
        height: 60px;
        object-fit: cover;
    }

    .media-list-item .remove {
        position: absolute;
        top: 2px;
        right: 2px;
        background: var(--danger);
        color: white;
        border: none;
        width: 18px;
        height: 18px;
        font-size: 12px;
        cursor: pointer;
        line-height: 1;
    }

    /* Articles list */
    .articles-list {
        max-height: 300px;
        overflow-y: auto;
    }

    .article-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 10px;
        border: 1px solid var(--border-color);
        margin-bottom: 5px;
        background: white;
    }

    .article-item:hover {
        background: var(--bg-gray-light);
    }

    .article-item .title {
        font-weight: 500;
    }

    .article-item .view-select {
        width: 120px;
        font-size: 10px;
        padding: 3px;
    }

    .article-item .actions {
        display: flex;
        gap: 5px;
    }

    /* Checkbox group */
    .checkbox-group {
        margin: 8px 0;
    }

    .checkbox-group label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 11px;
        cursor: pointer;
    }

    .checkbox-group input[type="checkbox"] {
        width: 14px;
        height: 14px;
    }

    /* Date inputs */
    .date-input {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
    }

    .date-input label {
        width: 80px;
        font-size: 11px;
    }

    .date-input input {
        flex: 1;
        padding: 5px;
        border: 1px solid #bbb;
        font-size: 11px;
    }
</style>

<form id="pageForm" action="{{ route('admin.pages.update', $page->id_page) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="page-editor">
        <!-- Main Editor Area -->
        <div class="editor-main">
            <!-- Tabs -->
            <div class="editor-tabs">
                <div class="editor-tab active" data-tab="content">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="vertical-align: middle; margin-right: 5px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Content
                </div>
                <div class="editor-tab" data-tab="medias">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="vertical-align: middle; margin-right: 5px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Medias
                </div>
                <div class="editor-tab" data-tab="articles">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="vertical-align: middle; margin-right: 5px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    Articles
                </div>
            </div>

            <!-- Content Tab -->
            <div class="editor-tab-content active" id="tab-content">
                <!-- Language Tabs -->
                <div class="lang-content-tabs">
                    @foreach($languages as $index => $lang)
                        <div class="lang-content-tab {{ $index === 0 ? 'active' : '' }}" data-lang="{{ $lang->lang }}">
                            {{ strtoupper($lang->lang) }}
                        </div>
                    @endforeach
                </div>

                @foreach($languages as $index => $lang)
                    @php $translation = $page->translations->where('lang', $lang->lang)->first(); @endphp
                    <div class="lang-content-area {{ $index === 0 ? 'active' : '' }}" id="content-{{ $lang->lang }}">
                        <div class="form-group">
                            <label>Title ({{ strtoupper($lang->lang) }})</label>
                            <input type="text" name="title_{{ $lang->lang }}" class="form-control"
                                   value="{{ old("title_{$lang->lang}", $translation->title ?? '') }}"
                                   onkeyup="document.getElementById('url_{{ $lang->lang }}').value = generateSlug(this.value)">
                        </div>

                        <div class="form-group">
                            <label>Subtitle ({{ strtoupper($lang->lang) }})</label>
                            <input type="text" name="subtitle_{{ $lang->lang }}" class="form-control"
                                   value="{{ old("subtitle_{$lang->lang}", $translation->subtitle ?? '') }}">
                        </div>

                        <div class="form-group">
                            <label>URL ({{ strtoupper($lang->lang) }})</label>
                            <input type="text" name="url_{{ $lang->lang }}" id="url_{{ $lang->lang }}" class="form-control"
                                   value="{{ old("url_{$lang->lang}", $translation->url ?? '') }}">
                        </div>

                        <div class="checkbox-group">
                            <label>
                                <input type="checkbox" name="online_{{ $lang->lang }}" value="1"
                                       {{ ($translation->online ?? true) ? 'checked' : '' }}>
                                Online for this language
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Medias Tab -->
            <div class="editor-tab-content" id="tab-medias">
                <div class="media-drop-zone" id="mediaDropZone">
                    <p>Drag media here or click to select</p>
                    <button type="button" class="btn" onclick="openMediaPicker(addMediaToPage)">Select Media</button>
                </div>

                <h4 style="font-size: 12px; margin-bottom: 10px;">Attached Medias</h4>
                <div class="media-list" id="attachedMedias">
                    @foreach($page->media as $media)
                        <div class="media-list-item" data-id="{{ $media->id_media }}">
                            <input type="hidden" name="media_ids[]" value="{{ $media->id_media }}">
                            <img src="{{ asset('storage/' . $media->path) }}" alt="{{ $media->name }}">
                            <button type="button" class="remove" onclick="removeMedia(this)">&times;</button>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Articles Tab -->
            <div class="editor-tab-content" id="tab-articles">
                <div class="toolbar">
                    <div class="toolbar-left">
                        <strong>Linked Articles</strong>
                    </div>
                    <div class="toolbar-right">
                        <select id="articleSelector" class="form-control" style="width: 200px;">
                            <option value="">-- Add article --</option>
                            @foreach($articles as $article)
                                @if(!$page->articles->contains('id_article', $article->id_article))
                                    <option value="{{ $article->id_article }}" data-title="{{ $article->translations->first()->title ?? $article->name }}">
                                        {{ $article->translations->first()->title ?? $article->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-success" onclick="addArticle()">Add</button>
                    </div>
                </div>

                <div class="articles-list" id="articlesList">
                    @foreach($page->articles as $article)
                        @php $artTranslation = $article->translations->first(); @endphp
                        <div class="article-item" data-id="{{ $article->id_article }}">
                            <input type="hidden" name="articles[]" value="{{ $article->id_article }}">
                            <span class="title">{{ $artTranslation->title ?? $article->name }}</span>
                            <div class="actions">
                                <select name="article_view_{{ $article->id_article }}" class="view-select">
                                    <option value="">Default view</option>
                                    <option value="article">article</option>
                                    <option value="article-list">article-list</option>
                                </select>
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeArticle(this)">×</button>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($page->articles->count() == 0)
                    <p style="color: #999; font-size: 11px; padding: 20px; text-align: center;">No articles linked to this page</p>
                @endif
            </div>
        </div>

        <!-- Options Panel -->
        <div class="options-panel">
            <!-- Status -->
            <div class="status-buttons">
                <button type="submit" class="btn btn-success" style="flex: 1;">Save</button>
                <a href="{{ route('admin.pages.index') }}" class="btn">Cancel</a>
            </div>

            <!-- Name -->
            <div class="options-section open">
                <div class="options-header" onclick="toggleSection(this)">
                    Name <span class="toggle">▼</span>
                </div>
                <div class="options-section-body">
                    <div class="form-group">
                        <label>Technical name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $page->name) }}" required>
                    </div>
                </div>
            </div>

            <!-- Dates -->
            <div class="options-section">
                <div class="options-header" onclick="toggleSection(this)">
                    Dates <span class="toggle">▶</span>
                </div>
                <div class="options-section-body">
                    <div style="font-size: 10px; color: #666;">
                        <p>Created: {{ $page->created_at?->format('d.m.Y H:i') ?? '-' }}</p>
                        <p>Updated: {{ $page->updated_at?->format('d.m.Y H:i') ?? '-' }}</p>
                    </div>
                    <hr style="margin: 10px 0;">
                    <div class="date-input">
                        <label>Publish on</label>
                        <input type="datetime-local" name="publish_on"
                               value="{{ $page->publish_on ? $page->publish_on->format('Y-m-d\TH:i') : '' }}">
                    </div>
                    <div class="date-input">
                        <label>Publish off</label>
                        <input type="datetime-local" name="publish_off"
                               value="{{ $page->publish_off ? $page->publish_off->format('Y-m-d\TH:i') : '' }}">
                    </div>
                </div>
            </div>

            <!-- Attributes -->
            <div class="options-section open">
                <div class="options-header" onclick="toggleSection(this)">
                    Attributes <span class="toggle">▼</span>
                </div>
                <div class="options-section-body">
                    <div class="form-group">
                        <label>View template</label>
                        <input type="text" name="view" class="form-control" value="{{ old('view', $page->view) }}" placeholder="page">
                    </div>
                    <div class="checkbox-group">
                        <label>
                            <input type="checkbox" name="appears" value="1" {{ $page->appears ? 'checked' : '' }}>
                            Display in navigation
                        </label>
                    </div>
                    <div class="checkbox-group">
                        <label>
                            <input type="checkbox" name="has_url" value="1" {{ $page->has_url ? 'checked' : '' }}>
                            Has URL
                        </label>
                    </div>
                </div>
            </div>

            <!-- Parent -->
            <div class="options-section open">
                <div class="options-header" onclick="toggleSection(this)">
                    Parent <span class="toggle">▼</span>
                </div>
                <div class="options-section-body">
                    <div class="form-group">
                        <label>Menu</label>
                        <select name="id_menu" class="form-control">
                            @foreach($menus as $menu)
                                <option value="{{ $menu->id_menu }}" {{ $page->id_menu == $menu->id_menu ? 'selected' : '' }}>
                                    {{ $menu->title ?? $menu->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Parent page</label>
                        <select name="id_parent" class="form-control">
                            <option value="0">-- Root level --</option>
                            @foreach($pages as $parentPage)
                                <option value="{{ $parentPage->id_page }}" {{ $page->id_parent == $parentPage->id_page ? 'selected' : '' }}>
                                    {{ $parentPage->translations->first()->title ?? $parentPage->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- SEO -->
            <div class="options-section">
                <div class="options-header" onclick="toggleSection(this)">
                    SEO <span class="toggle">▶</span>
                </div>
                <div class="options-section-body">
                    @foreach($languages as $lang)
                        @php $translation = $page->translations->where('lang', $lang->lang)->first(); @endphp
                        <div style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                            <strong style="font-size: 10px; color: var(--primary);">{{ strtoupper($lang->lang) }}</strong>
                            <div class="form-group">
                                <label>Meta title</label>
                                <input type="text" name="meta_title_{{ $lang->lang }}" class="form-control"
                                       value="{{ old("meta_title_{$lang->lang}", $translation->meta_title ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label>Meta description</label>
                                <textarea name="meta_description_{{ $lang->lang }}" class="form-control" rows="2">{{ old("meta_description_{$lang->lang}", $translation->meta_description ?? '') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Meta keywords</label>
                                <input type="text" name="meta_keywords_{{ $lang->lang }}" class="form-control"
                                       value="{{ old("meta_keywords_{$lang->lang}", $translation->meta_keywords ?? '') }}">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Advanced -->
            <div class="options-section">
                <div class="options-header" onclick="toggleSection(this)">
                    Advanced <span class="toggle">▶</span>
                </div>
                <div class="options-section-body">
                    <div class="checkbox-group">
                        <label>
                            <input type="checkbox" name="home" value="1" {{ $page->home ? 'checked' : '' }}>
                            Home page
                        </label>
                    </div>
                    <div class="checkbox-group">
                        <label>
                            <input type="checkbox" name="online" value="1" {{ $page->online ? 'checked' : '' }}>
                            Page online
                        </label>
                    </div>
                    <div class="form-group" style="margin-top: 10px;">
                        <label>Used by module</label>
                        <input type="text" name="used_by_module" class="form-control" 
                               value="{{ old('used_by_module', $page->used_by_module) }}"
                               placeholder="e.g., shop, blog, gallery">
                        <small style="color: #999; font-size: 9px;">Module controller'a yönlendirme için modül adı</small>
                    </div>
                </div>
            </div>

            <!-- Link -->
            <div class="options-section">
                <div class="options-header" onclick="toggleSection(this)">
                    Link <span class="toggle">▶</span>
                </div>
                <div class="options-section-body">
                    <div class="form-group">
                        <label>Link type</label>
                        <select name="link_type" class="form-control" id="linkTypeSelect" onchange="toggleLinkFields()">
                            <option value="" {{ !$page->link_type ? 'selected' : '' }}>-- None --</option>
                            <option value="internal" {{ $page->link_type == 'internal' ? 'selected' : '' }}>Internal link</option>
                            <option value="external" {{ $page->link_type == 'external' ? 'selected' : '' }}>External link</option>
                            <option value="module" {{ $page->link_type == 'module' ? 'selected' : '' }}>Module</option>
                        </select>
                    </div>
                    <div class="form-group" id="linkField" style="{{ $page->link_type ? '' : 'display:none;' }}">
                        <label>Link URL</label>
                        <input type="text" name="link" class="form-control" 
                               value="{{ old('link', $page->link) }}" 
                               placeholder="{{ $page->link_type == 'external' ? 'https://...' : '/page-url' }}">
                    </div>
                </div>
            </div>

            <!-- Permissions -->
            <div class="options-section">
                <div class="options-header" onclick="toggleSection(this)">
                    Permissions <span class="toggle">▶</span>
                </div>
                <div class="options-section-body">
                    <p style="font-size: 10px; color: #666; margin-bottom: 10px;">
                        Select which user roles can access this page. If none selected, page is public.
                    </p>
                    @php
                        $roles = \App\Models\Role::orderBy('role_level', 'desc')->get();
                        $pageRoles = \App\Models\PageAcl::where('id_page', $page->id_page)->pluck('id_role')->toArray();
                    @endphp
                    @foreach($roles as $role)
                        <div class="checkbox-group">
                            <label>
                                <input type="checkbox" name="acl_roles[]" value="{{ $role->id_role }}"
                                       {{ in_array($role->id_role, $pageRoles) ? 'checked' : '' }}>
                                {{ $role->role_name }}
                                <small style="color: #999;">(Level {{ $role->role_level }})</small>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Delete -->
            <div class="options-section">
                <div class="options-section-body" style="display: block;">
                    <form action="{{ route('admin.pages.destroy', $page->id_page) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this page?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="width: 100%;">Delete page</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    // Tab switching
    document.querySelectorAll('.editor-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.editor-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.editor-tab-content').forEach(c => c.classList.remove('active'));

            this.classList.add('active');
            document.getElementById('tab-' + this.dataset.tab).classList.add('active');
        });
    });

    // Language tab switching
    document.querySelectorAll('.lang-content-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.lang-content-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.lang-content-area').forEach(c => c.classList.remove('active'));

            this.classList.add('active');
            document.getElementById('content-' + this.dataset.lang).classList.add('active');
        });
    });

    // Options section toggle
    function toggleSection(header) {
        const section = header.closest('.options-section');
        section.classList.toggle('open');
        const toggle = header.querySelector('.toggle');
        toggle.textContent = section.classList.contains('open') ? '▼' : '▶';
    }

    // Add media to page
    function addMediaToPage(url) {
        // Extract media ID or create placeholder
        const container = document.getElementById('attachedMedias');
        const item = document.createElement('div');
        item.className = 'media-list-item';
        item.innerHTML = `
            <img src="${url}" alt="Media">
            <button type="button" class="remove" onclick="removeMedia(this)">&times;</button>
        `;
        container.appendChild(item);
    }

    function removeMedia(btn) {
        btn.closest('.media-list-item').remove();
    }

    // Add article
    function addArticle() {
        const select = document.getElementById('articleSelector');
        if (!select.value) return;

        const option = select.options[select.selectedIndex];
        const list = document.getElementById('articlesList');

        const item = document.createElement('div');
        item.className = 'article-item';
        item.dataset.id = select.value;
        item.innerHTML = `
            <input type="hidden" name="articles[]" value="${select.value}">
            <span class="title">${option.dataset.title}</span>
            <div class="actions">
                <select name="article_view_${select.value}" class="view-select">
                    <option value="">Default view</option>
                    <option value="article">article</option>
                    <option value="article-list">article-list</option>
                </select>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeArticle(this)">×</button>
            </div>
        `;
        list.appendChild(item);

        // Remove from select
        option.remove();
        select.value = '';

        // Remove empty message
        const emptyMsg = list.querySelector('p');
        if (emptyMsg) emptyMsg.remove();
    }

    function removeArticle(btn) {
        btn.closest('.article-item').remove();
    }

    // Toggle link fields based on link type
    function toggleLinkFields() {
        const linkType = document.getElementById('linkTypeSelect').value;
        const linkField = document.getElementById('linkField');
        
        if (linkType) {
            linkField.style.display = 'block';
        } else {
            linkField.style.display = 'none';
        }
    }
</script>
@endpush
@endsection
