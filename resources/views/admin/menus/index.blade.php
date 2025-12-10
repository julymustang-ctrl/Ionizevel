@extends('layouts.admin')
@section('title', 'Menüler')
@section('content')
    <div class="page-header"><h2>Menüler</h2></div>
    <div class="toolbar"><div class="toolbar-right"><a href="{{ route('admin.menus.create') }}" class="btn btn-success">+ Yeni Menü</a></div></div>
    <div class="panel"><table class="table"><thead><tr><th width="40">ID</th><th>Ad</th><th>Başlık</th><th>Sayfa Sayısı</th><th width="150">İşlemler</th></tr></thead>
    <tbody>@foreach($menus as $menu)<tr><td>{{ $menu->id_menu }}</td><td><a href="{{ route('admin.menus.edit', $menu->id_menu) }}">{{ $menu->name }}</a></td><td>{{ $menu->title }}</td><td>{{ $menu->pages_count }}</td><td class="actions"><a href="{{ route('admin.menus.edit', $menu->id_menu) }}" class="btn">Düzenle</a>@if($menu->pages_count == 0)<form action="{{ route('admin.menus.destroy', $menu->id_menu) }}" method="POST" style="display:inline;" onsubmit="return confirm('Emin misiniz?');">@csrf @method('DELETE')<button type="submit" class="btn btn-danger">Sil</button></form>@endif</td></tr>@endforeach</tbody></table></div>{{ $menus->links() }}
@endsection
