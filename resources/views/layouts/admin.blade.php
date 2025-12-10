<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | Ionizevel CMS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #667eea;
            --primary-dark: #5a67d8;
            --sidebar-bg: #1a1a2e;
            --sidebar-hover: #16213e;
            --text-light: #e2e8f0;
            --text-muted: #94a3b8;
            --bg-main: #f1f5f9;
            --white: #ffffff;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
        }

        body {
            font-family: "Segoe UI", -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-main);
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            height: 100vh;
            background: var(--sidebar-bg);
            color: var(--text-light);
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-header h1 {
            font-size: 22px;
            font-weight: 700;
        }

        .sidebar-header h1 span {
            color: var(--primary);
        }

        .sidebar-menu {
            padding: 16px 0;
        }

        .menu-section {
            padding: 8px 20px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            margin-top: 16px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--text-light);
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .menu-item:hover, .menu-item.active {
            background: var(--sidebar-hover);
            border-left-color: var(--primary);
        }

        .menu-item svg {
            width: 20px;
            height: 20px;
            margin-right: 12px;
            opacity: 0.7;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
        }

        .topbar {
            background: var(--white);
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-title {
            font-size: 20px;
            font-weight: 600;
            color: #1e293b;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            color: #1e293b;
        }

        .user-role {
            font-size: 12px;
            color: var(--text-muted);
        }

        .logout-btn {
            background: #fee2e2;
            color: var(--danger);
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s;
        }

        .logout-btn:hover {
            background: #fecaca;
        }

        .content {
            padding: 24px;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--white);
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .stat-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-icon.pages { background: #dbeafe; color: var(--info); }
        .stat-icon.articles { background: #d1fae5; color: var(--success); }
        .stat-icon.users { background: #fef3c7; color: var(--warning); }
        .stat-icon.media { background: #ede9fe; color: #8b5cf6; }

        .stat-title {
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 4px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #1e293b;
        }

        /* Welcome Card */
        .welcome-card {
            background: linear-gradient(135deg, var(--primary) 0%, #764ba2 100%);
            color: white;
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 24px;
        }

        .welcome-card h2 {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .welcome-card p {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h1>Ionize<span>vel</span></h1>
        </div>
        <nav class="sidebar-menu">
            <div class="menu-section">Ana Menü</div>
            <a href="{{ route('admin.dashboard') }}" class="menu-item active">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>
            
            <div class="menu-section">İçerik</div>
            <a href="#" class="menu-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Sayfalar
            </a>
            <a href="#" class="menu-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
                Makaleler
            </a>
            <a href="#" class="menu-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Medya
            </a>
            
            <div class="menu-section">Sistem</div>
            <a href="#" class="menu-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Kullanıcılar
            </a>
            <a href="#" class="menu-item">
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
            <h2 class="topbar-title">@yield('title', 'Dashboard')</h2>
            <div class="user-menu">
                <div class="user-info">
                    <div class="user-name">{{ auth()->user()->full_name }}</div>
                    <div class="user-role">{{ auth()->user()->role->role_name ?? 'User' }}</div>
                </div>
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="logout-btn">Çıkış</button>
                </form>
            </div>
        </header>

        <div class="content">
            @yield('content')
        </div>
    </main>
</body>
</html>
