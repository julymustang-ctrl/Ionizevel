@extends('layouts.admin')
@section('title', 'Medya Düzenle')
@section('content')
    <div class="page-header"><h2>{{ $media->file_name }}</h2></div>
    <form action="{{ route('admin.media.update', $media->id_media) }}" method="POST">@csrf @method('PUT')
        <div class="panel"><div class="panel-header">Medya Bilgileri</div><div class="panel-body">
            @if($media->type === 'picture')<div style="margin-bottom:15px;"><img src="{{ asset($media->path) }}" style="max-width:300px;"></div>@endif
            @foreach($languages as $lang)@php $t = $media->translations->where('lang', $lang->lang)->first(); @endphp
            <div class="form-group"><label>Başlık ({{ strtoupper($lang->lang) }})</label><input type="text" name="title_{{ $lang->lang }}" class="form-control" value="{{ $t->title ?? '' }}"></div>
            <div class="form-group"><label>Alt ({{ strtoupper($lang->lang) }})</label><input type="text" name="alt_{{ $lang->lang }}" class="form-control" value="{{ $t->alt ?? '' }}"></div>
            <div class="form-group"><label>Açıklama ({{ strtoupper($lang->lang) }})</label><textarea name="description_{{ $lang->lang }}" class="form-control" rows="3">{{ $t->description ?? '' }}</textarea></div>@endforeach
        </div><div class="panel-footer"><a href="{{ route('admin.media.index') }}" class="btn">İptal</a> <button type="submit" class="btn btn-success">Kaydet</button></div></div>
    </form>
@endsection
