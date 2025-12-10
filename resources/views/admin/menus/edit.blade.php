@extends('layouts.admin')
@section('title', 'Menü Düzenle')
@section('content')
    <div class="page-header"><h2>{{ $menu->title }}</h2></div>
    <form action="{{ route('admin.menus.update', $menu->id_menu) }}" method="POST">@csrf @method('PUT')
        <div class="panel"><div class="panel-header">Menü Bilgileri</div><div class="panel-body">
            <div class="form-group"><label class="required">Teknik Ad</label><input type="text" name="name" class="form-control" value="{{ $menu->name }}" required></div>
            <div class="form-group"><label class="required">Başlık</label><input type="text" name="title" class="form-control" value="{{ $menu->title }}" required></div>
        </div><div class="panel-footer"><a href="{{ route('admin.menus.index') }}" class="btn">İptal</a> <button type="submit" class="btn btn-success">Kaydet</button></div></div>
    </form>
@endsection
