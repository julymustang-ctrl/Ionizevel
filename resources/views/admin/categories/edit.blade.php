@extends('layouts.admin')
@section('title', 'Kategori Düzenle')
@section('content')
    <div class="page-header"><h2>{{ $category->name }}</h2></div>
    <form action="{{ route('admin.categories.update', $category->id_category) }}" method="POST">@csrf @method('PUT')
        <div class="panel"><div class="panel-header">Kategori Bilgileri</div><div class="panel-body">
            <div class="form-group"><label class="required">Ad</label><input type="text" name="name" class="form-control" value="{{ $category->name }}" required></div>
            @foreach($languages as $lang)@php $t = $category->translations->where('lang', $lang->lang)->first(); @endphp<div class="form-group"><label>Başlık ({{ strtoupper($lang->lang) }})</label><input type="text" name="title_{{ $lang->lang }}" class="form-control" value="{{ $t->title ?? '' }}"></div>@endforeach
        </div><div class="panel-footer"><a href="{{ route('admin.categories.index') }}" class="btn">İptal</a> <button type="submit" class="btn btn-success">Kaydet</button></div></div>
    </form>
@endsection
