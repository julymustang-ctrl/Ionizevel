@extends('layouts.admin')

@section('title', 'New Role')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span>›</span>
        <a href="{{ route('admin.roles.index') }}">Roles</a>
        <span>›</span>
        <span>New</span>
    </div>
@endsection

@section('content')
<div class="panel" style="max-width: 500px;">
    <div class="panel-header">
        <span class="panel-title">Create New Role</span>
    </div>
    <div class="panel-body">
        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Role Name *</label>
                <input type="text" name="role_name" class="form-control" required placeholder="e.g., Editor">
            </div>

            <div class="form-group">
                <label>Role Code *</label>
                <input type="text" name="role_code" class="form-control" required placeholder="e.g., editor">
                <small style="color: #999;">Unique identifier, lowercase, no spaces</small>
            </div>

            <div class="form-group">
                <label>Level (0-10000) *</label>
                <input type="number" name="role_level" class="form-control" value="100" min="0" max="10000" required>
                <small style="color: #999;">Higher level = more authority. Admin: 10000, Editor: 500, User: 100</small>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="role_description" class="form-control" rows="3" placeholder="Role description..."></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-success">Create Role</button>
                <a href="{{ route('admin.roles.index') }}" class="btn">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
