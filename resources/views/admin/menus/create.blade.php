@extends('layouts.admin')
@section('title', 'Yeni Menü')
@section('content')
    <div class="page-header"><h2>Yeni Menü</h2></div>
    <form action="{{ route('admin.menus.store') }}" method="POST">@csrf
        <div class="panel"><div class="panel-header">Menü Bilgileri</div><div class="panel-body">
            <div class="form-group"><label class="required">Teknik Ad</label><input type="text" name="name" class="form-control" required></div>
            <div class="form-group"><label class="required">Başlık</label><input type="text" name="title" class="form-control" required></div>
        </div><div class="panel-footer"><a href="{{ route('admin.menus.index') }}" class="btn">İptal</a> <button type="submit" class="btn btn-success">Kaydet</button></div></div>
    </form>
@endsection
