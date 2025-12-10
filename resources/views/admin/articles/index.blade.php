@extends('layouts.admin')

@section('title', 'Makaleler')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span>›</span>
        <span>Makaleler</span>
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
            <h2>Makaleler</h2>
            <div class="subtitle">{{ $articles->total() }} makale mevcut</div>
        </div>
    </div>

    <div class="toolbar">
        <div class="toolbar-left"></div>
        <div class="toolbar-right">
            <a href="{{ route('admin.articles.create') }}" class="btn btn-success">+ Yeni Makale</a>
        </div>
    </div>

    <div class="panel">
        <table class="table">
            <thead>
                <tr>
                    <th width="40">ID</th>
                    <th>Başlık</th>
                    <th>Kategoriler</th>
                    <th width="120">Tarih</th>
                    <th width="80">Durum</th>
                    <th width="150">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                    <tr>
                        <td>{{ $article->id_article }}</td>
                        <td>
                            <a href="{{ route('admin.articles.edit', $article->id_article) }}">
                                {{ $article->translations->first()->title ?? $article->name }}
                            </a>
                        </td>
                        <td>
                            @forelse($article->categories as $cat)
                                <span style="background: #eee; padding: 2px 6px; border-radius: 3px; font-size: 10px;">
                                    {{ $cat->translations->first()->title ?? $cat->name }}
                                </span>
                            @empty
                                <span style="color: #999;">-</span>
                            @endforelse
                        </td>
                        <td>{{ $article->created_at?->format('d.m.Y H:i') }}</td>
                        <td>
                            @if($article->translations->where('online', true)->count() > 0)
                                <span style="color: var(--success);">● Yayında</span>
                            @else
                                <span style="color: #999;">○ Taslak</span>
                            @endif
                        </td>
                        <td class="actions">
                            <a href="{{ route('admin.articles.edit', $article->id_article) }}" class="btn">Düzenle</a>
                            <form action="{{ route('admin.articles.destroy', $article->id_article) }}" method="POST" style="display: inline;" onsubmit="return confirm('Bu makaleyi silmek istediğinize emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Sil</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #999; padding: 30px;">
                            Henüz makale oluşturulmamış.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $articles->links() }}
@endsection
