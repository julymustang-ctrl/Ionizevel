@extends('layouts.admin')

@section('title', 'Translations')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span>›</span>
        <span>Translations</span>
    </div>
@endsection

@section('actions')
    <button type="button" class="btn" onclick="location.href='{{ route('admin.translations.import') }}'">
        Import from Lang Files
    </button>
    <a href="{{ route('admin.translations.create') }}" class="btn btn-success">
        + New Translation
    </a>
@endsection

@section('content')
<style>
    .translation-filters {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
    }

    .translation-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11px;
    }

    .translation-table th,
    .translation-table td {
        padding: 10px;
        border: 1px solid var(--border-color);
        text-align: left;
    }

    .translation-table th {
        background: var(--bg-gray-light);
        font-weight: 600;
    }

    .translation-table .key-cell {
        font-family: monospace;
        background: #f8f8f8;
    }

    .translation-table .editable-cell {
        position: relative;
    }

    .translation-table .editable-cell input {
        width: 100%;
        border: 1px solid transparent;
        padding: 5px;
        background: transparent;
        font-size: 11px;
    }

    .translation-table .editable-cell input:focus {
        border-color: var(--primary);
        background: white;
        outline: none;
    }

    .translation-table .editable-cell.saving {
        background: #fff3cd;
    }

    .translation-table .editable-cell.saved {
        background: #d4edda;
    }

    .group-badge {
        display: inline-block;
        padding: 2px 6px;
        background: var(--primary);
        color: white;
        border-radius: 3px;
        font-size: 9px;
    }
</style>

<div class="panel">
    <div class="panel-header">
        <span class="panel-title">Interface Translations</span>
    </div>
    <div class="panel-body">
        <form class="translation-filters">
            <input type="text" name="search" class="form-control" placeholder="Search key or value..." 
                   value="{{ $search }}" style="width: 300px;">
            <select name="group" class="form-control" style="width: 150px;">
                <option value="all">All Groups</option>
                @foreach($groups as $g)
                    <option value="{{ $g }}" {{ $group === $g ? 'selected' : '' }}>{{ $g }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn">Filter</button>
        </form>

        <table class="translation-table">
            <thead>
                <tr>
                    <th style="width: 80px;">Group</th>
                    <th style="width: 200px;">Key</th>
                    @foreach($languages as $lang)
                        <th>{{ strtoupper($lang->lang) }}</th>
                    @endforeach
                    <th style="width: 80px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Group translations by key
                    $grouped = $translations->groupBy(function($t) {
                        return $t->group . '|' . $t->key;
                    });
                @endphp
                @forelse($translations->unique(function($t) { return $t->group . '|' . $t->key; }) as $translation)
                    <tr>
                        <td><span class="group-badge">{{ $translation->group }}</span></td>
                        <td class="key-cell">{{ $translation->key }}</td>
                        @foreach($languages as $lang)
                            @php
                                $langTranslation = \App\Models\Translation::where('key', $translation->key)
                                    ->where('group', $translation->group)
                                    ->where('lang', $lang->lang)
                                    ->first();
                            @endphp
                            <td class="editable-cell">
                                <input type="text" 
                                       value="{{ $langTranslation->value ?? '' }}"
                                       data-key="{{ $translation->key }}"
                                       data-group="{{ $translation->group }}"
                                       data-lang="{{ $lang->lang }}"
                                       onchange="saveTranslation(this)">
                            </td>
                        @endforeach
                        <td>
                            <a href="{{ route('admin.translations.edit', $translation->id) }}" class="btn btn-sm">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 3 + $languages->count() }}" style="text-align: center; color: #999;">
                            No translations found. <a href="{{ route('admin.translations.create') }}">Create first translation</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 15px;">
            {{ $translations->appends(['search' => $search, 'group' => $group])->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    function saveTranslation(input) {
        const cell = input.closest('.editable-cell');
        cell.classList.add('saving');
        
        fetch('{{ route('admin.translations.update-ajax') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                key: input.dataset.key,
                group: input.dataset.group,
                lang: input.dataset.lang,
                value: input.value
            })
        })
        .then(response => response.json())
        .then(data => {
            cell.classList.remove('saving');
            if (data.success) {
                cell.classList.add('saved');
                setTimeout(() => cell.classList.remove('saved'), 1000);
            }
        })
        .catch(err => {
            cell.classList.remove('saving');
            alert('Error saving translation');
        });
    }
</script>
@endpush
@endsection
