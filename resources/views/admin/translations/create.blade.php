@extends('layouts.admin')

@section('title', 'New Translation')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span>›</span>
        <a href="{{ route('admin.translations.index') }}">Translations</a>
        <span>›</span>
        <span>New</span>
    </div>
@endsection

@section('content')
<div class="panel">
    <div class="panel-header">
        <span class="panel-title">New Translation</span>
    </div>
    <div class="panel-body">
        <form action="{{ route('admin.translations.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label>Group</label>
                <select name="group" class="form-control" id="groupSelect">
                    <option value="">-- New Group --</option>
                    @foreach($groups as $g)
                        <option value="{{ $g }}">{{ $g }}</option>
                    @endforeach
                </select>
                <input type="text" name="group" class="form-control" id="groupInput" 
                       placeholder="Enter group name (e.g., messages, validation)" style="margin-top: 5px;">
            </div>

            <div class="form-group">
                <label>Key</label>
                <input type="text" name="key" class="form-control" required 
                       placeholder="e.g., welcome_message">
            </div>

            <hr>
            <h4 style="font-size: 12px; margin-bottom: 15px;">Values</h4>

            @foreach($languages as $lang)
                <div class="form-group">
                    <label>{{ strtoupper($lang->lang) }} - {{ $lang->name }}</label>
                    <textarea name="value_{{ $lang->lang }}" class="form-control" rows="2"></textarea>
                </div>
            @endforeach

            <div class="form-actions">
                <button type="submit" class="btn btn-success">Save Translation</button>
                <a href="{{ route('admin.translations.index') }}" class="btn">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('groupSelect').addEventListener('change', function() {
        const input = document.getElementById('groupInput');
        if (this.value) {
            input.style.display = 'none';
            input.name = '';
        } else {
            input.style.display = 'block';
            input.name = 'group';
        }
    });
</script>
@endpush
@endsection
