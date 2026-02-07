@extends('layouts.admin')

@section('title', 'Kategoriler')

@section('breadcrumb')
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span>›</span>
        <span>Categories</span>
    </div>
@endsection

@section('actions')
    <a href="{{ route('admin.categories.create') }}" class="btn btn-success">
        + Yeni Kategori
    </a>
@endsection

@section('content')
<style>
    .category-tree {
        background: white;
        border: 1px solid var(--border-color);
    }

    .category-tree .header {
        display: grid;
        grid-template-columns: 40px 1fr 80px 80px 120px;
        padding: 10px 15px;
        background: var(--bg-gray-light);
        border-bottom: 1px solid var(--border-color);
        font-weight: 600;
        font-size: 11px;
    }

    .category-item {
        display: grid;
        grid-template-columns: 40px 1fr 80px 80px 120px;
        padding: 10px 15px;
        border-bottom: 1px solid #f0f0f0;
        align-items: center;
        transition: background 0.15s;
    }

    .category-item:hover {
        background: #f9f9f9;
    }

    .category-item.depth-1 {
        padding-left: 35px;
    }

    .category-item.depth-2 {
        padding-left: 55px;
    }

    .category-item.depth-3 {
        padding-left: 75px;
    }

    .category-item .drag-handle {
        cursor: grab;
        color: #999;
    }

    .category-item .drag-handle:active {
        cursor: grabbing;
    }

    .category-item .name {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .category-item .name .icon {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--primary);
        color: white;
        border-radius: 4px;
        font-size: 12px;
    }

    .category-item .name a {
        font-weight: 500;
    }

    .category-item .article-count {
        font-size: 11px;
        color: #666;
    }

    .category-item .ordering {
        font-size: 11px;
        color: #999;
    }

    .category-item .actions {
        display: flex;
        gap: 5px;
    }

    .add-child-btn {
        font-size: 10px;
        padding: 3px 8px;
    }

    .empty-state {
        padding: 40px;
        text-align: center;
        color: #999;
    }

    /* Icon picker */
    .icon-grid {
        display: grid;
        grid-template-columns: repeat(8, 1fr);
        gap: 5px;
        max-height: 200px;
        overflow-y: auto;
    }

    .icon-grid .icon-option {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #ddd;
        cursor: pointer;
        border-radius: 4px;
    }

    .icon-grid .icon-option:hover {
        border-color: var(--primary);
        background: #e3f2fd;
    }
</style>

<div class="category-tree">
    <div class="header">
        <span></span>
        <span>Name</span>
        <span>Articles</span>
        <span>Order</span>
        <span>Actions</span>
    </div>

    <div id="categoryList">
        @php
            function renderCategory($category, $depth = 0) {
                $translation = $category->translations->first();
                $title = $translation?->title ?? $category->name;
                $articleCount = $category->articles()->count();
                
                $icons = ['📁', '🏷️', '📂', '📦', '🗂️', '📋', '🔖', '⭐'];
                $icon = $category->icon ?? $icons[$category->id_category % count($icons)];
                
                echo '<div class="category-item depth-' . $depth . '" data-id="' . $category->id_category . '" data-parent="' . ($category->id_parent ?? 0) . '">';
                echo '<span class="drag-handle">☰</span>';
                echo '<div class="name">';
                echo '<span class="icon">' . $icon . '</span>';
                echo '<a href="' . route('admin.categories.edit', $category->id_category) . '">' . e($title) . '</a>';
                echo '</div>';
                echo '<span class="article-count">' . $articleCount . '</span>';
                echo '<span class="ordering">' . $category->ordering . '</span>';
                echo '<div class="actions">';
                echo '<a href="' . route('admin.categories.create') . '?parent=' . $category->id_category . '" class="btn btn-sm add-child-btn">+ Child</a>';
                echo '<a href="' . route('admin.categories.edit', $category->id_category) . '" class="btn btn-sm">Edit</a>';
                echo '</div>';
                echo '</div>';
                
                foreach ($category->children as $child) {
                    renderCategory($child, $depth + 1);
                }
            }
        @endphp

        @forelse($categories as $category)
            @php renderCategory($category); @endphp
        @empty
            <div class="empty-state">
                <p>Henüz kategori yok.</p>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-success">İlk kategoriyi oluştur</a>
            </div>
        @endforelse
    </div>
</div>

@if(session('success'))
    <script>alert('{{ session('success') }}');</script>
@endif

@push('scripts')
<script>
    // Drag and drop category reordering
    if (typeof Sortable !== 'undefined') {
        new Sortable(document.getElementById('categoryList'), {
            animation: 150,
            handle: '.drag-handle',
            onEnd: function(evt) {
                const items = document.querySelectorAll('.category-item');
                const order = [];
                items.forEach((item, index) => {
                    order.push({
                        id: item.dataset.id,
                        ordering: index + 1
                    });
                });
                
                fetch('{{ route('admin.categories.reorder') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ order: order })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        alert('Failed to save order');
                    }
                });
            }
        });
    }
</script>
@endpush
@endsection
