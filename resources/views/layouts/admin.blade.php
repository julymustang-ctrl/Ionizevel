<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | Ionizevel CMS</title>
    <style>
        /* Reset & Base */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            /* Ionize Color Palette */
            --primary: #098ED1;
            --primary-dark: #0973B6;
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

        /* Sidebar - Ionize Style */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 220px;
            height: 100vh;
            background: var(--sidebar-bg);
            color: var(--text-light);
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        .sidebar-header {
            padding: 15px;
            background: var(--sidebar-dark);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-header h1 {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-white);
        }

        .sidebar-header h1 span { color: var(--primary); }

        .sidebar-header .version {
            font-size: 10px;
            color: var(--text-muted);
            margin-top: 3px;
        }

        /* Menu Sections */
        .menu-section {
            padding: 12px 15px 6px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        .menu-section:first-of-type { border-top: none; }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            color: var(--text-light);
            text-decoration: none;
            transition: all 0.15s;
            border-left: 3px solid transparent;
            font-size: 12px;
        }

        .menu-item:hover {
            background: var(--sidebar-hover);
            border-left-color: var(--primary);
            text-decoration: none;
            color: var(--text-white);
        }

        .menu-item.active {
            background: var(--sidebar-hover);
            border-left-color: var(--primary);
            color: var(--text-white);
        }

        .menu-item svg, .menu-item .icon {
            width: 16px;
            height: 16px;
            margin-right: 10px;
            opacity: 0.7;
        }

        .menu-item:hover svg, .menu-item:hover .icon,
        .menu-item.active svg, .menu-item.active .icon {
            opacity: 1;
        }

        /* Main Content Area */
        .main-content {
            margin-left: 220px;
            min-height: 100vh;
        }

        /* Top Bar - Ionize Style */
        .topbar {
            background: var(--bg-white);
            padding: 0 15px;
            height: 44px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .topbar-title {
            font-size: 14px;
            font-weight: 600;
            color: #2a2d33;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            font-size: 11px;
            color: #666;
        }

        .breadcrumb a { color: var(--primary); }
        .breadcrumb span { margin: 0 5px; color: #999; }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .user-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 600;
        }

        .user-name {
            font-weight: 600;
            color: #333;
            font-size: 11px;
        }

        .user-role {
            font-size: 10px;
            color: #999;
        }

        /* Buttons - Ionize Style */
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
            background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAACCAYAAACZgbYnAAAADklEQVQI12NgYGBgYAQAAA4ABzQGrN8AAAAASUVORK5CYII=');
            color: var(--primary);
            text-decoration: none;
        }

        .btn:hover {
            background: #e8e8e8;
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

        /* Content Area */
        .content {
            padding: 15px;
        }

        /* Page Header - Ionize Style */
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
            font-size: 20px;
            font-weight: 600;
            color: #2a2d33;
            margin: 0;
        }

        .page-header .subtitle {
            font-size: 12px;
            color: #666;
            font-weight: normal;
        }

        /* Panels - Ionize Style */
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

        /* Forms - Ionize Style */
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

        /* Tables - Ionize Style */
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

        /* Stats Cards */
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

        /* Tabs - Ionize Style */
        .tabs {
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 15px;
        }

        .tabs .tab {
            display: inline-block;
            padding: 10px 20px;
            font-size: 12px;
            color: #666;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            margin-bottom: -1px;
        }

        .tabs .tab:hover { color: var(--primary); }

        .tabs .tab.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
            font-weight: 600;
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

        /* Tree View */
        .tree { list-style: none; padding-left: 0; }
        .tree ul { list-style: none; padding-left: 20px; }
        .tree li { padding: 4px 0; }

        .tree-item {
            display: flex;
            align-items: center;
            padding: 4px 8px;
            cursor: pointer;
            border-radius: 3px;
        }

        .tree-item:hover { background: var(--bg-gray-light); }
        .tree-item.active { background: #cef; }

        .tree-toggle {
            width: 16px;
            height: 16px;
            margin-right: 5px;
            text-align: center;
            font-size: 10px;
            line-height: 16px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h1>Ionize<span>vel</span></h1>
            <div class="version">Laravel CMS v1.0.0</div>
        </div>

        <nav>
            <div class="menu-section">İçerik Yönetimi</div>
            <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>
            <a href="{{ route('admin.pages.index') }}" class="menu-item {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Sayfalar
            </a>
            <a href="{{ route('admin.articles.index') }}" class="menu-item {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
                Makaleler
            </a>
            <a href="{{ route('admin.media.index') }}" class="menu-item {{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Medya
            </a>

            <div class="menu-section">Yapılandırma</div>
            <a href="{{ route('admin.categories.index') }}" class="menu-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                Kategoriler
            </a>
            <a href="{{ route('admin.menus.index') }}" class="menu-item {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                Menüler
            </a>
            <a href="{{ route('admin.languages.index') }}" class="menu-item {{ request()->routeIs('admin.languages.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                </svg>
                Diller
            </a>

            <div class="menu-section">Sistem</div>
            <a href="{{ route('admin.users.index') }}" class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Kullanıcılar
            </a>
            <a href="{{ route('admin.settings.index') }}" class="menu-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Ayarlar
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="topbar">
            <div class="topbar-left">
                @yield('breadcrumb')
            </div>
            <div class="topbar-right">
                <div class="user-info">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->username, 0, 2)) }}
                    </div>
                    <div>
                        <div class="user-name">{{ auth()->user()->full_name }}</div>
                        <div class="user-role">{{ auth()->user()->role->role_name ?? 'User' }}</div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Çıkış</button>
                </form>
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

    @stack('scripts')
</body>
</html>
