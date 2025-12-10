@extends('layouts.admin')

@section('title', 'Sayfa Düzenle')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span>›</span>
        <a href="{{ route('admin.pages.index') }}">Sayfalar</a>
        <span>›</span>
        <span>{{ $page->translations->first()->title ?? $page->name }}</span>
    </div>
@endsection

@section('content')
    <div class="page-header">
        <div class="page-header-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#36607D">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <div>
            <h2>{{ $page->translations->first()->title ?? $page->name }}</h2>
            <div class="subtitle">Sayfa ID: {{ $page->id_page }}</div>
        </div>
    </div>

    <form action="{{ route('admin.pages.update', $page->id_page) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="two-columns">
            <!-- Sol Kolon - İçerik -->
            <div>
                <div class="panel">
                    <div class="panel-header">Temel Bilgiler</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="required">Sayfa Adı (Teknik)</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $page->name) }}" required>
                        </div>

                        <div class="form-group">
                            <label class="required">Menü</label>
                            <select name="id_menu" class="form-control" required>
                                @foreach($menus as $menu)
                                    <option value="{{ $menu->id_menu }}" {{ $page->id_menu == $menu->id_menu ? 'selected' : '' }}>
                                        {{ $menu->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Üst Sayfa</label>
                            <select name="id_parent" class="form-control">
                                <option value="0">-- Ana Seviye --</option>
                                @foreach($pages as $parentPage)
                                    <option value="{{ $parentPage->id_page }}" {{ $page->id_parent == $parentPage->id_page ? 'selected' : '' }}>
                                        {{ $parentPage->translations->first()->title ?? $parentPage->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>View Şablonu</label>
                            <input type="text" name="view" class="form-control" value="{{ old('view', $page->view) }}" placeholder="page">
                        </div>
                    </div>
                </div>

                <!-- Dil İçerikleri -->
                <div class="panel">
                    <div class="panel-header">İçerik</div>
                    <div class="panel-body">
                        <div class="lang-tabs">
                            @foreach($languages as $index => $lang)
                                <div class="lang-tab {{ $index === 0 ? 'active' : '' }}" data-lang="{{ $lang->lang }}">
                                    {{ strtoupper($lang->lang) }}
                                </div>
                            @endforeach
                        </div>

                        @foreach($languages as $index => $lang)
                            @php
                                $translation = $page->translations->where('lang', $lang->lang)->first();
                            @endphp
                            <div class="lang-content" id="lang-{{ $lang->lang }}" style="{{ $index !== 0 ? 'display:none;' : '' }}">
                                <div class="form-group">
                                    <label>Başlık ({{ strtoupper($lang->lang) }})</label>
                                    <input type="text" name="title_{{ $lang->lang }}" class="form-control"
                                           value="{{ old("title_{$lang->lang}", $translation->title ?? '') }}"
                                           onkeyup="document.getElementById('url_{{ $lang->lang }}').value = generateSlug(this.value)">
                                </div>

                                <div class="form-group">
                                    <label>URL ({{ strtoupper($lang->lang) }})</label>
                                    <input type="text" name="url_{{ $lang->lang }}" id="url_{{ $lang->lang }}" class="form-control"
                                           value="{{ old("url_{$lang->lang}", $translation->url ?? '') }}">
                                </div>

                                <div class="form-group">
                                    <label>Alt Başlık ({{ strtoupper($lang->lang) }})</label>
                                    <input type="text" name="subtitle_{{ $lang->lang }}" class="form-control"
                                           value="{{ old("subtitle_{$lang->lang}", $translation->subtitle ?? '') }}">
                                </div>

                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="online_{{ $lang->lang }}" value="1"
                                               {{ ($translation->online ?? true) ? 'checked' : '' }}> Bu dilde yayında
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- SEO -->
                <div class="panel">
                    <div class="panel-header">SEO Ayarları</div>
                    <div class="panel-body">
                        @foreach($languages as $index => $lang)
                            @php
                                $translation = $page->translations->where('lang', $lang->lang)->first();
                            @endphp
                            <div class="lang-content" id="seo-{{ $lang->lang }}" style="{{ $index !== 0 ? 'display:none;' : '' }}">
                                <div class="form-group">
                                    <label>Meta Başlık ({{ strtoupper($lang->lang) }})</label>
                                    <input type="text" name="meta_title_{{ $lang->lang }}" class="form-control"
                                           value="{{ old("meta_title_{$lang->lang}", $translation->meta_title ?? '') }}">
                                </div>

                                <div class="form-group">
                                    <label>Meta Açıklama ({{ strtoupper($lang->lang) }})</label>
                                    <textarea name="meta_description_{{ $lang->lang }}" class="form-control" rows="3">{{ old("meta_description_{$lang->lang}", $translation->meta_description ?? '') }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>Meta Anahtar Kelimeler ({{ strtoupper($lang->lang) }})</label>
                                    <input type="text" name="meta_keywords_{{ $lang->lang }}" class="form-control"
                                           value="{{ old("meta_keywords_{$lang->lang}", $translation->meta_keywords ?? '') }}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Sağ Kolon - Seçenekler -->
            <div>
                <div class="panel">
                    <div class="panel-header">Yayın Durumu</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="online" value="1" {{ $page->online ? 'checked' : '' }}>
                                Yayında
                            </label>
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="appears" value="1" {{ $page->appears ? 'checked' : '' }}>
                                Menüde Görünsün
                            </label>
                        </div>

                        @if($page->home)
                            <div class="alert alert-info" style="padding: 8px; margin-top: 10px;">
                                Bu sayfa ana sayfa olarak ayarlanmış.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Bağlı Makaleler -->
                <div class="panel">
                    <div class="panel-header">Bağlı Makaleler</div>
                    <div class="panel-body">
                        <div class="linked-items" id="linkedArticles">
                            @forelse($page->articles as $article)
                                <div class="linked-item">
                                    <input type="hidden" name="articles[]" value="{{ $article->id_article }}">
                                    {{ $article->translations->first()->title ?? $article->name }}
                                    <span class="remove" onclick="this.parentElement.remove()">×</span>
                                </div>
                            @empty
                                <em style="color: #999; font-size: 11px;">Henüz bağlı makale yok</em>
                            @endforelse
                        </div>

                        <div class="form-group">
                            <select id="articleSelect" class="form-control" onchange="addArticle(this)">
                                <option value="">-- Makale Ekle --</option>
                                @foreach($articles as $article)
                                    @if(!$page->articles->contains('id_article', $article->id_article))
                                        <option value="{{ $article->id_article }}" data-title="{{ $article->translations->first()->title ?? $article->name }}">
                                            {{ $article->translations->first()->title ?? $article->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-header">Bilgi</div>
                    <div class="panel-body" style="font-size: 11px; color: #666;">
                        <p><strong>Oluşturan:</strong> {{ $page->author ?? '-' }}</p>
                        <p><strong>Oluşturma:</strong> {{ $page->created_at?->format('d.m.Y H:i') }}</p>
                        <p><strong>Son Güncelleme:</strong> {{ $page->updated_at?->format('d.m.Y H:i') }}</p>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-footer" style="text-align: center;">
                        <a href="{{ route('admin.pages.index') }}" class="btn">İptal</a>
                        <button type="submit" class="btn btn-success">Kaydet</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
        // Language tab switching
        document.querySelectorAll('.lang-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.lang-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.lang-content').forEach(c => c.style.display = 'none');

                this.classList.add('active');
                const lang = this.dataset.lang;
                document.getElementById('lang-' + lang).style.display = 'block';
                document.getElementById('seo-' + lang).style.display = 'block';
            });
        });

        // Add article to page
        function addArticle(select) {
            if (!select.value) return;

            const option = select.options[select.selectedIndex];
            const container = document.getElementById('linkedArticles');

            // Remove empty message if exists
            const emptyMsg = container.querySelector('em');
            if (emptyMsg) emptyMsg.remove();

            // Create linked item
            const item = document.createElement('div');
            item.className = 'linked-item';
            item.innerHTML = `
                <input type="hidden" name="articles[]" value="${select.value}">
                ${option.dataset.title}
                <span class="remove" onclick="this.parentElement.remove()">×</span>
            `;
            container.appendChild(item);

            // Remove from select
            option.remove();
            select.value = '';
        }
    </script>
    @endpush
@endsection
