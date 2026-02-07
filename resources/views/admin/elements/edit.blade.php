@extends('layouts.admin')

@section('title', 'Edit: ' . $element->title)

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span>›</span>
        <a href="{{ route('admin.elements.index') }}">Content Elements</a>
        <span>›</span>
        <span>Edit: {{ $element->title }}</span>
    </div>
@endsection

@section('actions')
    <button type="submit" form="elementForm" class="btn btn-success">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        Save Changes
    </button>
@endsection

@section('content')
<style>
    .field-builder {
        border: 1px solid var(--border-color);
        background: var(--bg-gray-light);
        padding: 15px;
        margin-top: 20px;
    }

    .field-item {
        background: white;
        border: 1px solid var(--border-color);
        padding: 15px;
        margin-bottom: 10px;
        position: relative;
    }

    .field-item .field-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .field-item .field-number {
        font-weight: bold;
        color: var(--primary);
    }

    .field-item .remove-field {
        color: var(--danger);
        cursor: pointer;
        font-size: 18px;
    }

    .field-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 10px;
    }

    .field-row-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .field-options {
        display: flex;
        gap: 15px;
    }

    .field-options label {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
    }
</style>

<form id="elementForm" action="{{ route('admin.elements.update', $element->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">Element Details</span>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label>Internal Name <span class="required">*</span></label>
                <input type="text" name="name" class="form-control" required value="{{ $element->name }}" pattern="[a-z_]+" title="Lowercase letters and underscores only">
            </div>

            <div class="form-group">
                <label>Display Title <span class="required">*</span></label>
                <input type="text" name="title" class="form-control" required value="{{ $element->title }}">
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3">{{ $element->description }}</textarea>
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="active" {{ $element->active ? 'checked' : '' }}>
                    Active
                </label>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">Fields</span>
            <button type="button" class="btn btn-sm" onclick="addField()">+ Add Field</button>
        </div>
        <div class="panel-body">
            <div class="field-builder">
                <div id="fieldsContainer">
                    @foreach($element->fields as $index => $field)
                        <div class="field-item" data-index="{{ $index }}">
                            <div class="field-header">
                                <span class="field-number">Field #{{ $index + 1 }}</span>
                                <span class="remove-field" onclick="removeField({{ $index }})">×</span>
                            </div>
                            
                            <div class="field-row">
                                <div class="form-group">
                                    <label>Field Name <span class="required">*</span></label>
                                    <input type="text" name="fields[{{ $index }}][name]" class="form-control" required value="{{ $field->name }}" pattern="[a-z_]+">
                                </div>
                                <div class="form-group">
                                    <label>Label <span class="required">*</span></label>
                                    <input type="text" name="fields[{{ $index }}][label]" class="form-control" required value="{{ $field->label }}">
                                </div>
                                <div class="form-group">
                                    <label>Type <span class="required">*</span></label>
                                    <select name="fields[{{ $index }}][type]" class="form-control" required>
                                        @foreach($fieldTypes as $typeKey => $typeLabel)
                                            <option value="{{ $typeKey }}" {{ $field->type === $typeKey ? 'selected' : '' }}>{{ $typeLabel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="field-row-2">
                                <div class="form-group">
                                    <label>Default Value</label>
                                    <input type="text" name="fields[{{ $index }}][default_value]" class="form-control" value="{{ $field->default_value }}">
                                </div>
                                <div class="form-group">
                                    <label>Options (JSON for select)</label>
                                    <input type="text" name="fields[{{ $index }}][options]" class="form-control" value="{{ $field->options ? json_encode($field->options) : '' }}">
                                </div>
                            </div>
                            
                            <div class="field-options">
                                <label>
                                    <input type="checkbox" name="fields[{{ $index }}][required]" {{ $field->required ? 'checked' : '' }}>
                                    Required
                                </label>
                                <label>
                                    <input type="checkbox" name="fields[{{ $index }}][translatable]" {{ $field->translatable ? 'checked' : '' }}>
                                    Translatable
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn" onclick="addField()">+ Add Another Field</button>
            </div>
        </div>
    </div>
</form>

<script>
    let fieldIndex = {{ $element->fields->count() }};

    const fieldTypes = @json($fieldTypes);

    function addField() {
        const container = document.getElementById('fieldsContainer');
        const typeOptions = Object.entries(fieldTypes).map(([k, v]) => `<option value="${k}">${v}</option>`).join('');
        
        const fieldHtml = `
            <div class="field-item" data-index="${fieldIndex}">
                <div class="field-header">
                    <span class="field-number">Field #${document.querySelectorAll('.field-item').length + 1}</span>
                    <span class="remove-field" onclick="removeField(${fieldIndex})">×</span>
                </div>
                
                <div class="field-row">
                    <div class="form-group">
                        <label>Field Name <span class="required">*</span></label>
                        <input type="text" name="fields[${fieldIndex}][name]" class="form-control" required placeholder="e.g., first_name" pattern="[a-z_]+">
                    </div>
                    <div class="form-group">
                        <label>Label <span class="required">*</span></label>
                        <input type="text" name="fields[${fieldIndex}][label]" class="form-control" required placeholder="e.g., First Name">
                    </div>
                    <div class="form-group">
                        <label>Type <span class="required">*</span></label>
                        <select name="fields[${fieldIndex}][type]" class="form-control" required>
                            ${typeOptions}
                        </select>
                    </div>
                </div>
                
                <div class="field-row-2">
                    <div class="form-group">
                        <label>Default Value</label>
                        <input type="text" name="fields[${fieldIndex}][default_value]" class="form-control" placeholder="Optional default value">
                    </div>
                    <div class="form-group">
                        <label>Options (JSON for select)</label>
                        <input type="text" name="fields[${fieldIndex}][options]" class="form-control" placeholder='{"opt1": "Option 1", "opt2": "Option 2"}'>
                    </div>
                </div>
                
                <div class="field-options">
                    <label>
                        <input type="checkbox" name="fields[${fieldIndex}][required]">
                        Required
                    </label>
                    <label>
                        <input type="checkbox" name="fields[${fieldIndex}][translatable]">
                        Translatable
                    </label>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', fieldHtml);
        fieldIndex++;
    }

    function removeField(index) {
        const field = document.querySelector(`.field-item[data-index="${index}"]`);
        if (field) {
            field.remove();
        }
    }
</script>
@endsection
