@extends('layouts.admin')

@section('title', 'Edit Template: ' . $fileName)

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span>›</span>
        <a href="{{ route('admin.theme.index') }}">Theme</a>
        <span>›</span>
        <span>Edit: {{ $fileName }}</span>
    </div>
@endsection

@section('actions')
    <a href="{{ route('admin.theme.index') }}" class="btn">
        ← Back to Theme
    </a>
    <button type="submit" form="editForm" class="btn btn-success">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        Save File
    </button>
@endsection

@section('content')
<style>
    .editor-container {
        background: white;
        border: 1px solid var(--border-color);
    }

    .editor-header {
        padding: 12px 15px;
        background: var(--bg-gray-light);
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .editor-header h3 {
        margin: 0;
        font-size: 13px;
        color: #36607D;
        font-family: monospace;
    }

    .editor-meta {
        font-size: 10px;
        color: #888;
    }

    .code-editor {
        width: 100%;
        min-height: 600px;
        font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
        font-size: 13px;
        line-height: 1.5;
        padding: 15px;
        border: none;
        resize: vertical;
        tab-size: 4;
        background: #1e1e1e;
        color: #d4d4d4;
    }

    .code-editor:focus {
        outline: none;
    }

    .editor-footer {
        padding: 10px 15px;
        background: var(--bg-gray-light);
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .editor-info {
        font-size: 10px;
        color: #666;
    }
</style>

<form id="editForm" action="{{ route('admin.theme.save-file') }}" method="POST">
    @csrf
    <input type="hidden" name="file_path" value="{{ $filePath }}">

    <div class="editor-container">
        <div class="editor-header">
            <h3>{{ $filePath }}</h3>
            <div class="editor-meta">
                Theme: <strong>{{ $activeTheme }}</strong>
            </div>
        </div>

        <textarea name="content" class="code-editor" spellcheck="false">{{ $content }}</textarea>

        <div class="editor-footer">
            <div class="editor-info">
                Press Ctrl+S to save (via form submit)
            </div>
            <div>
                <button type="submit" class="btn btn-success">Save Changes</button>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    // Keyboard shortcut for saving
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            document.getElementById('editForm').submit();
        }
    });

    // Tab key support in textarea
    document.querySelector('.code-editor').addEventListener('keydown', function(e) {
        if (e.key === 'Tab') {
            e.preventDefault();
            const start = this.selectionStart;
            const end = this.selectionEnd;
            this.value = this.value.substring(0, start) + '    ' + this.value.substring(end);
            this.selectionStart = this.selectionEnd = start + 4;
        }
    });
</script>
@endpush
@endsection
