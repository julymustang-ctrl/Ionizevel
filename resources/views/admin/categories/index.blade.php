@extends('layouts.admin')
@section('title', 'Kategoriler')
@section('content')
    <div class="page-header"><h2>Kategoriler</h2><div class="subtitle">{{ $categories->total() }} kategori</div></div>
    <div class="toolbar"><div class="toolbar-left"></div><div class="toolbar-right"><a href="{{ route('admin.categories.create') }}" class="btn btn-success">+ Yeni Kategori</a></div></div>
    <div class="panel">
        <table class="table"><thead><tr><th width="40">ID</th><th>Ad</th><th>Sıra</th><th width="150">İşlemler</th></tr></thead>
        <tbody>@forelse($categories as $cat)<tr><td>{{ $cat->id_category }}</td><td><a href="{{ route('admin.categories.edit', $cat->id_category) }}">{{ $cat->translations->first()->title ?? $cat->name }}</a></td><td>{{ $cat->ordering }}</td><td class="actions"><a href="{{ route('admin.categories.edit', $cat->id_category) }}" class="btn">Düzenle</a><form action="{{ route('admin.categories.destroy', $cat->id_category) }}" method="POST" style="display:inline;" onsubmit="return confirm('Emin misiniz?');">@csrf @method('DELETE')<button type="submit" class="btn btn-danger">Sil</button></form></td></tr>@empty<tr><td colspan="4" style="text-align:center;color:#999;padding:30px;">Kategori yok.</td></tr>@endforelse</tbody></table>
    </div>{{ $categories->links() }}
@endsection
