@extends('layouts.admin')
@section('title', 'Yeni Kategori')
@section('content')
    <div class="page-header"><h2>Yeni Kategori</h2></div>
    <form action="{{ route('admin.categories.store') }}" method="POST">@csrf
        <div class="panel"><div class="panel-header">Kategori Bilgileri</div><div class="panel-body">
            <div class="form-group"><label class="required">Ad</label><input type="text" name="name" class="form-control" required></div>
            @foreach($languages as $lang)<div class="form-group"><label>Başlık ({{ strtoupper($lang->lang) }})</label><input type="text" name="title_{{ $lang->lang }}" class="form-control"></div>@endforeach
        </div><div class="panel-footer"><a href="{{ route('admin.categories.index') }}" class="btn">İptal</a> <button type="submit" class="btn btn-success">Kaydet</button></div></div>
    </form>
@endsection
