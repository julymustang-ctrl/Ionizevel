@extends('layouts.admin')

@section('title', 'Yeni Makale')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span>›</span>
        <a href="{{ route('admin.articles.index') }}">Makaleler</a>
        <span>›</span>
        <span>Yeni Makale</span>
    </div>
@endsection

@section('content')
    <div class="page-header">
        <div class="page-header-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#36607D">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
        </div>
        <h2>Yeni Makale</h2>
    </div>

    <form action="{{ route('admin.articles.store') }}" method="POST">
        @csrf

        <div class="panel">
            <div class="panel-header">Temel Bilgiler</div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="required">Makale Adı (Teknik)</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label>Kategoriler</label>
                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                        @foreach($categories as $category)
                            <label style="margin: 0;">
                                <input type="checkbox" name="categories[]" value="{{ $category->id_category }}">
                                {{ $category->translations->first()->title ?? $category->name }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="indexed" value="1"> Arama motorlarında indekslensin
                    </label>
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
                    <div class="lang-content" id="lang-{{ $lang->lang }}" style="{{ $index !== 0 ? 'display:none;' : '' }}">
                        <div class="form-group">
                            <label>Başlık ({{ strtoupper($lang->lang) }})</label>
                            <input type="text" name="title_{{ $lang->lang }}" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>URL ({{ strtoupper($lang->lang) }})</label>
                            <input type="text" name="url_{{ $lang->lang }}" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>İçerik ({{ strtoupper($lang->lang) }})</label>
                            <textarea name="content_{{ $lang->lang }}" class="form-control" rows="10"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Meta Başlık ({{ strtoupper($lang->lang) }})</label>
                            <input type="text" name="meta_title_{{ $lang->lang }}" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Meta Açıklama ({{ strtoupper($lang->lang) }})</label>
                            <textarea name="meta_description_{{ $lang->lang }}" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="online_{{ $lang->lang }}" value="1" checked> Bu dilde yayında
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="panel-footer">
                <a href="{{ route('admin.articles.index') }}" class="btn">İptal</a>
                <button type="submit" class="btn btn-success">Kaydet</button>
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
        document.querySelectorAll('.lang-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.lang-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.lang-content').forEach(c => c.style.display = 'none');
                this.classList.add('active');
                document.getElementById('lang-' + this.dataset.lang).style.display = 'block';
            });
        });
    </script>
    @endpush
@endsection
