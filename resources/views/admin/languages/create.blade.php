@extends('layouts.admin')
@section('title', 'Yeni Dil')
@section('content')
    <div class="page-header"><h2>Yeni Dil</h2></div>
    <form action="{{ route('admin.languages.store') }}" method="POST">@csrf
        <div class="panel"><div class="panel-header">Dil Bilgileri</div><div class="panel-body">
            <div class="form-group"><label class="required">Dil Kodu (2-3 harf)</label><input type="text" name="lang" class="form-control" maxlength="3" required></div>
            <div class="form-group"><label class="required">Dil Adı</label><input type="text" name="name" class="form-control" required></div>
            <div class="form-group"><label><input type="checkbox" name="online" value="1" checked> Aktif</label></div>
            <div class="form-group"><label><input type="checkbox" name="def" value="1"> Varsayılan Dil</label></div>
        </div><div class="panel-footer"><a href="{{ route('admin.languages.index') }}" class="btn">İptal</a> <button type="submit" class="btn btn-success">Kaydet</button></div></div>
    </form>
@endsection
