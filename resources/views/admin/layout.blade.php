<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Follup.io | Admin')</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Variables et Reset */
        :root {
            --primary-blue: #2563eb;
            --primary-blue-dark: #1d4ed8;
            --primary-blue-light: #60a5fa;
            --primary-blue-gradient: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            --sidebar-bg: #ffffff;
            --content-bg: #f8fafc;
            --card-bg: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-light: #94a3b8;
            --border-color: #e2e8f0;
            --hover-bg: #f1f5f9;
            --active-bg: #eff6ff;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 20px rgba(0,0,0,0.08);
            --shadow-lg: 0 12px 40px rgba(0,0,0,0.12);
            --radius-sm: 10px;
            --radius-md: 14px;
            --radius-lg: 18px;
            --sidebar-width: 280px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: var(--content-bg);
            color: var(--text-primary);
            line-height: 1.6;
            height: 100vh;
            overflow: hidden;
            display: flex;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: var(--transition);
            box-shadow: var(--shadow-md);
        }

        .brand {
            padding: 28px 24px;
            border-bottom: 1px solid var(--border-color);
            background: var(--sidebar-bg);
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .brand-logo {
            width: 42px;
            height: 42px;
            background: var(--primary-blue-gradient);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            font-weight: 700;
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
        }

        .brand-text {
            display: flex;
            flex-direction: column;
        }

        .brand h1 {
            font-size: 22px;
            font-weight: 800;
            color: var(--primary-blue);
            line-height: 1.2;
        }

        .brand-subtitle {
            font-size: 12px;
            color: var(--text-light);
            font-weight: 500;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .menu {
            flex: 1;
            padding: 24px 16px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            overflow-y: auto;
        }

        .menu-section {
            margin: 20px 0 12px;
            padding: 0 12px;
        }

        .menu-label {
            font-size: 11px;
            color: var(--text-light);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 20px;
            border-radius: var(--radius-md);
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .menu-item:hover {
            background: var(--hover-bg);
            color: var(--primary-blue);
            transform: translateX(6px);
            box-shadow: var(--shadow-sm);
        }

        .menu-item.active {
            background: var(--active-bg);
            color: var(--primary-blue);
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.1);
        }

        .menu-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 60%;
            background: var(--primary-blue);
            border-radius: 0 4px 4px 0;
        }

        .menu-item i {
            font-size: 18px;
            width: 24px;
            text-align: center;
            flex-shrink: 0;
        }

        .menu-badge {
            margin-left: auto;
            background: #e2e8f0;
            color: var(--text-secondary);
            font-size: 11px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            min-width: 24px;
            text-align: center;
        }

        .menu-item.active .menu-badge {
            background: var(--primary-blue);
            color: white;
        }

        .logout-section {
            padding: 24px;
            border-top: 1px solid var(--border-color);
            background: linear-gradient(to top, rgba(255,255,255,0.9), rgba(248,250,252,0.9));
            backdrop-filter: blur(10px);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 20px;
            padding: 12px;
            border-radius: var(--radius-md);
            background: var(--hover-bg);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-blue-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .user-info h4 {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .user-info p {
            font-size: 12px;
            color: var(--text-light);
        }

        .logout-btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: var(--radius-md);
            background: transparent;
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            border: 1px solid var(--border-color);
        }

        .logout-btn:hover {
            background: #fee2e2;
            color: #dc2626;
            border-color: #fecaca;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(220, 38, 38, 0.1);
        }

        /* Main Content */
        .content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 0;
            height: 100vh;
            overflow-y: auto;
            transition: margin-left 0.3s ease;
            position: relative;
        }

        .content-header {
            background: var(--card-bg);
            padding: 24px 40px;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 50;
            backdrop-filter: blur(20px);
            background: rgba(255,255,255,0.85);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-left h2 {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-left h2 i {
            color: var(--primary-blue);
            font-size: 26px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .time-display {
            font-size: 14px;
            color: var(--text-secondary);
            font-weight: 500;
            background: var(--hover-bg);
            padding: 8px 16px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
        }

        .content-body {
            padding: 32px 40px;
        }

        /* Cards */
        .card {
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            padding: 32px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            transition: var(--transition);
            margin-bottom: 24px;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-4px);
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .card-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .card-title i {
            color: var(--primary-blue);
            font-size: 22px;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .content {
                margin-left: 0;
            }
            
            .menu-toggle {
                display: flex;
            }
        }

        @media (max-width: 768px) {
            .content-body {
                padding: 24px;
            }
            
            .content-header {
                padding: 20px 24px;
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }
            
            .header-left h2 {
                font-size: 24px;
            }
            
            .header-right {
                width: 100%;
                justify-content: space-between;
            }
            
            .card {
                padding: 24px;
            }
        }

        @media (max-width: 480px) {
            .content-body {
                padding: 20px;
            }
            
            .card {
                padding: 20px;
                margin-bottom: 20px;
            }
            
            .brand {
                padding: 24px 20px;
            }
            
            .menu-item {
                padding: 14px 16px;
            }
            
            .content-header {
                padding: 20px;
            }
        }

        /* Mobile Menu Toggle */
        .menu-toggle {
            display: none;
            position: fixed;
            top: 24px;
            left: 24px;
            z-index: 1001;
            background: var(--primary-blue-gradient);
            color: white;
            border: none;
            border-radius: var(--radius-sm);
            width: 48px;
            height: 48px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
        }

        .menu-toggle:hover {
            transform: scale(1.05);
            box-shadow: var(--shadow-lg);
        }

        @media (max-width: 1200px) {
            .menu-toggle {
                display: flex;
            }
        }

        /* Overlay for mobile */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 999;
            backdrop-filter: blur(4px);
        }

        @media (max-width: 1200px) {
            .overlay.active {
                display: block;
            }
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-blue-light);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-blue-dark);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .content-body > * {
            animation: fadeIn 0.5s ease forwards;
        }

        /* Empty state styling */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-light);
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-state h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: var(--text-secondary);
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="menu-toggle" id="menuToggle">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Mobile Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <div class="brand-logo">DH</div>
            <div class="brand-text">
                <h1>Follup.io</h1>
                <div class="brand-subtitle">Admin Panel</div>
            </div>
        </div>

        <nav class="menu">
            <div class="menu-section">
                <div class="menu-label">Principal</div>
                <a href="/admin/dashboard" class="menu-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-label">Gestion</div>
                <a href="/admin/clients" class="menu-item {{ request()->is('admin/clients') ? 'active' : '' }}">
                    <i class="fas fa-building"></i>
                    <span>Clients</span>
                    <span class="menu-badge">{{ $clientsCount }}</span>
                </a>

                <a href="/admin/licences" class="menu-item {{ request()->is('admin/licences') ? 'active' : '' }}">
                    <i class="fas fa-key"></i>
                    <span>Licences</span>
                    <span class="menu-badge">{{ $licencesCount }}</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-label">Configuration</div>
                <a href="/admin/settings" class="menu-item {{ request()->is('admin/settings') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>Paramètres</span>
                </a>
                <a href="/admin/analytics" class="menu-item {{ request()->is('admin/analytics') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Analytics</span>
                </a>
                <a href="/admin/logs" class="menu-item {{ request()->is('admin/logs') ? 'active' : '' }}">
                    <i class="fas fa-history"></i>
                    <span>Logs</span>
                </a>
            </div>
        </nav>

        <div class="logout-section">
            <div class="user-profile">
                <div class="user-avatar">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="user-info">
                    <h4>Administrateur</h4>
                    <p>Super Admin</p>
                </div>
            </div>
            
            <form method="POST" action="/admin/logout">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Se déconnecter</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="content">
        <div class="content-header">
            <div class="header-left">
                <h2>
                    <i class="fas fa-tachometer-alt"></i>
                    @yield('page_title', 'Tableau de bord')
                </h2>
            </div>
            <div class="header-right">
                <div class="time-display" id="timeDisplay">
                    <i class="far fa-clock"></i>
                    <span id="currentTime"></span>
                </div>
            </div>
        </div>
        
        <div class="content-body">
            @yield('content')
        </div>
    </main>

    <script>
        // Mobile menu toggle
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const body = document.body;

        function toggleMenu() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
        }

        menuToggle.addEventListener('click', toggleMenu);
        overlay.addEventListener('click', toggleMenu);

        // Close menu when clicking on a menu item (mobile)
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', () => {
                if (window.innerWidth <= 1200) {
                    toggleMenu();
                }
            });
        });

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 1200) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                body.style.overflow = '';
            }
        });

        // Add active state based on current URL
        document.addEventListener('DOMContentLoaded', () => {
            const currentPath = window.location.pathname;
            document.querySelectorAll('.menu-item').forEach(item => {
                if (item.getAttribute('href') === currentPath) {
                    item.classList.add('active');
                }
            });
        });

        // Add hover effect to cards
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-4px)';
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
            });
        });

        // Update time display
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('fr-FR', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });
            document.getElementById('currentTime').textContent = timeString;
        }

        // Update time immediately and every minute
        updateTime();
        setInterval(updateTime, 60000);
    </script>
</body>
</html>