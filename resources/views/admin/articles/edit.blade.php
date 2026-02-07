@extends('layouts.admin')

@section('title', 'Makale Düzenle')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span>›</span>
        <a href="{{ route('admin.articles.index') }}">Articles</a>
        <span>›</span>
        <span>{{ $article->translations->first()->title ?? $article->name }}</span>
    </div>
@endsection

@section('actions')
    <button type="submit" form="articleForm" class="btn btn-success">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        Save
    </button>
@endsection

@section('content')
<style>
    .article-editor {
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

    /* Categories */
    .category-list {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid var(--border-color);
        padding: 10px;
    }

    .category-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 5px 0;
        font-size: 11px;
    }
</style>

<form id="articleForm" action="{{ route('admin.articles.update', $article->id_article) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="article-editor">
        <!-- Main Editor Area -->
        <div class="editor-main">
            <!-- Tabs -->
            <div class="editor-tabs">
                <div class="editor-tab active" data-tab="content">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="vertical-align: middle; margin-right: 5px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    Content
                </div>
                <div class="editor-tab" data-tab="medias">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="vertical-align: middle; margin-right: 5px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Medias
                </div>
                <div class="editor-tab" data-tab="categories">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="vertical-align: middle; margin-right: 5px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Categories
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
                    @php $translation = $article->translations->where('lang', $lang->lang)->first(); @endphp
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

                        <div class="form-group">
                            <label>Content ({{ strtoupper($lang->lang) }})</label>
                            <textarea name="content_{{ $lang->lang }}" class="form-control wysiwyg" rows="10">{{ old("content_{$lang->lang}", $translation->content ?? '') }}</textarea>
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
                    <button type="button" class="btn" onclick="openMediaPicker(addMediaToArticle)">Select Media</button>
                </div>

                <h4 style="font-size: 12px; margin-bottom: 10px;">Attached Medias</h4>
                <div class="media-list" id="attachedMedias">
                    @foreach($article->media as $media)
                        <div class="media-list-item" data-id="{{ $media->id_media }}">
                            <input type="hidden" name="media_ids[]" value="{{ $media->id_media }}">
                            <img src="{{ asset('storage/' . $media->path) }}" alt="{{ $media->name }}">
                            <button type="button" class="remove" onclick="removeMedia(this)">&times;</button>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Categories Tab -->
            <div class="editor-tab-content" id="tab-categories">
                <h4 style="font-size: 12px; margin-bottom: 10px;">Select Categories</h4>
                <div class="category-list">
                    @foreach($categories as $category)
                        @php $catTranslation = $category->translations->first(); @endphp
                        <div class="category-item">
                            <input type="checkbox" name="categories[]" value="{{ $category->id_category }}"
                                   id="cat_{{ $category->id_category }}"
                                   {{ $article->categories->contains('id_category', $category->id_category) ? 'checked' : '' }}>
                            <label for="cat_{{ $category->id_category }}">
                                {{ $catTranslation->title ?? $category->name }}
                            </label>
                        </div>
                    @endforeach
                </div>

                @if($categories->count() == 0)
                    <p style="color: #999; font-size: 11px; padding: 20px; text-align: center;">No categories available</p>
                @endif
            </div>
        </div>

        <!-- Options Panel -->
        <div class="options-panel">
            <!-- Status -->
            <div class="status-buttons">
                <button type="submit" class="btn btn-success" style="flex: 1;">Save</button>
                <a href="{{ route('admin.articles.index') }}" class="btn">Cancel</a>
            </div>

            <!-- Name -->
            <div class="options-section open">
                <div class="options-header" onclick="toggleSection(this)">
                    Name <span class="toggle">▼</span>
                </div>
                <div class="options-section-body">
                    <div class="form-group">
                        <label>Technical name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $article->name) }}" required>
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
                        <p>Created: {{ $article->created_at?->format('d.m.Y H:i') ?? '-' }}</p>
                        <p>Updated: {{ $article->updated_at?->format('d.m.Y H:i') ?? '-' }}</p>
                    </div>
                    <hr style="margin: 10px 0;">
                    <div class="date-input">
                        <label>Publish on</label>
                        <input type="datetime-local" name="publish_on"
                               value="{{ $article->publish_on ? $article->publish_on->format('Y-m-d\TH:i') : '' }}">
                    </div>
                    <div class="date-input">
                        <label>Publish off</label>
                        <input type="datetime-local" name="publish_off"
                               value="{{ $article->publish_off ? $article->publish_off->format('Y-m-d\TH:i') : '' }}">
                    </div>
                </div>
            </div>

            <!-- Settings -->
            <div class="options-section open">
                <div class="options-header" onclick="toggleSection(this)">
                    Settings <span class="toggle">▼</span>
                </div>
                <div class="options-section-body">
                    <div class="checkbox-group">
                        <label>
                            <input type="checkbox" name="indexed" value="1" {{ $article->indexed ? 'checked' : '' }}>
                            Indexed (appears in search)
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Main category</label>
                        <select name="id_category" class="form-control">
                            <option value="">-- None --</option>
                            @foreach($categories as $category)
                                @php $catTranslation = $category->translations->first(); @endphp
                                <option value="{{ $category->id_category }}"
                                        {{ $article->id_category == $category->id_category ? 'selected' : '' }}>
                                    {{ $catTranslation->title ?? $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Comments -->
            <div class="options-section">
                <div class="options-header" onclick="toggleSection(this)">
                    Comments <span class="toggle">▶</span>
                </div>
                <div class="options-section-body">
                    <div class="checkbox-group">
                        <label>
                            <input type="checkbox" name="comment_allow" value="1" {{ $article->comment_allow ? 'checked' : '' }}>
                            Allow comments
                        </label>
                    </div>
                    <div class="checkbox-group">
                        <label>
                            <input type="checkbox" name="comment_autovalid" value="1" {{ $article->comment_autovalid ? 'checked' : '' }}>
                            Auto-validate comments
                        </label>
                    </div>
                    <div class="date-input">
                        <label>Expire</label>
                        <input type="datetime-local" name="comment_expire"
                               value="{{ $article->comment_expire ? $article->comment_expire->format('Y-m-d\TH:i') : '' }}">
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
                        @php $translation = $article->translations->where('lang', $lang->lang)->first(); @endphp
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

            <!-- Delete -->
            <div class="options-section">
                <div class="options-section-body" style="display: block;">
                    <form action="{{ route('admin.articles.destroy', $article->id_article) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this article?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="width: 100%;">Delete article</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    // Initialize TinyMCE
    document.addEventListener('DOMContentLoaded', function() {
        initTinyMCE('.wysiwyg');
    });

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

    // Add media to article
    function addMediaToArticle(url) {
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
</script>
@endpush
@endsection
