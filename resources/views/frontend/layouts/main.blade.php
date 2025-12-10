<!DOCTYPE html>
<html lang="{{ $currentLang }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('meta_title', $siteName ?? 'Ionizevel')</title>
    <meta name="description" content="@yield('meta_description', '')">
    <meta name="keywords" content="@yield('meta_keywords', '')">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #098ED1;
            --primary-dark: #0973B6;
            --secondary: #2a2d33;
            --accent: #8AC05E;
            --text: #333333;
            --text-light: #666666;
            --text-muted: #999999;
            --bg: #ffffff;
            --bg-alt: #f8f9fa;
            --border: #e5e7eb;
            --shadow: rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 16px;
            line-height: 1.6;
            color: var(--text);
            background: var(--bg);
        }

        a {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.2s;
        }

        a:hover {
            color: var(--primary-dark);
        }

        img {
            max-width: 100%;
            height: auto;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */
        .header {
            background: var(--secondary);
            color: white;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px var(--shadow);
        }

        .header-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: white;
            text-decoration: none;
        }

        .logo span {
            color: var(--primary);
        }

        /* Navigation */
        .nav {
            display: flex;
            gap: 30px;
        }

        .nav a {
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            font-size: 14px;
            padding: 8px 0;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
        }

        .nav a:hover,
        .nav a.active {
            color: white;
            border-bottom-color: var(--primary);
        }

        /* Language Switcher */
        .lang-switch {
            display: flex;
            gap: 8px;
        }

        .lang-switch a {
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.7);
            background: rgba(255, 255, 255, 0.1);
        }

        .lang-switch a.active {
            background: var(--primary);
            color: white;
        }

        /* Hero */
        .hero {
            background: linear-gradient(135deg, var(--secondary) 0%, #1a1d21 100%);
            color: white;
            padding: 80px 0;
            text-align: center;
        }

        .hero h1 {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .hero .subtitle {
            font-size: 20px;
            color: rgba(255, 255, 255, 0.8);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Main Content */
        .main {
            padding: 60px 0;
        }

        .content {
            max-width: 800px;
        }

        .content h1 {
            font-size: 36px;
            margin-bottom: 20px;
            color: var(--secondary);
        }

        .content h2 {
            font-size: 28px;
            margin: 40px 0 15px;
            color: var(--secondary);
        }

        .content h3 {
            font-size: 22px;
            margin: 30px 0 10px;
            color: var(--secondary);
        }

        .content p {
            margin-bottom: 20px;
            color: var(--text-light);
        }

        .content ul, .content ol {
            margin: 0 0 20px 25px;
            color: var(--text-light);
        }

        .content li {
            margin-bottom: 8px;
        }

        .content img {
            border-radius: 8px;
            margin: 20px 0;
            box-shadow: 0 4px 20px var(--shadow);
        }

        .content blockquote {
            border-left: 4px solid var(--primary);
            padding: 15px 20px;
            margin: 20px 0;
            background: var(--bg-alt);
            font-style: italic;
            color: var(--text-light);
        }

        /* Sidebar */
        .sidebar {
            padding-left: 40px;
        }

        .sidebar-widget {
            background: var(--bg-alt);
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .sidebar-widget h3 {
            font-size: 16px;
            margin-bottom: 15px;
            color: var(--secondary);
            border-bottom: 2px solid var(--primary);
            padding-bottom: 10px;
        }

        .sidebar-widget ul {
            list-style: none;
        }

        .sidebar-widget li {
            margin-bottom: 10px;
        }

        .sidebar-widget a {
            color: var(--text-light);
            font-size: 14px;
        }

        .sidebar-widget a:hover {
            color: var(--primary);
        }

        /* Articles Grid */
        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        .article-card {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
        }

        .article-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px var(--shadow);
        }

        .article-card-image {
            height: 200px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        }

        .article-card-content {
            padding: 25px;
        }

        .article-card h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .article-card h3 a {
            color: var(--secondary);
        }

        .article-card p {
            color: var(--text-light);
            font-size: 14px;
            line-height: 1.6;
        }

        /* Breadcrumb */
        .breadcrumb {
            padding: 15px 0;
            font-size: 14px;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
        }

        .breadcrumb a {
            color: var(--text-light);
        }

        .breadcrumb span {
            margin: 0 8px;
        }

        /* Footer */
        .footer {
            background: var(--secondary);
            color: rgba(255, 255, 255, 0.7);
            padding: 50px 0 30px;
            margin-top: 60px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer h4 {
            color: white;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .footer ul {
            list-style: none;
        }

        .footer li {
            margin-bottom: 10px;
        }

        .footer a {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }

        .footer a:hover {
            color: white;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 20px;
            text-align: center;
            font-size: 14px;
        }

        /* Two Column Layout */
        .two-col {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 40px;
        }

        @media (max-width: 900px) {
            .two-col {
                grid-template-columns: 1fr;
            }
            .sidebar {
                padding-left: 0;
            }
        }

        @media (max-width: 768px) {
            .nav {
                display: none;
            }
            .hero h1 {
                font-size: 32px;
            }
            .content h1 {
                font-size: 28px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container header-inner">
            <a href="{{ url('/' . $currentLang) }}" class="logo">Ionize<span>vel</span></a>

            <nav class="nav">
                @if(isset($menus))
                    @foreach($menus->where('name', 'main')->first()?->pages ?? [] as $menuPage)
                        @php $menuTranslation = $menuPage->translate($currentLang); @endphp
                        <a href="{{ url($currentLang . '/' . ($menuTranslation->url ?? $menuPage->name)) }}"
                           class="{{ isset($page) && $page->id_page == $menuPage->id_page ? 'active' : '' }}">
                            {{ $menuTranslation->title ?? $menuPage->name }}
                        </a>
                    @endforeach
                @endif
            </nav>

            <div class="lang-switch">
                @foreach($languages as $language)
                    <a href="{{ url($language->lang . '/' . (isset($translation) ? $translation->url : '')) }}"
                       class="{{ $currentLang == $language->lang ? 'active' : '' }}">
                        {{ strtoupper($language->lang) }}
                    </a>
                @endforeach
            </div>
        </div>
    </header>

    @yield('content')

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <h4>{{ $siteName ?? 'Ionizevel' }}</h4>
                    <p>Laravel tabanlı modern CMS sistemi.</p>
                </div>

                <div>
                    <h4>Hızlı Bağlantılar</h4>
                    <ul>
                        @if(isset($menus))
                            @foreach($menus->where('name', 'main')->first()?->pages ?? [] as $menuPage)
                                @php $menuTranslation = $menuPage->translate($currentLang); @endphp
                                <li>
                                    <a href="{{ url($currentLang . '/' . ($menuTranslation->url ?? $menuPage->name)) }}">
                                        {{ $menuTranslation->title ?? $menuPage->name }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>

                <div>
                    <h4>İletişim</h4>
                    <ul>
                        <li>info@example.com</li>
                        <li>+90 123 456 7890</li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                &copy; {{ date('Y') }} {{ $siteName ?? 'Ionizevel' }}. Tüm hakları saklıdır.
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
