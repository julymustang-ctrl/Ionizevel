@extends('layouts.admin')
@section('title', 'Diller')
@section('content')
    <div class="page-header"><h2>Diller</h2></div>
    <div class="toolbar"><div class="toolbar-right"><a href="{{ route('admin.languages.create') }}" class="btn btn-success">+ Yeni Dil</a></div></div>
    <div class="panel"><table class="table"><thead><tr><th width="60">Kod</th><th>Ad</th><th>Varsayılan</th><th>Durum</th><th width="150">İşlemler</th></tr></thead>
    <tbody>@foreach($languages as $lang)<tr><td><strong>{{ strtoupper($lang->lang) }}</strong></td><td><a href="{{ route('admin.languages.edit', $lang->lang) }}">{{ $lang->name }}</a></td><td>@if($lang->def)<span style="color:var(--success);">✓</span>@endif</td><td>@if($lang->online)<span style="color:var(--success);">● Aktif</span>@else<span style="color:#999;">○ Pasif</span>@endif</td><td class="actions"><a href="{{ route('admin.languages.edit', $lang->lang) }}" class="btn">Düzenle</a>@if(!$lang->def)<form action="{{ route('admin.languages.destroy', $lang->lang) }}" method="POST" style="display:inline;" onsubmit="return confirm('Emin misiniz?');">@csrf @method('DELETE')<button type="submit" class="btn btn-danger">Sil</button></form>@endif</td></tr>@endforeach</tbody></table></div>{{ $languages->links() }}
@endsection
