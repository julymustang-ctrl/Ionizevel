@extends('layouts.admin')
@section('title', 'Dil Düzenle')
@section('content')
    <div class="page-header"><h2>{{ $language->name }}</h2></div>
    <form action="{{ route('admin.languages.update', $language->lang) }}" method="POST">@csrf @method('PUT')
        <div class="panel"><div class="panel-header">Dil Bilgileri</div><div class="panel-body">
            <div class="form-group"><label>Dil Kodu</label><input type="text" class="form-control" value="{{ strtoupper($language->lang) }}" disabled></div>
            <div class="form-group"><label class="required">Dil Adı</label><input type="text" name="name" class="form-control" value="{{ $language->name }}" required></div>
            <div class="form-group"><label><input type="checkbox" name="online" value="1" {{ $language->online ? 'checked' : '' }}> Aktif</label></div>
            <div class="form-group"><label><input type="checkbox" name="def" value="1" {{ $language->def ? 'checked' : '' }}> Varsayılan Dil</label></div>
        </div><div class="panel-footer"><a href="{{ route('admin.languages.index') }}" class="btn">İptal</a> <button type="submit" class="btn btn-success">Kaydet</button></div></div>
    </form>
@endsection
