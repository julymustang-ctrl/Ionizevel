@extends('layouts.admin')

@section('title', 'Edit Role - ' . $role->role_name)

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span>›</span>
        <a href="{{ route('admin.roles.index') }}">Roles</a>
        <span>›</span>
        <span>{{ $role->role_name }}</span>
    </div>
@endsection

@section('content')
<style>
    .role-editor {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 20px;
    }

    .permission-matrix {
        width: 100%;
        border-collapse: collapse;
        font-size: 11px;
    }

    .permission-matrix th,
    .permission-matrix td {
        padding: 10px;
        border: 1px solid var(--border-color);
        text-align: center;
    }

    .permission-matrix th {
        background: var(--bg-gray-light);
        font-weight: 600;
    }

    .permission-matrix th.resource {
        text-align: left;
        width: 150px;
    }

    .permission-matrix td.resource {
        text-align: left;
        font-weight: 500;
    }

    .permission-matrix input[type="checkbox"] {
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    .permission-matrix tr:hover {
        background: #f9f9f9;
    }

    .all-toggle {
        font-size: 9px;
        color: var(--primary);
        cursor: pointer;
        margin-left: 5px;
    }
</style>

<form action="{{ route('admin.roles.update', $role->id_role) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="role-editor">
        <!-- Role Info -->
        <div class="panel">
            <div class="panel-header">
                <span class="panel-title">Role Information</span>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label>Role Name</label>
                    <input type="text" name="role_name" class="form-control" value="{{ $role->role_name }}" required>
                </div>

                <div class="form-group">
                    <label>Role Code</label>
                    <input type="text" class="form-control" value="{{ $role->role_code }}" disabled>
                    <small style="color: #999;">Code cannot be changed</small>
                </div>

                <div class="form-group">
                    <label>Level (0-10000)</label>
                    <input type="number" name="role_level" class="form-control" 
                           value="{{ $role->role_level }}" min="0" max="10000" required>
                    <small style="color: #999;">Higher level = more authority</small>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="role_description" class="form-control" rows="3">{{ $role->role_description }}</textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-success" style="width: 100%;">Save Role</button>
                </div>
            </div>
        </div>

        <!-- Permission Matrix -->
        <div class="panel">
            <div class="panel-header">
                <span class="panel-title">Permission Matrix</span>
            </div>
            <div class="panel-body">
                <table class="permission-matrix">
                    <thead>
                        <tr>
                            <th class="resource">Resource</th>
                            @php
                                $allActions = [];
                                foreach ($permissions as $resource => $data) {
                                    foreach ($data['actions'] as $action) {
                                        if (!in_array($action, $allActions)) {
                                            $allActions[] = $action;
                                        }
                                    }
                                }
                            @endphp
                            @foreach($allActions as $action)
                                <th>
                                    {{ ucfirst($action) }}
                                    <br><span class="all-toggle" onclick="toggleColumn('{{ $action }}')">[all]</span>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permissions as $resource => $data)
                            <tr>
                                <td class="resource">{{ $data['label'] }}</td>
                                @foreach($allActions as $action)
                                    <td>
                                        @if(in_array($action, $data['actions']))
                                            @php
                                                $ruleKey = "{$resource}:{$action}";
                                                $hasPermission = isset($roleRules[$ruleKey]) || isset($roleRules[$resource]);
                                            @endphp
                                            <input type="checkbox" 
                                                   name="permissions[{{ $resource }}][{{ $action }}]" 
                                                   value="1"
                                                   class="perm-{{ $action }}"
                                                   {{ $hasPermission ? 'checked' : '' }}>
                                        @else
                                            <span style="color: #ccc;">-</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="margin-top: 15px; display: flex; gap: 10px;">
                    <button type="button" class="btn" onclick="selectAll()">Select All</button>
                    <button type="button" class="btn" onclick="deselectAll()">Deselect All</button>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    function toggleColumn(action) {
        const checkboxes = document.querySelectorAll('.perm-' + action);
        const anyUnchecked = Array.from(checkboxes).some(cb => !cb.checked);
        checkboxes.forEach(cb => cb.checked = anyUnchecked);
    }

    function selectAll() {
        document.querySelectorAll('.permission-matrix input[type="checkbox"]').forEach(cb => cb.checked = true);
    }

    function deselectAll() {
        document.querySelectorAll('.permission-matrix input[type="checkbox"]').forEach(cb => cb.checked = false);
    }
</script>
@endpush
@endsection
