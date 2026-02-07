@extends('layouts.admin')

@section('title', 'Roles & Permissions')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span>›</span>
        <span>Roles & Permissions</span>
    </div>
@endsection

@section('actions')
    <a href="{{ route('admin.roles.create') }}" class="btn btn-success">
        + New Role
    </a>
@endsection

@section('content')
<style>
    .roles-table {
        width: 100%;
        border-collapse: collapse;
    }

    .roles-table th,
    .roles-table td {
        padding: 12px 15px;
        border: 1px solid var(--border-color);
        text-align: left;
    }

    .roles-table th {
        background: var(--bg-gray-light);
        font-weight: 600;
        font-size: 11px;
    }

    .role-level {
        display: inline-block;
        padding: 2px 8px;
        background: var(--primary);
        color: white;
        border-radius: 3px;
        font-size: 10px;
        font-weight: bold;
    }

    .role-level.admin {
        background: #dc3545;
    }

    .role-level.editor {
        background: #28a745;
    }

    .role-level.user {
        background: #6c757d;
    }

    .user-count {
        color: #666;
        font-size: 11px;
    }
</style>

<div class="panel">
    <div class="panel-header">
        <span class="panel-title">User Roles</span>
    </div>
    <div class="panel-body">
        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom: 15px;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger" style="margin-bottom: 15px;">{{ session('error') }}</div>
        @endif

        <table class="roles-table">
            <thead>
                <tr>
                    <th>Role Name</th>
                    <th>Code</th>
                    <th>Level</th>
                    <th>Users</th>
                    <th>Description</th>
                    <th width="150">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                    <tr>
                        <td>
                            <strong>{{ $role->role_name }}</strong>
                        </td>
                        <td>
                            <code style="font-size: 10px; background: #f5f5f5; padding: 2px 5px;">{{ $role->role_code }}</code>
                        </td>
                        <td>
                            <span class="role-level {{ $role->role_level >= 10000 ? 'admin' : ($role->role_level >= 500 ? 'editor' : 'user') }}">
                                {{ $role->role_level }}
                            </span>
                        </td>
                        <td>
                            <span class="user-count">{{ $role->users_count }} user(s)</span>
                        </td>
                        <td style="font-size: 11px; color: #666;">
                            {{ $role->role_description }}
                        </td>
                        <td>
                            <a href="{{ route('admin.roles.edit', $role->id_role) }}" class="btn btn-sm">
                                Edit & Permissions
                            </a>
                            @if($role->role_level < 10000 && $role->users_count == 0)
                                <form action="{{ route('admin.roles.destroy', $role->id_role) }}" method="POST" style="display: inline;"
                                      onsubmit="return confirm('Delete this role?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">×</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
