@extends('layouts.admin')

@section('title', 'Sayfalar')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span>›</span>
        <span>Sayfalar</span>
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
            <h2>Sayfalar</h2>
            <div class="subtitle">{{ $pages->total() }} sayfa mevcut</div>
        </div>
    </div>

    <div class="toolbar">
        <div class="toolbar-left">
            <select class="form-control" style="width: 200px;">
                <option value="">Tüm Menüler</option>
                @foreach($menus as $menu)
                    <option value="{{ $menu->id_menu }}">{{ $menu->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="toolbar-right">
            <a href="{{ route('admin.pages.create') }}" class="btn btn-success">+ Yeni Sayfa</a>
        </div>
    </div>

    <div class="panel">
        <table class="table">
            <thead>
                <tr>
                    <th width="40">ID</th>
                    <th>Başlık</th>
                    <th>Menü</th>
                    <th width="80">Sıra</th>
                    <th width="80">Durum</th>
                    <th width="150">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $page)
                    <tr>
                        <td>{{ $page->id_page }}</td>
                        <td>
                            <a href="{{ route('admin.pages.edit', $page->id_page) }}">
                                {{ $page->translations->first()->title ?? $page->name }}
                            </a>
                            @if($page->home)
                                <span style="color: var(--success); font-size: 10px;">(Ana Sayfa)</span>
                            @endif
                        </td>
                        <td>{{ $page->menu->title ?? '-' }}</td>
                        <td>{{ $page->ordering }}</td>
                        <td>
                            @if($page->online)
                                <span style="color: var(--success);">● Yayında</span>
                            @else
                                <span style="color: #999;">○ Taslak</span>
                            @endif
                        </td>
                        <td class="actions">
                            <a href="{{ route('admin.pages.edit', $page->id_page) }}" class="btn">Düzenle</a>
                            <form action="{{ route('admin.pages.destroy', $page->id_page) }}" method="POST" style="display: inline;" onsubmit="return confirm('Bu sayfayı silmek istediğinize emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Sil</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #999; padding: 30px;">
                            Henüz sayfa oluşturulmamış.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $pages->links() }}
@endsection
