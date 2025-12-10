@extends('layouts.admin')
@section('title', 'Ayarlar')
@section('content')
    <div class="page-header"><h2>Sistem Ayarları</h2></div>
    <form action="{{ route('admin.settings.update') }}" method="POST">@csrf
        <div class="panel"><div class="panel-header">Genel Ayarlar</div><div class="panel-body">
            <div class="form-group"><label>Web Sitesi E-posta</label><input type="email" name="website_email" class="form-control" value="{{ $settings['website_email']->content ?? '' }}"></div>
            <div class="form-group"><label>Dosya Dizini</label><input type="text" name="files_path" class="form-control" value="{{ $settings['files_path']->content ?? 'files' }}"></div>
            <div class="form-group"><label>Tema</label><input type="text" name="theme" class="form-control" value="{{ $settings['theme']->content ?? 'default' }}"></div>
            <div class="form-group"><label>Varsayılan Admin Dili</label><select name="default_admin_lang" class="form-control">@foreach($languages as $lang)<option value="{{ $lang->lang }}" {{ ($settings['default_admin_lang']->content ?? 'tr') == $lang->lang ? 'selected' : '' }}>{{ $lang->name }}</option>@endforeach</select></div>
        </div></div>
        <div class="panel"><div class="panel-header">Önbellek</div><div class="panel-body">
            <div class="form-group"><label><input type="checkbox" name="cache" value="1" {{ ($settings['cache']->content ?? '0') == '1' ? 'checked' : '' }}> Önbellek Etkin</label></div>
            <div class="form-group"><label>Önbellek Süresi (dakika)</label><input type="number" name="cache_time" class="form-control" value="{{ $settings['cache_time']->content ?? '150' }}"></div>
        </div></div>
        <div class="panel"><div class="panel-header">Editör</div><div class="panel-body">
            <div class="form-group"><label>Metin Editörü</label><select name="texteditor" class="form-control"><option value="tinymce" {{ ($settings['texteditor']->content ?? 'tinymce') == 'tinymce' ? 'selected' : '' }}>TinyMCE</option><option value="ckeditor" {{ ($settings['texteditor']->content ?? '') == 'ckeditor' ? 'selected' : '' }}>CKEditor</option></select></div>
            <div class="form-group"><label>Medya Küçük Resim Boyutu</label><input type="number" name="media_thumb_size" class="form-control" value="{{ $settings['media_thumb_size']->content ?? '120' }}"></div>
        </div><div class="panel-footer"><button type="submit" class="btn btn-success">Ayarları Kaydet</button></div></div>
    </form>
@endsection
