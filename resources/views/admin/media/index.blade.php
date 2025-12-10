@extends('layouts.admin')
@section('title', 'Medya')
@section('content')
    <div class="page-header"><h2>Medya Kütüphanesi</h2><div class="subtitle">{{ $media->total() }} dosya</div></div>
    <div class="toolbar"><div class="toolbar-left"></div><div class="toolbar-right"><a href="{{ route('admin.media.create') }}" class="btn btn-success">+ Dosya Yükle</a></div></div>
    <div class="panel"><div class="panel-body" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:15px;">
        @forelse($media as $m)
        <div style="border:1px solid #ddd;padding:10px;text-align:center;">
            @if($m->type === 'picture')<img src="{{ asset($m->path) }}" style="max-width:100%;height:100px;object-fit:cover;">@else<div style="height:100px;background:#f5f5f5;display:flex;align-items:center;justify-content:center;color:#999;">{{ $m->type }}</div>@endif
            <div style="margin-top:8px;font-size:10px;overflow:hidden;text-overflow:ellipsis;">{{ $m->file_name }}</div>
            <div style="margin-top:5px;"><a href="{{ route('admin.media.edit', $m->id_media) }}" class="btn" style="font-size:10px;">Düzenle</a></div>
        </div>
        @empty<p style="color:#999;">Henüz dosya yok.</p>@endforelse
    </div></div>{{ $media->links() }}
@endsection
