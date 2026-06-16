<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - Inventario')</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Base CSS -->
    <style>
        :root {
            --bg-color: #f7f8fa;
            --sidebar-bg: #27282b;
            --sidebar-hover: #36373b;
            --text-main: #1d1e20;
            --text-muted: #8d929a;
            --sidebar-text: #a4a8b2;
            --sidebar-active-text: #a6c64b;
            --accent-color: #a6c64b;
            --card-bg: #ffffff;
            --border-color: #e5e7eb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--sidebar-bg);
            color: var(--text-main);
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* Resets for Bootstrap interference from child views */
        aside ul,
        aside li,
        aside p,
        aside h6,
        aside h4 {
            margin: 0;
            padding: 0;
        }

        aside a {
            text-decoration: none !important;
        }

        /* Sidebar container */
        aside {
            width: 260px;
            background-color: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            padding: 24px 0;
            overflow-y: auto;
            flex-shrink: 0;
        }

        /* Logo */
        .logo-container {
            padding: 0 24px 32px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            background: var(--accent-color);
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: var(--sidebar-bg);
        }

        .logo-text {
            color: white;
            font-size: 20px;
            font-weight: 600;
        }

        /* Menu */
        .menu-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--sidebar-text);
            padding: 0 24px 8px;
            margin-top: 16px;
            font-weight: 500;
        }

        .sidebar-menu {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding: 0 12px !important;
            margin: 0 !important;
        }

        .sidebar-item {
            border-radius: 8px;
        }

        .sidebar-link {
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
            padding: 12px !important;
            color: var(--sidebar-text);
            text-decoration: none !important;
            font-size: 15px;
            font-weight: 400;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .sidebar-link:hover {
            background-color: var(--sidebar-hover);
            color: white;
        }

        .sidebar-link.active {
            color: var(--sidebar-active-text) !important;
            font-weight: 500;
            border-left: 3px solid var(--sidebar-active-text) !important;
            border-radius: 4px 8px 8px 4px;
            background: rgba(255, 255, 255, 0.02);
        }

        .sidebar-link.active i {
            color: var(--sidebar-active-text) !important;
        }

        .sidebar-link i {
            font-size: 20px;
            color: var(--sidebar-text);
            margin-top: -2px;
            /* Fixes slight vertical misalignment with flex */
        }

        /* Submenus */
        .submenu {
            list-style: none;
            padding-left: 36px !important;
            display: none;
            flex-direction: column;
            gap: 4px;
            margin: 4px 0 0 0 !important;
        }

        .has-submenu.open .submenu {
            display: flex;
        }

        .submenu-link {
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 14px;
            padding: 8px 12px;
            border-radius: 6px;
            display: block;
        }

        .submenu-link:hover {
            color: white;
            background: var(--sidebar-hover);
        }

        /* Sidebar Footer (Pro Banner & User) */
        .sidebar-footer {
            margin-top: auto;
            padding: 24px 16px 0;
        }


        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 8px 16px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-info h6 {
            color: white;
            font-size: 14px;
            font-weight: 500;
            margin: 0 0 2px 0 !important;
        }

        .user-info span {
            color: var(--sidebar-text);
            font-size: 12px;
        }

        /* Main Content */
        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background: var(--bg-color);
            border-radius: 24px 0 0 24px;
            margin: 12px 0 12px 0;
            box-shadow: -4px 0 15px rgba(0, 0, 0, 0.05);
            transition: margin 0.3s, border-radius 0.3s;
        }

        /* Header */
        header {
            padding: 24px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            border-top-left-radius: 24px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .hamburger {
            display: none;
            background: transparent;
            border: none;
            color: var(--text-main);
            font-size: 28px;
            cursor: pointer;
        }

        .header-title {
            font-size: 24px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .search-bar {
            background: var(--bg-color);
            padding: 10px 16px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            width: 300px;
        }

        .search-bar i {
            color: var(--text-muted);
        }

        .search-bar input {
            border: none;
            background: transparent;
            outline: none;
            font-size: 14px;
            width: 100%;
            color: var(--text-main);
        }

        .header-icons {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-icon {
            color: var(--text-muted);
            font-size: 22px;
            cursor: pointer;
            transition: 0.2s;
            position: relative;
        }

        .header-icon:hover {
            color: var(--text-main);
        }

        .badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: #ff4757;
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .btn-primary {
            background: var(--sidebar-bg);
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: transform 0.1s, background 0.2s;
        }

        .btn-primary:active {
            transform: scale(0.98);
        }

        .btn-primary:hover {
            background: #111;
        }

        /* Content Area */
        .content-area {
            flex: 1;
            padding: 32px;
            overflow-y: auto;
            background-color: white;
            border-top-left-radius: 20px;
        }

        /* Scrollbar styles for sidebar and content */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        aside::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Mobile Styles */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
            display: none;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .sidebar-overlay.show {
            display: block;
            opacity: 1;
        }

        @media (max-width: 991px) {
            aside {
                position: fixed;
                left: -260px;
                top: 0;
                bottom: 0;
                z-index: 999;
                transition: left 0.3s;
            }

            aside.show {
                left: 0;
            }

            main {
                margin: 0;
                border-radius: 0;
            }

            header {
                padding: 16px 20px;
                border-top-left-radius: 0;
            }

            .hamburger {
                display: block;
            }

            .header-actions {
                gap: 12px;
            }

            .search-bar {
                display: none;
                /* Hide search on mobile, or make it small */
            }

            .btn-primary span {
                display: none;
            }

            .header-title {
                font-size: 18px;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <aside>
        <div class="logo-container">
            <div class="logo-icon"><i class='bx bx-cube'></i></div>
            <div class="logo-text"><img src="imagenes/tubosalogo.png" width="120px" alt=""></div>
        </div>

        <div class="menu-title">MENU</div>
        <ul class="sidebar-menu">
            <li class="sidebar-item">
                <a href="{{ route('dashboard') }}" class="sidebar-link active">
                    <i class='bx bx-home-alt'></i> Dashboard
                </a>
            </li>
            <li class="sidebar-item has-submenu">
                <a href="#" class="sidebar-link" onclick="toggleSubmenu(event)">
                    <i class='bx bx-box'></i> Inventario <i class='bx bx-chevron-down' style="margin-left: auto;"></i>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('inventario.index', ['tipo' => 'ciclico']) }}" class="submenu-link">Cíclico</a></li>
                    <li><a href="{{ route('inventario.index', ['tipo' => 'general']) }}" class="submenu-link">General</a></li>
                </ul>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('materiales.index') }}" class="sidebar-link">
                    <i class='bx bx-qr-scan'></i> Materiales
                </a>
            </li>
            <li class="sidebar-item">
                <a href="#" class="sidebar-link">
                    <i class='bx bx-pie-chart-alt-2'></i> Reportes
                </a>
            </li>
        </ul>

        <div class="menu-title" style="margin-top: 32px;">GENERAL</div>
        <ul class="sidebar-menu">
            <li class="sidebar-item">
                <a href="#" class="sidebar-link">
                    <i class='bx bx-user'></i> Cuenta
                </a>
            </li>
            <li class="sidebar-item">
                <a href="/admin/users" class="sidebar-link">
                    <i class='bx bx-group'></i> Gestión Usuario
                </a>
            </li>
            <li class="sidebar-item" style="margin-top: 16px;">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a href="#" class="sidebar-link"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    style="color: rgba(239, 68, 68, 0.9);">
                    <i class='bx bx-log-out-circle' style="color: rgba(239, 68, 68, 0.9);"></i> Cerrar Sesión
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <div class="user-profile">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? Auth::user()->username ?? 'Usuario') }}&background=random"
                    alt="User" class="user-avatar">
                <div class="user-info">
                    <h6>{{ Auth::user()->name ?? Auth::user()->username ?? 'Usuario' }}</h6>
                    <span>{{ Auth::user() && Auth::user()->roles->count() > 0 ? Auth::user()->roles->first()->name : 'Rol Usuario' }}</span>
                </div>
            </div>
        </div>
    </aside>

    <main>
        <header>
            <div class="header-left">
                <button class="hamburger" onclick="toggleSidebar()">
                    <i class='bx bx-menu'></i>
                </button>
                <div class="header-title">
                    <i class='bx bxs-sun' style="color: #f1c40f;"></i>
                    Hola {{ Auth::user()->name ?? Auth::user()->username ?? 'Usuario' }} 👋
                </div>
            </div>

            <div class="header-actions">
                <div class="search-bar">
                    <i class='bx bx-search'></i>
                    <input type="text" placeholder="Buscar aqui">
                </div>

                <div class="header-icons">
                    <div
                        style="padding: 8px; border: 1px solid var(--border-color); border-radius: 8px; display: flex; cursor: pointer;">
                        <i class='bx bx-slider' style="font-size: 18px; color: var(--text-main);"></i>
                    </div>
                    <div class="header-icon"><i class='bx bx-bell'></i>
                        <div class="badge" style="display:none;"></div>
                    </div>
                    <div class="header-icon"><i class='bx bx-envelope'></i>
                        <div class="badge"></div>
                    </div>
                </div>

                <button class="btn-primary">
                    <span style="font-family: inherit;">Iniciar Inventario</span> <i class='bx bx-plus-circle'></i>
                </button>
            </div>
        </header>

        <div class="content-area">
            @yield('content')
        </div>
    </main>

    <script>
        function toggleSubmenu(e) {
            e.preventDefault();
            const parentLi = e.currentTarget.parentElement;
            parentLi.classList.toggle('open');
            const icon = e.currentTarget.querySelector('.bx-chevron-down, .bx-chevron-up');
            if (icon) {
                if (parentLi.classList.contains('open')) {
                    icon.classList.replace('bx-chevron-down', 'bx-chevron-up');
                } else {
                    icon.classList.replace('bx-chevron-up', 'bx-chevron-down');
                }
            }
        }

        function toggleSidebar() {
            const sidebar = document.querySelector('aside');
            const overlay = document.querySelector('.sidebar-overlay');
            sidebar.classList.toggle('show');

            if (sidebar.classList.contains('show')) {
                overlay.classList.add('show');
                // document.body.style.overflow = 'hidden'; 
            } else {
                overlay.classList.remove('show');
                // document.body.style.overflow = '';
            }
        }
    </script>
</body>

</html>