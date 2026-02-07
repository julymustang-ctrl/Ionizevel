@extends('layouts.admin')

@section('title', 'Media Manager')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span>›</span>
        <span>Media Manager</span>
    </div>
@endsection

@section('actions')
    <button type="button" class="btn" onclick="createFolder()">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
        </svg>
        New Folder
    </button>
    <a href="{{ route('admin.media.create') }}" class="btn btn-success">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
        </svg>
        Upload Files
    </a>
@endsection

@section('content')
<style>
    .media-manager {
        display: grid;
        grid-template-columns: 200px 1fr 250px;
        gap: 15px;
        min-height: 500px;
    }

    /* Folder Tree */
    .folder-tree {
        background: white;
        border: 1px solid var(--border-color);
        padding: 10px;
    }

    .folder-tree h4 {
        font-size: 11px;
        color: #666;
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: 1px solid #eee;
    }

    .folder-item {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 6px 8px;
        cursor: pointer;
        font-size: 11px;
        border-radius: 3px;
    }

    .folder-item:hover {
        background: var(--bg-gray-light);
    }

    .folder-item.active {
        background: var(--primary);
        color: white;
    }

    .folder-item svg {
        width: 14px;
        height: 14px;
    }

    /* Media Grid */
    .media-grid-container {
        background: white;
        border: 1px solid var(--border-color);
    }

    .media-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        border-bottom: 1px solid var(--border-color);
        background: var(--bg-gray-light);
    }

    .media-toolbar .path {
        font-size: 11px;
        color: #666;
    }

    .media-toolbar .view-toggle {
        display: flex;
        gap: 5px;
    }

    .media-toolbar .view-toggle button {
        padding: 5px 10px;
        background: white;
        border: 1px solid #ccc;
        cursor: pointer;
    }

    .media-toolbar .view-toggle button.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .media-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 10px;
        padding: 15px;
    }

    .media-item {
        border: 1px solid var(--border-color);
        cursor: pointer;
        position: relative;
        transition: all 0.15s;
    }

    .media-item:hover {
        border-color: var(--primary);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .media-item.selected {
        border-color: var(--primary);
        box-shadow: 0 0 0 2px var(--primary);
    }

    .media-item .thumb {
        height: 90px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f5f5f5;
    }

    .media-item .thumb img {
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
    }

    .media-item .info {
        padding: 6px;
        font-size: 9px;
        background: white;
        border-top: 1px solid #eee;
    }

    .media-item .info .name {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-weight: 500;
    }

    .media-item .info .size {
        color: #999;
    }

    .media-item .checkbox {
        position: absolute;
        top: 5px;
        left: 5px;
    }

    /* Drop zone */
    .drop-zone {
        border: 2px dashed #ccc;
        padding: 30px;
        text-align: center;
        background: #fafafa;
        margin: 15px;
    }

    .drop-zone.dragover {
        border-color: var(--primary);
        background: #e3f2fd;
    }

    /* Details Panel */
    .details-panel {
        background: white;
        border: 1px solid var(--border-color);
    }

    .details-panel h4 {
        font-size: 11px;
        padding: 10px 15px;
        background: var(--bg-gray-light);
        border-bottom: 1px solid #eee;
        margin: 0;
    }

    .details-content {
        padding: 15px;
    }

    .details-content .preview {
        text-align: center;
        margin-bottom: 15px;
    }

    .details-content .preview img {
        max-width: 100%;
        max-height: 150px;
    }

    .details-content .info-row {
        display: flex;
        justify-content: space-between;
        font-size: 10px;
        padding: 5px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .details-content .info-row .label {
        color: #666;
    }

    .details-content .actions {
        margin-top: 15px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
</style>

<div class="media-manager">
    <!-- Folder Tree -->
    <div class="folder-tree">
        <h4>Folders</h4>
        <div class="folder-item active" data-path="/">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            All Files
        </div>
        @php
            $folders = \App\Models\Media::select('path')
                ->whereNotNull('path')
                ->where('path', '!=', '')
                ->distinct()
                ->pluck('path')
                ->map(function($path) {
                    return dirname($path);
                })
                ->unique()
                ->filter()
                ->sort();
        @endphp
        @foreach($folders as $folder)
            <div class="folder-item" data-path="{{ $folder }}" style="padding-left: {{ (substr_count($folder, '/') + 1) * 15 }}px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
                {{ basename($folder) ?: 'uploads' }}
            </div>
        @endforeach
    </div>

    <!-- Media Grid -->
    <div class="media-grid-container">
        <div class="media-toolbar">
            <div class="path">
                <span>📁</span> <span id="currentPath">/</span> ({{ $media->total() }} files)
            </div>
            <div class="view-toggle">
                <button class="active" onclick="setView('grid')">▦</button>
                <button onclick="setView('list')">☰</button>
            </div>
        </div>

        <div class="drop-zone" id="dropZone">
            <p>Drag & drop files here to upload</p>
            <p style="font-size: 10px; color: #999;">or click "Upload Files" button</p>
        </div>

        <div class="media-grid" id="mediaGrid">
            @forelse($media as $m)
                <div class="media-item" data-id="{{ $m->id_media }}" onclick="selectMedia({{ $m->id_media }}, this)">
                    <input type="checkbox" class="checkbox" name="selected[]" value="{{ $m->id_media }}">
                    <div class="thumb">
                        @if($m->type === 'picture')
                            <img src="{{ asset($m->path) }}" alt="{{ $m->file_name }}">
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="#999">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        @endif
                    </div>
                    <div class="info">
                        <div class="name" title="{{ $m->file_name }}">{{ Str::limit($m->file_name, 15) }}</div>
                        <div class="size">{{ number_format($m->file_size / 1024, 1) }} KB</div>
                    </div>
                </div>
            @empty
                <p style="color: #999; grid-column: 1/-1; text-align: center; padding: 30px;">No files found.</p>
            @endforelse
        </div>

        <div style="padding: 15px;">
            {{ $media->links() }}
        </div>
    </div>

    <!-- Details Panel -->
    <div class="details-panel">
        <h4>File Details</h4>
        <div class="details-content" id="detailsContent">
            <p style="color: #999; font-size: 11px; text-align: center;">Select a file to view details</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let selectedMediaId = null;

    function selectMedia(id, el) {
        // Toggle selection
        document.querySelectorAll('.media-item').forEach(item => item.classList.remove('selected'));
        el.classList.add('selected');
        selectedMediaId = id;
        
        // Load details
        loadMediaDetails(id);
    }

    function loadMediaDetails(id) {
        fetch('/admin/media/' + id + '/details')
            .then(response => response.json())
            .then(data => {
                document.getElementById('detailsContent').innerHTML = `
                    <div class="preview">
                        ${data.type === 'picture' ? `<img src="${data.url}" alt="${data.name}">` : 
                          `<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="none" viewBox="0 0 24 24" stroke="#999">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                          </svg>`}
                    </div>
                    <div class="info-row"><span class="label">Name:</span><span>${data.name}</span></div>
                    <div class="info-row"><span class="label">Type:</span><span>${data.type}</span></div>
                    <div class="info-row"><span class="label">Size:</span><span>${data.sizeFormatted}</span></div>
                    <div class="info-row"><span class="label">Dimensions:</span><span>${data.dimensions || 'N/A'}</span></div>
                    <div class="info-row"><span class="label">Created:</span><span>${data.created}</span></div>
                    <div class="actions">
                        <a href="${data.url}" class="btn" target="_blank">Open Original</a>
                        <a href="/admin/media/${id}/edit" class="btn">Edit Details</a>
                        <button class="btn btn-danger" onclick="deleteMedia(${id})">Delete</button>
                    </div>
                `;
            })
            .catch(err => {
                console.error('Error loading details:', err);
            });
    }

    function deleteMedia(id) {
        if (!confirm('Are you sure you want to delete this file?')) return;
        
        fetch('/admin/media/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (response.ok) {
                window.location.reload();
            }
        });
    }

    function createFolder() {
        const name = prompt('Enter folder name:');
        if (!name) return;
        
        fetch('/admin/media/create-folder', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ name: name })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.error || 'Failed to create folder');
            }
        });
    }

    // Folder navigation
    document.querySelectorAll('.folder-item').forEach(item => {
        item.addEventListener('click', function() {
            document.querySelectorAll('.folder-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            document.getElementById('currentPath').textContent = this.dataset.path || '/';
            // In a full implementation, this would filter/reload the media grid
        });
    });

    // Drag and drop
    const dropZone = document.getElementById('dropZone');
    
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            uploadFiles(files);
        }
    });

    function uploadFiles(files) {
        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('files[]', files[i]);
        }

        fetch('/admin/media/upload-ajax', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Upload failed: ' + (data.error || 'Unknown error'));
            }
        });
    }

    function setView(type) {
        document.querySelectorAll('.view-toggle button').forEach(b => b.classList.remove('active'));
        event.target.classList.add('active');
        
        const grid = document.getElementById('mediaGrid');
        if (type === 'list') {
            grid.style.gridTemplateColumns = '1fr';
        } else {
            grid.style.gridTemplateColumns = 'repeat(auto-fill, minmax(120px, 1fr))';
        }
    }
</script>
@endpush
@endsection
