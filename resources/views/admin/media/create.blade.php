@extends('layouts.admin')
@section('title', 'Dosya Yükle')
@section('content')
    <div class="page-header"><h2>Dosya Yükle</h2></div>
    <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data">@csrf
        <div class="panel"><div class="panel-header">Dosya Seç</div><div class="panel-body">
            <div class="form-group"><label class="required">Dosya</label><input type="file" name="file" class="form-control" required></div>
        </div><div class="panel-footer"><a href="{{ route('admin.media.index') }}" class="btn">İptal</a> <button type="submit" class="btn btn-success">Yükle</button></div></div>
    </form>
@endsection
