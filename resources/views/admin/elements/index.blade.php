@extends('layouts.admin')

@section('title', 'Content Elements')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span>›</span>
        <span>Content Elements</span>
    </div>
@endsection

@section('actions')
    <a href="{{ route('admin.elements.create') }}" class="btn btn-success">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        New Element
    </a>
@endsection

@section('content')
<style>
    .elements-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }

    .element-card {
        background: white;
        border: 1px solid var(--border-color);
        padding: 20px;
        position: relative;
    }

    .element-card h3 {
        font-size: 14px;
        color: #36607D;
        margin-bottom: 5px;
    }

    .element-card .description {
        font-size: 11px;
        color: #666;
        margin-bottom: 15px;
    }

    .element-card .fields-count {
        font-size: 10px;
        color: #999;
        margin-bottom: 10px;
    }

    .element-fields {
        border-top: 1px solid #eee;
        padding-top: 10px;
    }

    .element-fields .field {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 5px 0;
        font-size: 11px;
    }

    .element-fields .field-type {
        background: var(--bg-gray-light);
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 9px;
        color: #666;
    }

    .element-card .actions {
        margin-top: 15px;
        display: flex;
        gap: 8px;
    }

    .element-card .status {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 9px;
        padding: 2px 6px;
        border-radius: 3px;
    }

    .element-card .status.active {
        background: var(--success);
        color: white;
    }

    .element-card .status.inactive {
        background: #999;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 60px;
        background: white;
        border: 2px dashed #ddd;
    }

    .empty-state h3 {
        font-size: 16px;
        color: #666;
        margin-bottom: 10px;
    }

    .empty-state p {
        font-size: 12px;
        color: #999;
        margin-bottom: 20px;
    }
</style>

<div class="panel">
    <div class="panel-header">
        <span class="panel-title">Content Element Definitions</span>
    </div>
    <div class="panel-body">
        @if($elements->count() > 0)
            <div class="elements-grid">
                @foreach($elements as $element)
                    <div class="element-card">
                        <span class="status {{ $element->active ? 'active' : 'inactive' }}">
                            {{ $element->active ? 'Active' : 'Inactive' }}
                        </span>
                        
                        <h3>{{ $element->title }}</h3>
                        <div class="description">{{ $element->description ?: 'No description' }}</div>
                        <div class="fields-count">{{ $element->fields->count() }} field(s)</div>
                        
                        @if($element->fields->count() > 0)
                            <div class="element-fields">
                                @foreach($element->fields->take(5) as $field)
                                    <div class="field">
                                        <span class="field-type">{{ strtoupper($field->type) }}</span>
                                        <span>{{ $field->label }}</span>
                                        @if($field->required)
                                            <span style="color: var(--danger);">*</span>
                                        @endif
                                        @if($field->translatable)
                                            <span style="color: var(--primary);">🌐</span>
                                        @endif
                                    </div>
                                @endforeach
                                @if($element->fields->count() > 5)
                                    <div class="field" style="color: #999;">
                                        +{{ $element->fields->count() - 5 }} more...
                                    </div>
                                @endif
                            </div>
                        @endif
                        
                        <div class="actions">
                            <a href="{{ route('admin.elements.edit', $element->id) }}" class="btn btn-sm">Edit</a>
                            <form action="{{ route('admin.elements.destroy', $element->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <h3>No Content Elements Yet</h3>
                <p>Create your first Content Element to add flexible, repeatable content blocks to your pages and articles.</p>
                <a href="{{ route('admin.elements.create') }}" class="btn btn-success">Create First Element</a>
            </div>
        @endif
    </div>
</div>

<div class="panel" style="margin-top: 20px;">
    <div class="panel-header">
        <span class="panel-title">About Content Elements</span>
    </div>
    <div class="panel-body">
        <p style="font-size: 11px; color: #666; line-height: 1.6;">
            <strong>Content Elements</strong> are like custom field groups that you can add to any page or article. 
            Each element is a collection of fields (text, images, select boxes, etc.) that can be repeated multiple times.
        </p>
        <p style="font-size: 11px; color: #666; line-height: 1.6; margin-top: 10px;">
            <strong>Examples:</strong><br>
            - <em>Team Member</em>: Name, Photo, Position, Bio<br>
            - <em>Testimonial</em>: Quote, Author, Company, Rating<br>
            - <em>Feature Box</em>: Icon, Title, Description, Link<br>
            - <em>FAQ Item</em>: Question, Answer
        </p>
    </div>
</div>
@endsection
