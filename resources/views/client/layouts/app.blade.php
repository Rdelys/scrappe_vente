<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Delegg Hub')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    
    <!-- Font Awesome & Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /*=============================================================================
          PROFESSIONAL LAYOUT - 100% RESPONSIVE WITH MOBILE DROPDOWN
          =============================================================================*/

        /*---------------------------------------
          DESIGN SYSTEM VARIABLES
        ---------------------------------------*/
        :root {
            /* Primary Colors */
            --primary-50: #eef2ff;
            --primary-100: #e0e7ff;
            --primary-200: #c7d2fe;
            --primary-300: #a5b4fc;
            --primary-400: #818cf8;
            --primary-500: #6366f1;
            --primary-600: #4f46e5;
            --primary-700: #4338ca;
            --primary-800: #3730a3;
            --primary-900: #312e81;
            
            /* Danger Colors */
            --danger-50: #fef2f2;
            --danger-100: #fee2e2;
            --danger-200: #fecaca;
            --danger-300: #fca5a5;
            --danger-400: #f87171;
            --danger-500: #ef4444;
            --danger-600: #dc2626;
            --danger-700: #b91c1c;
            
            /* Sidebar Colors */
            --sidebar-bg: #0f172a;
            --sidebar-bg-light: #1a253b;
            --sidebar-hover: #1e293b;
            --sidebar-active: #4f46e5;
            --sidebar-border: #334155;
            
            /* Text Colors */
            --text-primary: #e5e7eb;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --text-dark: #0f172a;
            
            /* Background Colors */
            --bg-main: #f1f5f9;
            --bg-card: #ffffff;
            --bg-hover: #f8fafc;
            
            /* Shadows */
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
            
            /* Border Radius */
            --radius-sm: 0.375rem;
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
            --radius-xl: 1rem;
            
            /* Transitions */
            --transition-base: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-menu: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            
            /* Spacing */
            --spacing-1: 0.25rem;
            --spacing-2: 0.5rem;
            --spacing-3: 0.75rem;
            --spacing-4: 1rem;
            --spacing-5: 1.25rem;
            --spacing-6: 1.5rem;
            --spacing-8: 2rem;
            --spacing-10: 2.5rem;
            --spacing-12: 3rem;
        }

        /*---------------------------------------
          RESET & BASE STYLES
        ---------------------------------------*/
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--bg-main);
            color: var(--text-dark);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
        }

        /*---------------------------------------
          MAIN LAYOUT
        ---------------------------------------*/
        .layout {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        /*---------------------------------------
          MOBILE HEADER (visible only on mobile)
        ---------------------------------------*/
        .mobile-header {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, var(--sidebar-bg-light) 100%);
            color: var(--text-primary);
            padding: var(--spacing-3) var(--spacing-4);
            z-index: 100;
            box-shadow: var(--shadow-lg);
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--sidebar-border);
        }

        .mobile-brand {
            font-size: 1.25rem;
            font-weight: 800;
            background: linear-gradient(135deg, #fff 0%, var(--primary-300) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .mobile-brand small {
            font-size: 0.7rem;
            display: block;
            color: var(--text-secondary);
            -webkit-text-fill-color: var(--text-secondary);
        }

        .menu-toggle-btn {
            background: transparent;
            border: 1px solid var(--sidebar-border);
            color: var(--text-primary);
            width: 40px;
            height: 40px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.25rem;
            transition: var(--transition-base);
        }

        .menu-toggle-btn:hover {
            background: var(--sidebar-hover);
            border-color: var(--primary-500);
        }

        .menu-toggle-btn i {
            transition: transform 0.3s ease;
        }

        .menu-toggle-btn.active i {
            transform: rotate(90deg);
        }

        /*---------------------------------------
          SIDEBAR - RESPONSIVE
        ---------------------------------------*/
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, var(--sidebar-bg-light) 100%);
            color: var(--text-primary);
            padding: var(--spacing-6) var(--spacing-4);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            box-shadow: var(--shadow-xl);
            border-right: 1px solid var(--sidebar-border);
            z-index: 90;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Custom Scrollbar for Sidebar */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--sidebar-border);
            border-radius: var(--radius-lg);
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--text-secondary);
        }

        .sidebar {
            scrollbar-width: thin;
            scrollbar-color: var(--sidebar-border) transparent;
        }

        /*---------------------------------------
          BRAND SECTION
        ---------------------------------------*/
        .brand {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: var(--spacing-8);
            text-align: left;
            padding: 0 var(--spacing-2);
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #fff 0%, var(--primary-300) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
            overflow: hidden;
        }

        .brand span {
            display: block;
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-top: var(--spacing-1);
            font-weight: 400;
            letter-spacing: normal;
            -webkit-text-fill-color: var(--text-secondary);
        }

        .brand::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: translateX(-100%);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            100% {
                transform: translateX(100%);
            }
        }

        /*---------------------------------------
          MENU STYLES
        ---------------------------------------*/
        .menu, .submenu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu > li {
            margin-bottom: var(--spacing-1);
        }

        /* Menu Links */
        .menu a {
            display: flex;
            align-items: center;
            gap: var(--spacing-3);
            padding: var(--spacing-3) var(--spacing-4);
            border-radius: var(--radius-lg);
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: var(--transition-base);
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .menu a i {
            width: 20px;
            font-size: 1rem;
            color: var(--text-muted);
            transition: var(--transition-base);
        }

        /* Menu item hover effect */
        .menu a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, var(--primary-600) 0%, transparent 100%);
            opacity: 0.1;
            transition: width 0.3s ease;
            z-index: -1;
        }

        .menu a:hover::before {
            width: 100%;
        }

        .menu a:hover {
            background: var(--sidebar-hover);
            color: var(--text-primary);
            transform: translateX(4px);
        }

        .menu a:hover i {
            color: var(--primary-400);
        }

        /* Active State */
        .menu a.active {
            background: var(--sidebar-active);
            color: white;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .menu a.active i {
            color: white;
        }

        .menu a.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: white;
            border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
        }

        /* Menu Item with Submenu Indicator */
        .menu > li > a:not(:only-child)::after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            margin-left: auto;
            font-size: 0.875rem;
            color: var(--text-muted);
            transition: transform 0.3s ease;
        }

        .menu > li > a.open:not(:only-child)::after {
            transform: rotate(-180deg);
            color: var(--primary-400);
        }

        /*---------------------------------------
          SUBMENU STYLES (CLICK TO OPEN)
        ---------------------------------------*/
        .submenu {
            margin-left: var(--spacing-8);
            margin-top: var(--spacing-1);
            margin-bottom: var(--spacing-1);
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                        opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: top;
        }

        .submenu.open {
            max-height: 500px;
            opacity: 1;
        }

        /* Nested Submenu (Third Level) */
        .submenu .submenu {
            margin-left: var(--spacing-6);
            margin-top: 0;
        }

        .submenu .submenu a {
            font-size: 0.8125rem;
            padding: var(--spacing-2) var(--spacing-3);
        }

        /* Submenu Links */
        .submenu a {
            padding: var(--spacing-2) var(--spacing-4);
            color: var(--text-muted);
            font-size: 0.8125rem;
            border-left: 1px solid transparent;
            transition: var(--transition-base);
            cursor: pointer;
        }

        .submenu a:hover {
            color: var(--text-primary);
            background: transparent;
            border-left-color: var(--primary-500);
            transform: translateX(2px);
        }

        .submenu a.active {
            background: transparent;
            color: var(--primary-400);
            border-left-color: var(--primary-500);
        }

        .submenu a.active i {
            color: var(--primary-400);
        }

        /* Submenu Icon */
        .submenu a i {
            font-size: 0.75rem;
            width: 16px;
        }

        /* Submenu item with nested submenu */
        .submenu > li > a:not(:only-child)::after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            margin-left: auto;
            font-size: 0.75rem;
            color: var(--text-muted);
            transition: transform 0.3s ease;
        }

        .submenu > li > a.open:not(:only-child)::after {
            transform: rotate(-180deg);
            color: var(--primary-400);
        }

        /*---------------------------------------
          LOGOUT BUTTON
        ---------------------------------------*/
        .logout {
            margin-top: auto;
            padding-top: var(--spacing-6);
            border-top: 1px solid var(--sidebar-border);
        }

        .logout button {
            width: 100%;
            background: transparent;
            border: 1px solid var(--sidebar-border);
            color: var(--text-secondary);
            padding: var(--spacing-3) var(--spacing-4);
            border-radius: var(--radius-lg);
            cursor: pointer;
            font-weight: 600;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-2);
            transition: var(--transition-base);
        }

        .logout button i {
            font-size: 1rem;
            transition: var(--transition-base);
        }

        .logout button:hover {
            background: var(--sidebar-hover);
            color: var(--text-primary);
            border-color: var(--danger-500);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
        }

        .logout button:hover i {
            transform: translateX(4px);
        }

        /*---------------------------------------
          MAIN CONTENT - SCROLLABLE
        ---------------------------------------*/
        .content {
            flex: 1;
            padding: var(--spacing-8);
            margin-left: 280px;
            min-height: 100vh;
            overflow-y: auto;
            background: var(--bg-main);
            transition: var(--transition-base);
        }

        /* Custom Scrollbar for Content */
        .content::-webkit-scrollbar {
            width: 8px;
        }

        .content::-webkit-scrollbar-track {
            background: var(--bg-main);
        }

        .content::-webkit-scrollbar-thumb {
            background: var(--primary-200);
            border-radius: var(--radius-lg);
            border: 2px solid var(--bg-main);
        }

        .content::-webkit-scrollbar-thumb:hover {
            background: var(--primary-400);
        }

        /*---------------------------------------
          CARD STYLES
        ---------------------------------------*/
        .card {
            background: var(--bg-card);
            border-radius: var(--radius-xl);
            padding: var(--spacing-8);
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: var(--transition-base);
        }

        .card:hover {
            box-shadow: var(--shadow-xl);
            transform: translateY(-2px);
        }

        /*---------------------------------------
          OVERLAY FOR MOBILE
        ---------------------------------------*/
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 80;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .sidebar-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        /*---------------------------------------
          ANIMATIONS
        ---------------------------------------*/
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .sidebar {
            animation: slideIn 0.3s ease-out;
        }

        /*---------------------------------------
          RESPONSIVE DESIGN - AMÉLIORÉ
        ---------------------------------------*/

        /* Tablettes et petits écrans */
        @media (max-width: 1024px) {
            .sidebar {
                width: 240px;
            }
            
            .content {
                margin-left: 240px;
                padding: var(--spacing-6);
            }
            
            .brand {
                font-size: 1.25rem;
            }
        }

        /* Mobile - Version avec dropdown */
        @media (max-width: 768px) {
            /* Afficher le header mobile */
            .mobile-header {
                display: flex;
            }

            /* Ajuster le layout */
            .layout {
                padding-top: 60px; /* Hauteur du header mobile */
            }

            /* Sidebar transformée en drawer */
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
                top: 60px; /* Sous le header mobile */
                height: calc(100vh - 60px);
                box-shadow: var(--shadow-xl);
                border-right: 1px solid var(--sidebar-border);
                border-top: 1px solid var(--sidebar-border);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            /* Contenu principal */
            .content {
                margin-left: 0;
                padding: var(--spacing-4);
                width: 100%;
            }

            /* Overlay actif */
            .sidebar-overlay {
                display: block;
            }

            /* Ajustements du menu */
            .submenu {
                margin-left: var(--spacing-6);
            }

            .submenu .submenu {
                margin-left: var(--spacing-4);
            }

            /* Cards responsives */
            .card {
                padding: var(--spacing-4);
            }

            /* Brand caché dans la sidebar en mobile */
            .sidebar .brand {
                display: block; /* On garde le brand dans la sidebar */
            }
        }

        /* Petits mobiles */
        @media (max-width: 480px) {
            .mobile-header {
                padding: var(--spacing-2) var(--spacing-3);
            }

            .mobile-brand {
                font-size: 1rem;
            }

            .mobile-brand small {
                font-size: 0.6rem;
            }

            .menu-toggle-btn {
                width: 36px;
                height: 36px;
                font-size: 1rem;
            }

            .sidebar {
                width: 100%; /* Sidebar en plein écran sur très petits écrans */
                max-width: 300px;
            }

            .menu a {
                padding: var(--spacing-2) var(--spacing-3);
                font-size: 0.8125rem;
            }
            
            .submenu a {
                font-size: 0.75rem;
                padding: var(--spacing-2) var(--spacing-3);
            }
            
            .content {
                padding: var(--spacing-3);
            }

            .card {
                padding: var(--spacing-3);
                border-radius: var(--radius-lg);
            }

            .logout button {
                padding: var(--spacing-2) var(--spacing-3);
                font-size: 0.8125rem;
            }
        }

        /* Très petits écrans */
        @media (max-width: 360px) {
            .sidebar {
                max-width: 260px;
            }

            .menu a {
                font-size: 0.75rem;
                padding: var(--spacing-2);
            }

            .menu a i {
                width: 16px;
                font-size: 0.875rem;
            }

            .submenu {
                margin-left: var(--spacing-4);
            }
        }

        /* Mode paysage sur mobile */
        @media (max-width: 768px) and (orientation: landscape) {
            .sidebar {
                width: 260px;
            }

            .content {
                padding: var(--spacing-3);
            }

            .menu a {
                padding: var(--spacing-2) var(--spacing-3);
            }
        }

        /*---------------------------------------
          FOCUS STATES
        ---------------------------------------*/
        :focus-visible {
            outline: 2px solid var(--primary-500);
            outline-offset: 2px;
        }

        /*---------------------------------------
          UTILITY CLASSES
        ---------------------------------------*/
        .text-primary {
            color: var(--primary-600);
        }

        .bg-primary {
            background: var(--primary-600);
        }

        .menu-separator {
            height: 1px;
            background: var(--sidebar-border);
            margin: var(--spacing-4) 0;
        }

        /* Support des grands écrans */
        @media (min-width: 1920px) {
            .sidebar {
                width: 320px;
            }

            .content {
                margin-left: 320px;
                padding: var(--spacing-10);
            }

            .menu a {
                font-size: 1rem;
                padding: var(--spacing-4) var(--spacing-5);
            }

            .submenu a {
                font-size: 0.9375rem;
            }

            .brand {
                font-size: 1.75rem;
            }
        }

        /* Impression */
        @media print {
            .sidebar,
            .mobile-header,
            .logout {
                display: none;
            }

            .content {
                margin-left: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>

<!-- Mobile Header (visible uniquement sur mobile) -->
<div class="mobile-header">
    <div class="mobile-brand">
        DELEGG HUB
        <small>{{ session('client.company') }}</small>
    </div>
    <button class="menu-toggle-btn" id="mobileMenuToggle" aria-label="Menu">
        <i class="fa-solid fa-bars"></i>
    </button>
</div>

<!-- Overlay pour mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="layout">
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            DELEGG HUB
            <span>{{ session('client.company') }}</span>
        </div>

        <ul class="menu" id="mainMenu">
            <!-- CRM -->
            <li>
                <a href="#" class="menu-toggle" data-target="crm-submenu">
                    <i class="fa-solid fa-chart-line"></i> CRM
                </a>
                <ul class="submenu" id="crm-submenu">
                    @if(session('client.role') === 'superadmin')
                    <li><a href="{{ route('client.crm.dashboard') }}">Dashboard</a></li>
                    @endif
                    <li><a href="{{ route('client.crm.leads') }}">Mes Leads</a></li>
                </ul>
            </li>

            <!-- MAILS -->
            <li>
                <a href="#" class="menu-toggle" data-target="mails-submenu">
                    <i class="fa-solid fa-envelope"></i> Mails
                </a>
                <ul class="submenu" id="mails-submenu">
                    <li><a href="{{ route('client.mails.programmes') }}">Mes mails programmés</a></li>
                    <li>
                        <a href="#" class="menu-toggle" data-target="inbox-submenu">
                            Boite de réception
                        </a>
                        <ul class="submenu" id="inbox-submenu">
                            <li><a href="{{ route('client.mails.envoyes') }}">Mails envoyés</a></li>
                            <li><a href="{{ route('client.mails.recus') }}">Mails reçus</a></li>
                        </ul>
                    </li>
                </ul>
            </li>

            <!-- SCRAPPING -->
            <li>
                <a href="#" class="menu-toggle" data-target="scraping-submenu">
                    <i class="fa-solid fa-magnifying-glass"></i> SCRAPPING
                </a>
                <ul class="submenu" id="scraping-submenu">
                    <li><a href="{{ route('client.google') }}">Google Maps</a></li>
                    <li><a href="{{ route('client.web') }}">Site Web</a></li>
                </ul>
            </li>

            <!-- PARAMETRES -->
            <li>
                <a href="#" class="menu-toggle" data-target="params-submenu">
                    <i class="fa-solid fa-gear"></i> Paramètres
                </a>
                <ul class="submenu" id="params-submenu">
                    <li><a href="{{ route('client.profil') }}">Profil</a></li>
                    @if(session('client.role') === 'superadmin')
                        <li><a href="{{ route('client.users') }}">Utilisateurs</a></li>
                    @endif
                </ul>
            </li>
        </ul>

        <div class="logout">
            <form method="POST" action="{{ route('client.logout') }}">
                @csrf
                <button type="submit">
                    <i class="fa-solid fa-right-from-bracket"></i> Déconnexion
                </button>
            </form>
        </div>
    </aside>

    <main class="content">
        @yield('content')
    </main>
</div>

<script>
(function() {
    'use strict';

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        initializeMobileMenu();
        initializeMenuSystem();
        initializeActiveLinks();
        handleResize();
    });

    // Gestion du menu mobile (dropdown)
    function initializeMobileMenu() {
        const mobileToggle = document.getElementById('mobileMenuToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        
        if (!mobileToggle || !sidebar || !overlay) return;

        // Ouvrir/fermer le menu mobile
        mobileToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const isActive = sidebar.classList.contains('active');
            
            if (!isActive) {
                // Ouvrir
                sidebar.classList.add('active');
                overlay.classList.add('active');
                mobileToggle.classList.add('active');
                document.body.style.overflow = 'hidden'; // Empêcher le scroll
            } else {
                // Fermer
                closeMobileMenu();
            }
        });

        // Fermer avec l'overlay
        overlay.addEventListener('click', function() {
            closeMobileMenu();
        });

        // Fermer avec la touche Echap
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar.classList.contains('active')) {
                closeMobileMenu();
            }
        });

        function closeMobileMenu() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            mobileToggle.classList.remove('active');
            document.body.style.overflow = '';
        }

        // Fermer le menu après avoir cliqué sur un lien (mobile)
        const menuLinks = sidebar.querySelectorAll('a:not(.menu-toggle)');
        menuLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    closeMobileMenu();
                }
            });
        });
    }

    // Initialize click-to-open menu system
    function initializeMenuSystem() {
        const menuToggles = document.querySelectorAll('.menu-toggle');
        
        menuToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const targetId = this.dataset.target;
                const targetMenu = document.getElementById(targetId);
                
                if (targetMenu) {
                    // Toggle current menu
                    targetMenu.classList.toggle('open');
                    this.classList.toggle('open');
                    
                    // Close sibling menus at the same level
                    const parent = this.closest('li');
                    if (parent) {
                        const siblingMenus = parent.parentElement.querySelectorAll(':scope > li > .submenu');
                        siblingMenus.forEach(menu => {
                            if (menu.id !== targetId) {
                                menu.classList.remove('open');
                                const siblingToggle = menu.parentElement.querySelector('.menu-toggle');
                                if (siblingToggle) {
                                    siblingToggle.classList.remove('open');
                                }
                            }
                        });
                    }
                    
                    // Animate icon rotation
                    const icon = this.querySelector('i:first-child');
                    if (icon) {
                        icon.style.transform = targetMenu.classList.contains('open') ? 'rotate(90deg)' : 'rotate(0)';
                        setTimeout(() => {
                            icon.style.transform = '';
                        }, 300);
                    }
                }
            });
        });
    }

    // Initialize active links based on current URL
    function initializeActiveLinks() {
        const currentPath = window.location.pathname;
        const menuLinks = document.querySelectorAll('.menu a:not(.menu-toggle)');
        
        menuLinks.forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                // Mark link as active
                link.classList.add('active');
                
                // Open all parent menus
                let parent = link.closest('.submenu');
                while (parent) {
                    parent.classList.add('open');
                    
                    // Find and mark parent toggle as open
                    const parentLi = parent.closest('li');
                    if (parentLi) {
                        const parentToggle = parentLi.querySelector(':scope > .menu-toggle');
                        if (parentToggle) {
                            parentToggle.classList.add('open');
                        }
                    }
                    
                    parent = parent.parentElement.closest('.submenu');
                }
            }
        });
    }

    // Gestion du redimensionnement
    function handleResize() {
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebarOverlay');
                const mobileToggle = document.getElementById('mobileMenuToggle');
                
                if (window.innerWidth > 768) {
                    // Mode desktop
                    if (sidebar) sidebar.classList.remove('active');
                    if (overlay) overlay.classList.remove('active');
                    if (mobileToggle) mobileToggle.classList.remove('active');
                    document.body.style.overflow = '';
                }
            }, 250);
        });
    }

    // Optional: Close menus when clicking outside (desktop)
    document.addEventListener('click', function(e) {
        if (window.innerWidth > 768) {
            if (!e.target.closest('.sidebar')) {
                // Optionnel: fermer tous les menus quand on clique ailleurs
                // const openMenus = document.querySelectorAll('.submenu.open');
                // openMenus.forEach(menu => {
                //     menu.classList.remove('open');
                //     const toggle = document.querySelector(`[data-target="${menu.id}"]`);
                //     if (toggle) {
                //         toggle.classList.remove('open');
                //     }
                // });
            }
        }
    });

    // Sauvegarde de l'état du menu (optionnel)
    function saveMenuState() {
        const openMenus = [];
        document.querySelectorAll('.submenu.open').forEach(menu => {
            openMenus.push(menu.id);
        });
        localStorage.setItem('openMenus', JSON.stringify(openMenus));
    }

    function loadMenuState() {
        const savedMenus = localStorage.getItem('openMenus');
        if (savedMenus) {
            const openMenus = JSON.parse(savedMenus);
            openMenus.forEach(menuId => {
                const menu = document.getElementById(menuId);
                const toggle = document.querySelector(`[data-target="${menuId}"]`);
                if (menu && toggle) {
                    menu.classList.add('open');
                    toggle.classList.add('open');
                }
            });
        }
    }

    // Décommenter pour activer la persistance
    // loadMenuState();
    // 
    // window.addEventListener('beforeunload', function() {
    //     saveMenuState();
    // });

})();
</script>

</body>
</html>