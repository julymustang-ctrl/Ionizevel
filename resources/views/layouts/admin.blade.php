<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | Ionizevel CMS</title>

    <!-- TinyMCE CDN -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <!-- SortableJS for drag-drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <style>
        /* Reset & Base */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            /* Ionize Color Palette */
            --primary: #098ED1;
            --primary-dark: #0973B6;
            --topbar-bg: #3c4049;
            --sidebar-bg: #2a2d33;
            --sidebar-dark: #1e2127;
            --sidebar-hover: #353c43;
            --text-light: #c2d0e0;
            --text-white: #ffffff;
            --text-muted: #94aec9;
            --bg-main: #f2f2f2;
            --bg-gray-light: #f8f8f8;
            --bg-white: #ffffff;
            --border-color: #d5d9dc;
            --success: #8AC05E;
            --success-dark: #70b54b;
            --warning: #E3B83A;
            --danger: #B63C1A;
            --danger-dark: #901A00;
            --info: #5F95C1;
        }

        body {
            font-family: "Segoe UI", "lucida grande", tahoma, verdana, arial, sans-serif;
            font-size: 12px;
            background: var(--bg-main);
            min-height: 100vh;
            color: #333;
        }

        a { color: var(--primary); text-decoration: none; }
        a:hover { color: var(--primary-dark); text-decoration: underline; }

        /* ============================================
           TOP MENU BAR - Ionize Style
           ============================================ */
        .main-topbar {
            background: var(--topbar-bg);
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 15px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1001;
        }

        .topbar-brand {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .topbar-brand h1 {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-white);
        }

        .topbar-brand h1 span { color: var(--primary); }

        /* Top Menu Items */
        .top-menu {
            display: flex;
            gap: 0;
        }

        .top-menu-item {
            position: relative;
            padding: 10px 18px;
            color: var(--text-light);
            text-decoration: none;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s;
        }

        .top-menu-item:hover,
        .top-menu-item.active {
            background: rgba(255,255,255,0.1);
            color: var(--text-white);
            text-decoration: none;
        }

        .top-menu-item svg {
            width: 14px;
            height: 14px;
            margin-right: 6px;
            vertical-align: middle;
        }

        /* Dropdown */
        .top-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            min-width: 200px;
            background: var(--sidebar-bg);
            border: 1px solid rgba(255,255,255,0.1);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            z-index: 1002;
        }

        .top-menu-item:hover .top-dropdown {
            display: block;
        }

        .top-dropdown a {
            display: block;
            padding: 10px 15px;
            color: var(--text-light);
            text-decoration: none;
            font-size: 11px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .top-dropdown a:hover {
            background: var(--sidebar-hover);
            color: var(--text-white);
            text-decoration: none;
        }

        .top-dropdown a svg {
            width: 14px;
            height: 14px;
            margin-right: 8px;
            vertical-align: middle;
            opacity: 0.7;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-light);
            font-size: 11px;
        }

        .topbar-user-avatar {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 600;
        }

        .topbar-logout {
            padding: 5px 12px;
            background: var(--danger);
            color: white;
            border: none;
            font-size: 11px;
            cursor: pointer;
        }

        .topbar-logout:hover {
            background: var(--danger-dark);
        }

        /* ============================================
           LEFT SIDEBAR - Page Tree
           ============================================ */
        .sidebar {
            position: fixed;
            left: 0;
            top: 40px;
            width: 240px;
            height: calc(100vh - 40px);
            background: var(--sidebar-bg);
            color: var(--text-light);
            overflow-y: auto;
            z-index: 1000;
            border-right: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-section {
            padding: 10px 12px 5px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-section:first-child { border-top: none; }

        /* ============================================
           ACCORDION SIDEBAR - Ionize Style
           ============================================ */
        .accordion-group {
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .accordion-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 12px;
            background: var(--sidebar-dark);
            cursor: pointer;
            transition: all 0.15s;
            user-select: none;
        }

        .accordion-header:hover {
            background: var(--sidebar-hover);
        }

        .accordion-header.active {
            background: var(--sidebar-hover);
            border-left: 3px solid var(--primary);
        }

        .accordion-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-light);
        }

        .accordion-title svg {
            width: 14px;
            height: 14px;
            opacity: 0.7;
        }

        .accordion-toggle {
            width: 16px;
            height: 16px;
            transition: transform 0.2s;
            opacity: 0.6;
        }

        .accordion-header.open .accordion-toggle {
            transform: rotate(90deg);
        }

        .accordion-content {
            display: none;
            padding: 0;
            background: rgba(0,0,0,0.1);
        }

        .accordion-content.open {
            display: block;
        }

        /* Quick Add Button */
        .quick-add-btn {
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary);
            border: none;
            border-radius: 3px;
            color: white;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.15s;
            line-height: 1;
        }

        .quick-add-btn:hover {
            background: var(--primary-dark);
            transform: scale(1.1);
        }

        /* Sidebar Quick Links */
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px 8px 20px;
            color: var(--text-light);
            text-decoration: none;
            font-size: 11px;
            transition: all 0.15s;
            border-left: 3px solid transparent;
        }

        .sidebar-link:hover {
            background: var(--sidebar-hover);
            border-left-color: var(--primary);
            color: var(--text-white);
            text-decoration: none;
        }

        .sidebar-link.active {
            background: var(--sidebar-hover);
            border-left-color: var(--success);
            color: var(--text-white);
        }

        .sidebar-link svg {
            width: 14px;
            height: 14px;
            opacity: 0.7;
        }

        .sidebar-link .badge {
            margin-left: auto;
            background: rgba(255,255,255,0.1);
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 9px;
        }

        /* Page Tree */
        .page-tree {
            padding: 5px 0;
        }

        .tree-item {
            display: flex;
            align-items: center;
            padding: 6px 12px;
            color: var(--text-light);
            text-decoration: none;
            font-size: 11px;
            cursor: pointer;
            transition: all 0.15s;
            border-left: 3px solid transparent;
        }

        .tree-item:hover {
            background: var(--sidebar-hover);
            border-left-color: var(--primary);
            color: var(--text-white);
            text-decoration: none;
        }

        .tree-item.active {
            background: var(--sidebar-hover);
            border-left-color: var(--success);
            color: var(--text-white);
        }

        .tree-item .toggle {
            width: 16px;
            height: 16px;
            margin-right: 5px;
            text-align: center;
            font-size: 10px;
            cursor: pointer;
        }

        .tree-item .icon {
            width: 14px;
            height: 14px;
            margin-right: 6px;
            opacity: 0.7;
        }

        .tree-item.has-children { font-weight: 500; }

        .tree-children {
            padding-left: 15px;
            display: none;
        }

        .tree-children.open { display: block; }

        .tree-item .status {
            margin-left: auto;
            font-size: 9px;
            padding: 2px 5px;
            border-radius: 3px;
        }

        .tree-item .status.online { background: var(--success); color: white; }
        .tree-item .status.offline { background: #666; color: white; }
        .tree-item .status.home { background: var(--warning); color: #333; }

        /* Drag handle */
        .tree-item .drag-handle {
            cursor: grab;
            opacity: 0.5;
            padding: 2px;
        }
        .tree-item .drag-handle:hover { opacity: 1; }
        .tree-item.dragging { opacity: 0.5; background: var(--primary); }

        /* Context Menu */
        .context-menu {
            position: fixed;
            background: white;
            border: 1px solid #ccc;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-radius: 4px;
            min-width: 180px;
            z-index: 10000;
            display: none;
            font-size: 11px;
        }

        .context-menu.show { display: block; }

        .context-menu-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            cursor: pointer;
            color: #333;
            transition: background 0.1s;
        }

        .context-menu-item:hover {
            background: var(--bg-gray-light);
        }

        .context-menu-item svg {
            width: 14px;
            height: 14px;
            opacity: 0.7;
        }

        .context-menu-item.danger { color: var(--danger); }
        .context-menu-item.danger:hover { background: #fee; }

        .context-menu-divider {
            height: 1px;
            background: #eee;
            margin: 4px 0;
        }

        /* Menu Selector */
        .menu-selector {
            padding: 10px 12px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .menu-selector select {
            width: 100%;
            padding: 6px 8px;
            background: var(--sidebar-dark);
            border: 1px solid rgba(255,255,255,0.2);
            color: var(--text-light);
            font-size: 11px;
        }

        /* ============================================
           MAIN CONTENT AREA
           ============================================ */
        .main-content {
            margin-left: 240px;
            margin-top: 40px;
            min-height: calc(100vh - 40px);
            padding-bottom: 30px; /* Space for status bar */
        }

        /* ============================================
           TAB BAR SYSTEM - Multi-Document Editing
           ============================================ */
        .tab-bar {
            display: flex;
            align-items: center;
            background: var(--bg-gray-light);
            border-bottom: 1px solid var(--border-color);
            overflow-x: auto;
            padding: 0 5px;
        }

        .tab-bar:empty {
            display: none;
        }

        .tab-item {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            background: var(--bg-white);
            border: 1px solid var(--border-color);
            border-bottom: none;
            border-radius: 4px 4px 0 0;
            margin-right: 2px;
            cursor: pointer;
            font-size: 11px;
            color: #666;
            white-space: nowrap;
            transition: all 0.15s;
            max-width: 180px;
        }

        .tab-item:hover {
            background: #fff;
            color: var(--primary);
        }

        .tab-item.active {
            background: #fff;
            color: var(--primary);
            border-bottom-color: #fff;
            font-weight: 500;
            position: relative;
        }

        .tab-item.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--primary);
        }

        .tab-item .tab-icon {
            width: 12px;
            height: 12px;
            opacity: 0.6;
        }

        .tab-item .tab-title {
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 120px;
        }

        .tab-item .tab-close {
            width: 14px;
            height: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 10px;
            color: #999;
            transition: all 0.15s;
        }

        .tab-item .tab-close:hover {
            background: var(--danger);
            color: white;
        }

        .tab-item.modified .tab-title::after {
            content: ' •';
            color: var(--warning);
        }

        /* ============================================
           BOTTOM STATUS BAR - Ionize Style
           ============================================ */
        .bottom-status-bar {
            position: fixed;
            bottom: 0;
            left: 240px;
            right: 0;
            height: 28px;
            background: var(--topbar-bg);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 15px;
            font-size: 10px;
            color: var(--text-muted);
            z-index: 999;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .status-left, .status-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .status-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .status-item svg {
            width: 12px;
            height: 12px;
            opacity: 0.6;
        }

        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--success);
        }

        .status-indicator.warning { background: var(--warning); }
        .status-indicator.error { background: var(--danger); }

        /* Secondary Topbar */
        .topbar {
            background: var(--bg-white);
            padding: 0 15px;
            height: 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            font-size: 11px;
            color: #666;
        }

        .breadcrumb a { color: var(--primary); }
        .breadcrumb span { margin: 0 5px; color: #999; }

        .topbar-actions {
            display: flex;
            gap: 8px;
        }

        /* Content Area */
        .content {
            padding: 15px;
        }

        /* ============================================
           BUTTONS - Ionize Style
           ============================================ */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border: 1px solid #bbb;
            border-radius: 0;
            font-size: 11px;
            cursor: pointer;
            transition: all 0.15s;
            background: linear-gradient(to bottom, #f8f8f8, #e8e8e8);
            color: var(--primary);
            text-decoration: none;
        }

        .btn:hover {
            background: #e0e0e0;
            text-decoration: none;
        }

        .btn-success {
            background: var(--success);
            border-color: var(--success);
            color: white;
        }

        .btn-success:hover {
            background: var(--success-dark);
            color: white;
        }

        .btn-danger {
            background: var(--danger);
            border-color: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: var(--danger-dark);
            color: white;
        }

        .btn-info {
            background: var(--info);
            border-color: var(--info);
            color: white;
        }

        .btn-sm {
            padding: 3px 8px;
            font-size: 10px;
        }

        /* ============================================
           PAGE HEADER
           ============================================ */
        .page-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
        }

        .page-header-icon {
            width: 40px;
            height: 40px;
            margin-right: 12px;
            background: var(--bg-gray-light);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .page-header h2 {
            font-size: 18px;
            font-weight: 600;
            color: #2a2d33;
            margin: 0;
        }

        .page-header .subtitle {
            font-size: 11px;
            color: #666;
            font-weight: normal;
        }

        /* ============================================
           PANELS
           ============================================ */
        .panel {
            background: var(--bg-white);
            border: 1px solid var(--border-color);
            margin-bottom: 15px;
        }

        .panel-header {
            background: var(--bg-gray-light);
            padding: 10px 15px;
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            font-size: 12px;
            color: #36607D;
        }

        .panel-body {
            padding: 15px;
        }

        .panel-footer {
            background: var(--bg-gray-light);
            padding: 10px 15px;
            border-top: 1px solid var(--border-color);
            text-align: right;
        }

        /* ============================================
           FORMS
           ============================================ */
        .form-group {
            margin-bottom: 12px;
        }

        .form-group label {
            display: block;
            font-size: 11px;
            margin-bottom: 4px;
            color: #333;
        }

        .form-group label.required { color: #b00; }

        .form-control {
            width: 100%;
            padding: 6px 8px;
            border: 1px solid #bbb;
            font-size: 11px;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            background-color: #F5F5F5;
            border-color: var(--primary);
        }

        select.form-control { height: 30px; }

        textarea.form-control {
            min-height: 80px;
            resize: vertical;
        }

        /* ============================================
           TABLES
           ============================================ */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            text-align: left;
            padding: 8px 10px;
            font-size: 11px;
            color: #36607D;
            border-bottom: 1px solid var(--border-color);
            background: var(--bg-gray-light);
        }

        .table td {
            padding: 8px 10px;
            border-bottom: 1px solid #eee;
            font-size: 11px;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: var(--bg-gray-light);
        }

        .table .actions {
            text-align: right;
            white-space: nowrap;
        }

        .table .actions .btn {
            padding: 3px 8px;
            margin-left: 3px;
        }

        /* ============================================
           STATS CARDS
           ============================================ */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: var(--bg-white);
            border: 1px solid var(--border-color);
            padding: 20px;
        }

        .stat-card-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }

        .stat-card-icon.pages { background: #e3f2fd; color: var(--info); }
        .stat-card-icon.articles { background: #e8f5e9; color: var(--success); }
        .stat-card-icon.users { background: #fff3e0; color: var(--warning); }
        .stat-card-icon.media { background: #f3e5f5; color: #9c27b0; }

        .stat-card-value {
            font-size: 28px;
            font-weight: 700;
            color: #2a2d33;
        }

        .stat-card-label {
            font-size: 11px;
            color: #666;
            margin-top: 4px;
        }

        /* Alerts */
        .alert {
            padding: 12px 15px;
            margin-bottom: 15px;
            font-size: 11px;
        }

        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-warning { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .alert-info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }

        /* Toolbar */
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            margin-bottom: 10px;
        }

        .toolbar-left, .toolbar-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Language Tabs */
        .lang-tabs {
            display: flex;
            gap: 5px;
            margin-bottom: 15px;
        }

        .lang-tab {
            padding: 6px 15px;
            background: var(--bg-gray-light);
            border: 1px solid var(--border-color);
            cursor: pointer;
            font-size: 11px;
        }

        .lang-tab.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.show { display: flex; }

        .modal {
            background: white;
            width: 80%;
            max-width: 900px;
            max-height: 80vh;
            border-radius: 4px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .modal-header {
            padding: 15px;
            background: var(--bg-gray-light);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 { margin: 0; font-size: 14px; }

        .modal-body {
            padding: 15px;
            overflow-y: auto;
            flex: 1;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #666;
        }

        .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 10px;
        }

        .media-item {
            border: 2px solid transparent;
            padding: 5px;
            cursor: pointer;
            text-align: center;
            border-radius: 4px;
        }

        .media-item:hover { border-color: var(--primary); }
        .media-item.selected { border-color: var(--success); background: #e8f5e9; }
        .media-item img { max-width: 100%; height: 80px; object-fit: cover; }
        .media-item .name { font-size: 10px; margin-top: 5px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        /* Page-Article Linker */
        .linked-items {
            border: 1px dashed #ccc;
            padding: 10px;
            min-height: 60px;
            margin-bottom: 10px;
            background: #fafafa;
        }

        .linked-item {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 8px;
            background: var(--bg-gray-light);
            border: 1px solid var(--border-color);
            border-radius: 3px;
            margin: 3px;
            font-size: 11px;
        }

        .linked-item .remove {
            cursor: pointer;
            color: var(--danger);
            font-weight: bold;
        }

        /* Two column layout */
        .two-columns {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 15px;
        }

        @media (max-width: 900px) {
            .two-columns { grid-template-columns: 1fr; }
        }

        /* Tabs */
        .tabs {
            display: flex;
            border-bottom: 2px solid var(--border-color);
            margin-bottom: 15px;
        }

        .tab {
            padding: 10px 20px;
            background: var(--bg-gray-light);
            border: 1px solid var(--border-color);
            border-bottom: none;
            margin-right: 2px;
            cursor: pointer;
            font-size: 11px;
            font-weight: 500;
        }

        .tab.active {
            background: white;
            border-bottom: 2px solid white;
            margin-bottom: -2px;
            color: var(--primary);
        }

        .tab-content { display: none; }
        .tab-content.active { display: block; }
    </style>
    @stack('styles')
</head>
<body>
    <!-- MAIN TOP BAR -->
    <header class="main-topbar">
        <div class="topbar-brand">
            <h1>Ionize<span>vel</span></h1>

            <nav class="top-menu">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="top-menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>

                <!-- Content -->
                <div class="top-menu-item {{ request()->routeIs('admin.pages.*') || request()->routeIs('admin.articles.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.media.*') || request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Content ▾
                    <div class="top-dropdown">
                        <a href="{{ route('admin.pages.create') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            New page
                        </a>
                        <a href="{{ route('admin.articles.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            Articles
                        </a>
                        <a href="{{ route('admin.categories.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            Categories
                        </a>
                        <a href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                            Translations
                        </a>
                        <a href="{{ route('admin.menus.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                            Menus
                        </a>
                        <a href="{{ route('admin.media.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Media manager
                        </a>
                        <a href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            SEO
                        </a>
                        <a href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                            Content elements
                        </a>
                    </div>
                </div>

                <!-- Modules -->
                <div class="top-menu-item">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                    Modules ▾
                    <div class="top-dropdown">
                        <a href="#">Modules list</a>
                        <a href="#">Static Block</a>
                    </div>
                </div>

                <!-- Tools -->
                <div class="top-menu-item">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Tools ▾
                    <div class="top-dropdown">
                        <a href="{{ route('admin.settings.index') }}#panel-analytics">Google Analytics</a>
                        <a href="{{ route('admin.tools.diagnose') }}">System diagnose</a>
                    </div>
                </div>

                <!-- Settings -->
                <div class="top-menu-item {{ request()->routeIs('admin.settings.*') || request()->routeIs('admin.languages.*') || request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                    Settings ▾
                    <div class="top-dropdown">
                        <a href="{{ route('admin.settings.index') }}">Website settings</a>
                        <a href="{{ route('admin.theme.index') }}">Theme</a>
                        <a href="{{ route('admin.languages.index') }}">Languages</a>
                        <a href="#">Translations</a>
                        <a href="{{ route('admin.settings.index') }}#panel-cache">Ionize</a>
                        <a href="{{ route('admin.tools.diagnose') }}">Technical settings</a>
                        <a href="{{ route('admin.users.index') }}">Users and Roles</a>
                        <a href="{{ route('admin.settings.index') }}#panel-maintenance">Advanced settings</a>
                    </div>
                </div>
            </nav>
        </div>

        <div class="topbar-right">
            <div class="topbar-user">
                <div class="topbar-user-avatar">
                    {{ strtoupper(substr(auth()->user()->username, 0, 2)) }}
                </div>
                <span>{{ auth()->user()->username }}</span>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="topbar-logout">Logout</button>
            </form>
        </div>
    </header>

    <!-- LEFT SIDEBAR - Ionize Accordion Style -->
    <aside class="sidebar">
        <!-- ============================================
             CONTENT ACCORDION
             ============================================ -->
        <div class="accordion-group">
            <div class="accordion-header open" onclick="toggleAccordion(this)">
                <div class="accordion-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Content
                </div>
                <div style="display: flex; gap: 5px; align-items: center;">
                    <button class="quick-add-btn" onclick="event.stopPropagation(); window.location='{{ route('admin.pages.create') }}'" title="New Page">+</button>
                    <svg class="accordion-toggle" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </div>
            <div class="accordion-content open">
                <!-- Menu Selector -->
                <div class="menu-selector">
                    <select id="menuSelector" onchange="loadMenuPages(this.value)">
                        @php
                            $menus = \App\Models\Menu::orderBy('ordering')->get();
                        @endphp
                        @foreach($menus as $menu)
                            <option value="{{ $menu->id_menu }}">{{ $menu->title ?? $menu->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Page Tree -->
                <div class="page-tree" id="pageTree">
                    @php
                        $pages = \App\Models\Page::where('id_parent', 0)
                            ->orderBy('ordering')
                            ->with(['translations', 'children.translations'])
                            ->get();
                    @endphp
                    @foreach($pages as $page)
                        @php
                            $translation = $page->translations->first();
                            $hasChildren = $page->children->count() > 0;
                        @endphp
                        <div class="tree-item {{ $hasChildren ? 'has-children' : '' }}" 
                             data-page-id="{{ $page->id_page }}" 
                             data-parent-id="0"
                             data-online="{{ $page->online ? '1' : '0' }}"
                             oncontextmenu="showContextMenu(event, {{ $page->id_page }}, '{{ addslashes($translation->title ?? $page->name) }}')"
                             onclick="handlePageClick(event, '{{ route('admin.pages.edit', $page->id_page) }}')">
                            <span class="drag-handle" title="Drag to reorder">⠿</span>
                            <span class="toggle" onclick="toggleChildren(event, this)">{{ $hasChildren ? '▶' : '' }}</span>
                            <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="title">{{ $translation->title ?? $page->name }}</span>
                            @if($page->home)
                                <span class="status home">H</span>
                            @elseif($page->online)
                                <span class="status online">✓</span>
                            @else
                                <span class="status offline">✗</span>
                            @endif
                        </div>
                        @if($hasChildren)
                            <div class="tree-children" data-parent-id="{{ $page->id_page }}">
                                @foreach($page->children->sortBy('ordering') as $child)
                                    @php $childTranslation = $child->translations->first(); @endphp
                                    <div class="tree-item" 
                                         data-page-id="{{ $child->id_page }}"
                                         data-parent-id="{{ $page->id_page }}"
                                         data-online="{{ $child->online ? '1' : '0' }}"
                                         oncontextmenu="showContextMenu(event, {{ $child->id_page }}, '{{ addslashes($childTranslation->title ?? $child->name) }}')"
                                         onclick="handlePageClick(event, '{{ route('admin.pages.edit', $child->id_page) }}')">
                                            <span class="drag-handle" title="Drag to reorder">⠿</span>
                                            <span class="toggle"></span>
                                            <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <span class="title">{{ $childTranslation->title ?? $child->name }}</span>
                                            @if($child->online)
                                                <span class="status online">✓</span>
                                            @else
                                                <span class="status offline">✗</span>
                                            @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Quick Links -->
                <a href="{{ route('admin.articles.index') }}" class="sidebar-link {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    Articles
                    <span class="badge">{{ \App\Models\Article::count() }}</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Categories
                </a>
                <a href="{{ route('admin.media.index') }}" class="sidebar-link {{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Media Manager
                </a>
                <a href="{{ route('admin.menus.index') }}" class="sidebar-link {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    Menus
                </a>
            </div>
        </div>

        <!-- ============================================
             MODULES ACCORDION
             ============================================ -->
        <div class="accordion-group">
            <div class="accordion-header" onclick="toggleAccordion(this)">
                <div class="accordion-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                    Modules
                </div>
                <svg class="accordion-toggle" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
            <div class="accordion-content">
                <a href="{{ route('admin.modules.index') }}" class="sidebar-link {{ request()->routeIs('admin.modules.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Modules List
                </a>
                <a href="#" class="sidebar-link">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z"/>
                    </svg>
                    Static Blocks
                </a>
            </div>
        </div>

        <!-- ============================================
             SETTINGS ACCORDION
             ============================================ -->
        <div class="accordion-group">
            <div class="accordion-header" onclick="toggleAccordion(this)">
                <div class="accordion-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Settings
                </div>
                <svg class="accordion-toggle" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
            <div class="accordion-content">
                <a href="{{ route('admin.settings.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 019-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                    </svg>
                    Website Settings
                </a>
                <a href="{{ route('admin.languages.index') }}" class="sidebar-link {{ request()->routeIs('admin.languages.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                    </svg>
                    Languages
                </a>
                <a href="{{ route('admin.theme.index') }}" class="sidebar-link {{ request()->routeIs('admin.theme.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                    </svg>
                    Theme
                </a>
                <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Users & Roles
                </a>
                <a href="{{ route('admin.settings.advanced') }}" class="sidebar-link {{ request()->routeIs('admin.settings.advanced') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                    Advanced
                </a>
            </div>
        </div>

        <!-- ============================================
             HELP ACCORDION
             ============================================ -->
        <div class="accordion-group">
            <div class="accordion-header" onclick="toggleAccordion(this)">
                <div class="accordion-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Help
                </div>
                <svg class="accordion-toggle" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
            <div class="accordion-content">
                <a href="{{ route('admin.help') }}" class="sidebar-link">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Documentation
                </a>
                <a href="{{ route('admin.tools.diagnose') }}" class="sidebar-link">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                    </svg>
                    System Diagnose
                </a>
                <a href="#" class="sidebar-link" onclick="showKeyboardShortcuts()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Keyboard Shortcuts
                </a>
            </div>
        </div>

        <!-- System Info -->
        <div class="sidebar-section" style="margin-top: auto; padding: 15px 12px; font-size: 10px; color: var(--text-muted); border-top: 1px solid rgba(255,255,255,0.1);">
            <div>Ionizevel CMS v1.0.0</div>
            <div style="margin-top: 3px;">Laravel {{ app()->version() }}</div>
        </div>
    </aside>

    <!-- Context Menu -->
    <div class="context-menu" id="pageContextMenu">
        <div class="context-menu-item" onclick="contextMenuEdit()">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Page
        </div>
        <div class="context-menu-item" onclick="contextMenuAddChild()">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Child Page
        </div>
        <div class="context-menu-item" onclick="contextMenuDuplicate()">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            Duplicate
        </div>
        <div class="context-menu-divider"></div>
        <div class="context-menu-item" onclick="contextMenuToggleOnline()">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <span id="toggleOnlineText">Toggle Online</span>
        </div>
        <div class="context-menu-item" onclick="contextMenuViewFrontend()">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            View on Frontend
        </div>
        <div class="context-menu-divider"></div>
        <div class="context-menu-item danger" onclick="contextMenuDelete()">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Delete
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <header class="topbar">
            <div class="topbar-left">
                @yield('breadcrumb')
            </div>
            <div class="topbar-actions">
                @yield('actions')
            </div>
        </header>

        <div class="content">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Media Picker Modal -->
    <div class="modal-overlay" id="mediaPickerModal">
        <div class="modal">
            <div class="modal-header">
                <h3>Medya Seç</h3>
                <button class="modal-close" onclick="closeMediaPicker()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="media-grid" id="mediaPickerGrid">
                    <!-- Media items will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // ============================================
        // ACCORDION SIDEBAR FUNCTIONS
        // ============================================
        function toggleAccordion(header) {
            const content = header.nextElementSibling;
            const isOpen = header.classList.contains('open');
            
            if (isOpen) {
                header.classList.remove('open');
                content.classList.remove('open');
            } else {
                header.classList.add('open');
                content.classList.add('open');
            }
            
            // Save state to localStorage
            saveAccordionState();
        }

        function saveAccordionState() {
            const headers = document.querySelectorAll('.accordion-header');
            const states = {};
            headers.forEach((header, index) => {
                states[index] = header.classList.contains('open');
            });
            localStorage.setItem('ionize_accordion_state', JSON.stringify(states));
        }

        function restoreAccordionState() {
            const saved = localStorage.getItem('ionize_accordion_state');
            if (saved) {
                const states = JSON.parse(saved);
                const headers = document.querySelectorAll('.accordion-header');
                headers.forEach((header, index) => {
                    const content = header.nextElementSibling;
                    if (states[index]) {
                        header.classList.add('open');
                        content.classList.add('open');
                    } else {
                        header.classList.remove('open');
                        content.classList.remove('open');
                    }
                });
            }
        }

        // Restore accordion state on page load
        document.addEventListener('DOMContentLoaded', restoreAccordionState);

        // Keyboard shortcuts modal
        function showKeyboardShortcuts() {
            alert('Keyboard Shortcuts:\n\nCtrl+S - Save\nCtrl+N - New\nEsc - Close modal\nCtrl+B - Bold\nCtrl+I - Italic');
        }

        // Toggle tree children
        document.querySelectorAll('.tree-item.has-children .toggle').forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.stopPropagation();
                const parent = this.closest('.tree-item');
                const children = parent.nextElementSibling;
                if (children && children.classList.contains('tree-children')) {
                    children.classList.toggle('open');
                    this.textContent = children.classList.contains('open') ? '▼' : '▶';
                }
            });
        });

        // TinyMCE Initialization
        function initTinyMCE(selector = '.wysiwyg') {
            tinymce.init({
                selector: selector,
                height: 400,
                menubar: false,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'help', 'wordcount'
                ],
                toolbar: 'undo redo | blocks | ' +
                    'bold italic forecolor | alignleft aligncenter ' +
                    'alignright alignjustify | bullist numlist outdent indent | ' +
                    'image media link | removeformat | code fullscreen help',
                content_style: 'body { font-family: "Segoe UI", sans-serif; font-size: 14px; }',
                file_picker_callback: function(callback, value, meta) {
                    if (meta.filetype === 'image') {
                        openMediaPicker(function(url) {
                            callback(url, { alt: '' });
                        });
                    }
                },
                images_upload_url: '/admin/media/upload-ajax',
                automatic_uploads: true,
                images_upload_handler: function(blobInfo, progress) {
                    return new Promise((resolve, reject) => {
                        const formData = new FormData();
                        formData.append('file', blobInfo.blob(), blobInfo.filename());

                        fetch('/admin/media/upload-ajax', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(result => {
                            if (result.location) {
                                resolve(result.location);
                            } else {
                                reject('Upload failed');
                            }
                        })
                        .catch(error => reject(error));
                    });
                }
            });
        }

        // Media Picker
        let mediaPickerCallback = null;

        function openMediaPicker(callback) {
            mediaPickerCallback = callback;
            document.getElementById('mediaPickerModal').classList.add('show');
            loadMediaItems();
        }

        function closeMediaPicker() {
            document.getElementById('mediaPickerModal').classList.remove('show');
            mediaPickerCallback = null;
        }

        function loadMediaItems() {
            fetch('/admin/media/json')
                .then(response => response.json())
                .then(data => {
                    const grid = document.getElementById('mediaPickerGrid');
                    grid.innerHTML = data.map(item => `
                        <div class="media-item" onclick="selectMedia('${item.path}', this)">
                            <img src="${item.path}" alt="${item.name}">
                            <div class="name">${item.name}</div>
                        </div>
                    `).join('');
                });
        }

        function selectMedia(url, element) {
            if (mediaPickerCallback) {
                mediaPickerCallback(url);
            }
            closeMediaPicker();
        }

        // Slug generator
        function generateSlug(text) {
            const turkishMap = {'ı':'i','ğ':'g','ü':'u','ş':'s','ö':'o','ç':'c','İ':'i','Ğ':'g','Ü':'u','Ş':'s','Ö':'o','Ç':'c'};
            return text.toLowerCase()
                .replace(/[ığüşöçİĞÜŞÖÇ]/g, c => turkishMap[c] || c)
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim();
        }

        // ============================================
        // PAGE TREE - DRAG & DROP
        // ============================================
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize SortableJS on page tree
            const pageTree = document.getElementById('pageTree');
            if (pageTree && typeof Sortable !== 'undefined') {
                new Sortable(pageTree, {
                    handle: '.drag-handle',
                    animation: 150,
                    ghostClass: 'dragging',
                    onEnd: function(evt) {
                        savePageOrder();
                    }
                });

                // Also make children sortable
                document.querySelectorAll('.tree-children').forEach(children => {
                    new Sortable(children, {
                        handle: '.drag-handle',
                        animation: 150,
                        ghostClass: 'dragging',
                        group: 'pages',
                        onEnd: function(evt) {
                            savePageOrder();
                        }
                    });
                });
            }
        });

        function savePageOrder() {
            const pages = [];
            let ordering = 0;
            
            document.querySelectorAll('#pageTree > .tree-item').forEach(item => {
                ordering++;
                pages.push({
                    id: parseInt(item.dataset.pageId),
                    ordering: ordering,
                    parent_id: 0
                });
            });

            document.querySelectorAll('.tree-children').forEach(children => {
                const parentId = parseInt(children.dataset.parentId);
                let childOrdering = 0;
                children.querySelectorAll('.tree-item').forEach(item => {
                    childOrdering++;
                    pages.push({
                        id: parseInt(item.dataset.pageId),
                        ordering: childOrdering,
                        parent_id: parentId
                    });
                });
            });

            fetch('/admin/pages/reorder', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ pages: pages })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Page order saved');
                }
            })
            .catch(error => console.error('Error saving page order:', error));
        }

        // ============================================
        // PAGE TREE - CONTEXT MENU
        // ============================================
        let contextMenuPageId = null;
        let contextMenuPageTitle = '';

        function showContextMenu(event, pageId, pageTitle) {
            event.preventDefault();
            event.stopPropagation();
            
            contextMenuPageId = pageId;
            contextMenuPageTitle = pageTitle;
            
            const menu = document.getElementById('pageContextMenu');
            menu.style.left = event.clientX + 'px';
            menu.style.top = event.clientY + 'px';
            menu.classList.add('show');
            
            // Update toggle text based on current state
            const item = event.target.closest('.tree-item');
            const isOnline = item.dataset.online === '1';
            document.getElementById('toggleOnlineText').textContent = isOnline ? 'Set Offline' : 'Set Online';
        }

        function hideContextMenu() {
            document.getElementById('pageContextMenu').classList.remove('show');
        }

        document.addEventListener('click', hideContextMenu);

        function contextMenuEdit() {
            hideContextMenu();
            window.location.href = '/admin/pages/' + contextMenuPageId + '/edit';
        }

        function contextMenuAddChild() {
            hideContextMenu();
            window.location.href = '/admin/pages/create?parent_id=' + contextMenuPageId;
        }

        function contextMenuDuplicate() {
            hideContextMenu();
            if (confirm('Are you sure you want to duplicate "' + contextMenuPageTitle + '"?')) {
                fetch('/admin/pages/' + contextMenuPageId + '/duplicate', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => {
                    if (response.redirected) {
                        window.location.href = response.url;
                    } else {
                        window.location.reload();
                    }
                });
            }
        }

        function contextMenuToggleOnline() {
            hideContextMenu();
            fetch('/admin/pages/' + contextMenuPageId + '/toggle-online', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the status indicator
                    const item = document.querySelector('[data-page-id="' + contextMenuPageId + '"]');
                    const status = item.querySelector('.status');
                    if (data.online) {
                        status.className = 'status online';
                        status.textContent = '✓';
                        item.dataset.online = '1';
                    } else {
                        status.className = 'status offline';
                        status.textContent = '✗';
                        item.dataset.online = '0';
                    }
                }
            });
        }

        function contextMenuViewFrontend() {
            hideContextMenu();
            // Open page in new tab - assuming default language
            window.open('/tr/' + contextMenuPageId, '_blank');
        }

        function contextMenuDelete() {
            hideContextMenu();
            if (confirm('Are you sure you want to delete "' + contextMenuPageTitle + '"? This cannot be undone.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/admin/pages/' + contextMenuPageId;
                form.innerHTML = `
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                    <input type="hidden" name="_method" value="DELETE">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        // ============================================
        // PAGE TREE - EXPAND/COLLAPSE
        // ============================================
        function toggleChildren(event, toggle) {
            event.stopPropagation();
            const treeItem = toggle.closest('.tree-item');
            const children = treeItem.nextElementSibling;
            
            if (children && children.classList.contains('tree-children')) {
                children.classList.toggle('open');
                toggle.textContent = children.classList.contains('open') ? '▼' : '▶';
            }
        }

        function handlePageClick(event, url) {
            // Don't navigate if clicking on toggle or drag handle
            if (event.target.closest('.toggle') || event.target.closest('.drag-handle')) {
                return;
            }
            window.location.href = url;
        }

        // ============================================
        // TOAST NOTIFICATION SYSTEM
        // ============================================
        window.toast = {
            show: function(message, type = 'success', duration = 3000) {
                const container = document.getElementById('toast-container');
                const toast = document.createElement('div');
                toast.className = 'toast toast-' + type;
                toast.innerHTML = `
                    <span class="toast-icon">${type === 'success' ? '✓' : type === 'error' ? '✕' : type === 'warning' ? '⚠' : 'ℹ'}</span>
                    <span class="toast-message">${message}</span>
                    <button class="toast-close" onclick="this.parentElement.remove()">×</button>
                `;
                container.appendChild(toast);
                
                setTimeout(() => toast.classList.add('show'), 10);
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 300);
                }, duration);
            },
            success: function(message) { this.show(message, 'success'); },
            error: function(message) { this.show(message, 'error', 5000); },
            warning: function(message) { this.show(message, 'warning', 4000); },
            info: function(message) { this.show(message, 'info'); }
        };

        // ============================================
        // LOADING INDICATOR
        // ============================================
        window.loading = {
            show: function(text = 'Loading...') {
                document.getElementById('global-loading').classList.add('active');
                document.getElementById('loading-text').textContent = text;
            },
            hide: function() {
                document.getElementById('global-loading').classList.remove('active');
            }
        };

        // ============================================
        // MODAL SYSTEM
        // ============================================
        window.modal = {
            show: function(options) {
                const overlay = document.getElementById('modal-overlay');
                const title = document.getElementById('modal-title');
                const body = document.getElementById('modal-body');
                const footer = document.getElementById('modal-footer');
                
                title.textContent = options.title || 'Modal';
                body.innerHTML = options.content || '';
                
                if (options.buttons) {
                    footer.innerHTML = '';
                    options.buttons.forEach(btn => {
                        const button = document.createElement('button');
                        button.className = 'btn ' + (btn.class || '');
                        button.textContent = btn.text;
                        button.onclick = btn.onclick || (() => this.hide());
                        footer.appendChild(button);
                    });
                }
                
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            },
            hide: function() {
                document.getElementById('modal-overlay').classList.remove('active');
                document.body.style.overflow = '';
            },
            confirm: function(message, callback) {
                this.show({
                    title: 'Confirm',
                    content: `<p>${message}</p>`,
                    buttons: [
                        { text: 'Cancel', onclick: () => this.hide() },
                        { text: 'Confirm', class: 'btn-danger', onclick: () => { this.hide(); callback(); } }
                    ]
                });
            }
        };

        // Close modal on overlay click
        document.getElementById('modal-overlay').addEventListener('click', function(e) {
            if (e.target === this) window.modal.hide();
        });

        // ============================================
        // KEYBOARD SHORTCUTS
        // ============================================
        document.addEventListener('keydown', function(e) {
            // Ctrl+S: Save (submit nearest form)
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                const form = document.querySelector('form:not([data-no-shortcut])');
                if (form) {
                    const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
                    if (submitBtn) submitBtn.click();
                }
                return;
            }
            
            // Ctrl+N: New (click first "New" button)
            if (e.ctrlKey && e.key === 'n') {
                e.preventDefault();
                const newBtn = document.querySelector('a.btn-success[href*="create"]');
                if (newBtn) window.location.href = newBtn.href;
                return;
            }
            
            // Esc: Close modal
            if (e.key === 'Escape') {
                window.modal.hide();
                return;
            }
            
            // Ctrl+/: Show keyboard shortcuts
            if (e.ctrlKey && e.key === '/') {
                e.preventDefault();
                window.modal.show({
                    title: 'Keyboard Shortcuts',
                    content: `
                        <div style="font-size: 12px; line-height: 2;">
                            <div><kbd>Ctrl</kbd> + <kbd>S</kbd> — Save / Submit form</div>
                            <div><kbd>Ctrl</kbd> + <kbd>N</kbd> — Create new item</div>
                            <div><kbd>Esc</kbd> — Close modal</div>
                            <div><kbd>Ctrl</kbd> + <kbd>/</kbd> — Show shortcuts</div>
                        </div>
                    `,
                    buttons: [{ text: 'Close', class: 'btn-primary' }]
                });
            }
        });

        // Show session flash messages
        @if(session('success'))
            window.toast.success('{{ session('success') }}');
        @endif
        @if(session('error'))
            window.toast.error('{{ session('error') }}');
        @endif
        @if(session('warning'))
            window.toast.warning('{{ session('warning') }}');
        @endif
    </script>

    <!-- Toast Container -->
    <div id="toast-container"></div>
    
    <!-- Global Loading Overlay -->
    <div id="global-loading">
        <div class="loading-spinner"></div>
        <div id="loading-text">Loading...</div>
    </div>
    
    <!-- Modal System -->
    <div id="modal-overlay">
        <div class="modal-dialog">
            <div class="modal-header">
                <span id="modal-title"></span>
                <button class="modal-close" onclick="window.modal.hide()">×</button>
            </div>
            <div id="modal-body"></div>
            <div id="modal-footer"></div>
        </div>
    </div>

    <style>
        /* Toast Notifications */
        #toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10000;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .toast {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            background: white;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateX(100%);
            opacity: 0;
            transition: all 0.3s ease;
            min-width: 280px;
        }
        .toast.show { transform: translateX(0); opacity: 1; }
        .toast-success { border-left: 4px solid var(--success); }
        .toast-error { border-left: 4px solid var(--danger); }
        .toast-warning { border-left: 4px solid var(--warning); }
        .toast-info { border-left: 4px solid var(--info); }
        .toast-icon { font-weight: bold; }
        .toast-success .toast-icon { color: var(--success); }
        .toast-error .toast-icon { color: var(--danger); }
        .toast-warning .toast-icon { color: var(--warning); }
        .toast-info .toast-icon { color: var(--info); }
        .toast-message { flex: 1; font-size: 13px; }
        .toast-close { background: none; border: none; font-size: 18px; cursor: pointer; color: #999; }
        
        /* Loading Overlay */
        #global-loading {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            display: none;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 15px;
            z-index: 10001;
        }
        #global-loading.active { display: flex; }
        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #fff;
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        #loading-text { color: white; font-size: 14px; }
        
        /* Modal System */
        #modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10002;
        }
        #modal-overlay.active { display: flex; }
        .modal-dialog {
            background: white;
            border-radius: 8px;
            min-width: 400px;
            max-width: 90%;
            max-height: 90vh;
            overflow: auto;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
        }
        #modal-title { font-weight: 600; font-size: 16px; }
        .modal-close { background: none; border: none; font-size: 24px; cursor: pointer; color: #999; }
        #modal-body { padding: 20px; }
        #modal-footer { padding: 15px 20px; border-top: 1px solid #eee; display: flex; gap: 10px; justify-content: flex-end; }
        
        /* Keyboard Shortcut Keys */
        kbd {
            display: inline-block;
            padding: 2px 6px;
            background: #f5f5f5;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-family: monospace;
            font-size: 11px;
        }
        
        /* Responsive Improvements */
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main-content { margin-left: 0; }
            .topbar .breadcrumb { display: none; }
            .modal-dialog { min-width: 90%; }
        }
    </style>

    @stack('scripts')

    <!-- Bottom Status Bar -->
    <div class="bottom-status-bar">
        <div class="status-left">
            <div class="status-item">
                <span class="status-indicator"></span>
                <span>System Ready</span>
            </div>
            <div class="status-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                </svg>
                <span>{{ count(\App\Models\Language::all()) }} Languages</span>
            </div>
            <div class="status-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>{{ \App\Models\Page::count() }} Pages</span>
            </div>
        </div>
        <div class="status-right">
            <div class="status-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span id="statusTime">--:--</span>
            </div>
            <div class="status-item">
                <span>{{ auth()->user()->username }}</span>
            </div>
        </div>
    </div>

    <script>
        // Update status bar time
        function updateStatusTime() {
            const now = new Date();
            const time = now.toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' });
            document.getElementById('statusTime').textContent = time;
        }
        updateStatusTime();
        setInterval(updateStatusTime, 60000);
    </script>
</body>
</html>

