@extends('layouts.admin')

@section('title', 'Makale Düzenle')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span>›</span>
        <a href="{{ route('admin.articles.index') }}">Makaleler</a>
        <span>›</span>
        <span>{{ $article->translations->first()->title ?? $article->name }}</span>
    </div>
@endsection

@section('content')
    <div class="page-header">
        <div class="page-header-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#36607D">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
        </div>
        <div>
            <h2>{{ $article->translations->first()->title ?? $article->name }}</h2>
            <div class="subtitle">Makale ID: {{ $article->id_article }}</div>
        </div>
    </div>

    <form action="{{ route('admin.articles.update', $article->id_article) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="two-columns">
            <!-- Sol Kolon - İçerik -->
            <div>
                <div class="panel">
                    <div class="panel-header">Temel Bilgiler</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="required">Makale Adı (Teknik)</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $article->name) }}" required>
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
                                $translation = $article->translations->where('lang', $lang->lang)->first();
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
                                    <label>İçerik ({{ strtoupper($lang->lang) }})</label>
                                    <textarea name="content_{{ $lang->lang }}" class="wysiwyg" id="content_{{ $lang->lang }}">{{ old("content_{$lang->lang}", $translation->content ?? '') }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="online_{{ $lang->lang }}" value="1"
                                               {{ ($translation->online ?? false) ? 'checked' : '' }}> Bu dilde yayında
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
                                $translation = $article->translations->where('lang', $lang->lang)->first();
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
                                <input type="checkbox" name="indexed" value="1" {{ $article->indexed ? 'checked' : '' }}>
                                Arama motorlarında indekslensin
                            </label>
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="comment_allow" value="1" {{ $article->comment_allow ? 'checked' : '' }}>
                                Yorumlara izin ver
                            </label>
                        </div>

                        <div class="form-group">
                            <label>Yayın Başlangıcı</label>
                            <input type="datetime-local" name="publish_on" class="form-control"
                                   value="{{ $article->publish_on ? $article->publish_on->format('Y-m-d\TH:i') : '' }}">
                        </div>

                        <div class="form-group">
                            <label>Yayın Bitişi</label>
                            <input type="datetime-local" name="publish_off" class="form-control"
                                   value="{{ $article->publish_off ? $article->publish_off->format('Y-m-d\TH:i') : '' }}">
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-header">Kategoriler</div>
                    <div class="panel-body">
                        @foreach($categories as $category)
                            <div class="form-group" style="margin-bottom: 5px;">
                                <label style="margin: 0;">
                                    <input type="checkbox" name="categories[]" value="{{ $category->id_category }}"
                                        {{ $article->categories->contains('id_category', $category->id_category) ? 'checked' : '' }}>
                                    {{ $category->translations->first()->title ?? $category->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-header">Bilgi</div>
                    <div class="panel-body" style="font-size: 11px; color: #666;">
                        <p><strong>Oluşturan:</strong> {{ $article->author ?? '-' }}</p>
                        <p><strong>Oluşturma:</strong> {{ $article->created_at?->format('d.m.Y H:i') }}</p>
                        <p><strong>Son Güncelleme:</strong> {{ $article->updated_at?->format('d.m.Y H:i') }}</p>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-footer" style="text-align: center;">
                        <a href="{{ route('admin.articles.index') }}" class="btn">İptal</a>
                        <button type="submit" class="btn btn-success">Kaydet</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
        // Switch both content and SEO tabs together
        document.querySelectorAll('.lang-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.lang-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.lang-content').forEach(c => c.style.display = 'none');

                this.classList.add('active');
                const lang = this.dataset.lang;
                document.getElementById('lang-' + lang).style.display = 'block';
                document.getElementById('seo-' + lang).style.display = 'block';

                // Save TinyMCE content before switching
                tinymce.triggerSave();
            });
        });
    </script>
    @endpush
@endsection
