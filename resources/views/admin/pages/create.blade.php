@extends('layouts.admin')

@section('title', 'Yeni Sayfa')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span>›</span>
        <a href="{{ route('admin.pages.index') }}">Sayfalar</a>
        <span>›</span>
        <span>Yeni Sayfa</span>
    </div>
@endsection

@section('content')
    <div class="page-header">
        <div class="page-header-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#36607D">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h2>Yeni Sayfa</h2>
    </div>

    <form action="{{ route('admin.pages.store') }}" method="POST">
        @csrf

        <div class="panel">
            <div class="panel-header">Temel Bilgiler</div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="required">Sayfa Adı (Teknik)</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label class="required">Menü</label>
                    <select name="id_menu" class="form-control" required>
                        @foreach($menus as $menu)
                            <option value="{{ $menu->id_menu }}">{{ $menu->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Üst Sayfa</label>
                    <select name="id_parent" class="form-control">
                        <option value="0">-- Ana Seviye --</option>
                        @foreach($pages as $parentPage)
                            <option value="{{ $parentPage->id_page }}">
                                {{ $parentPage->translations->first()->title ?? $parentPage->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="online" value="1"> Yayında
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
                            <input type="text" name="title_{{ $lang->lang }}" class="form-control" value="{{ old("title_{$lang->lang}") }}">
                        </div>

                        <div class="form-group">
                            <label>URL ({{ strtoupper($lang->lang) }})</label>
                            <input type="text" name="url_{{ $lang->lang }}" class="form-control" value="{{ old("url_{$lang->lang}") }}">
                        </div>

                        <div class="form-group">
                            <label>Meta Başlık ({{ strtoupper($lang->lang) }})</label>
                            <input type="text" name="meta_title_{{ $lang->lang }}" class="form-control" value="{{ old("meta_title_{$lang->lang}") }}">
                        </div>

                        <div class="form-group">
                            <label>Meta Açıklama ({{ strtoupper($lang->lang) }})</label>
                            <textarea name="meta_description_{{ $lang->lang }}" class="form-control" rows="3">{{ old("meta_description_{$lang->lang}") }}</textarea>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="panel-footer">
                <a href="{{ route('admin.pages.index') }}" class="btn">İptal</a>
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
