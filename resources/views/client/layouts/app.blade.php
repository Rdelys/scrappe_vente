<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Follup.io')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    
    <!-- Font Awesome & Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /*=============================================================================
          ULTRA MODERN PROFESSIONAL LAYOUT - 2025 DESIGN TRENDS
          =============================================================================*/

        /*---------------------------------------
          DESIGN SYSTEM VARIABLES - NOUVELLE PALETTE
        ---------------------------------------*/
        :root {
            /* Primary Colors - Élégant violet/indigo */
            --primary-50: #f3f1ff;
            --primary-100: #ebe5ff;
            --primary-200: #d9ceff;
            --primary-300: #bea6ff;
            --primary-400: #a075ff;
            --primary-500: #884dff;
            --primary-600: #7735e0;
            --primary-700: #6622cc;
            --primary-800: #4f17a3;
            --primary-900: #361273;
            
            /* Secondary Colors - Bleu océan sophistiqué */
            --secondary-50: #eef8ff;
            --secondary-100: #dcf0ff;
            --secondary-200: #bae2ff;
            --secondary-300: #8fcaff;
            --secondary-400: #5aa9ff;
            --secondary-500: #3b82f6;
            --secondary-600: #2563eb;
            --secondary-700: #1d4ed8;
            --secondary-800: #1e3a8a;
            --secondary-900: #172554;

            /* Accent Colors - Rose gold */
            --accent-50: #fff1f4;
            --accent-100: #ffe4e8;
            --accent-200: #fecdd6;
            --accent-300: #fda4b4;
            --accent-400: #fb7185;
            --accent-500: #f43f5e;
            --accent-600: #e11d48;
            --accent-700: #be123c;
            --accent-800: #9f1239;
            --accent-900: #881337;

            /* Sidebar Colors - Dégradé moderne */
            --sidebar-bg: #0B1120;
            --sidebar-bg-light: #1A2332;
            --sidebar-bg-lighter: #252F3F;
            --sidebar-hover: #2D374B;
            --sidebar-active: linear-gradient(135deg, var(--primary-600) 0%, var(--secondary-600) 100%);
            --sidebar-active-glow: rgba(136, 77, 255, 0.3);
            --sidebar-border: rgba(255, 255, 255, 0.08);
            
            /* Text Colors */
            --text-primary: #FFFFFF;
            --text-secondary: #E2E8F0;
            --text-muted: #94A3B8;
            --text-dark: #0F172A;
            --text-light: #F8FAFC;
            
            /* Background Colors */
            --bg-main: #F9FAFF;
            --bg-card: #FFFFFF;
            
            /* Shadows */
            --shadow-sm: 0 2px 4px 0 rgba(0, 0, 0, 0.02);
            --shadow-md: 0 4px 8px 0 rgba(0, 0, 0, 0.03);
            --shadow-lg: 0 8px 16px 0 rgba(0, 0, 0, 0.04);
            --shadow-xl: 0 20px 24px -4px rgba(0, 0, 0, 0.06);
            --shadow-2xl: 0 25px 30px -12px rgba(0, 0, 0, 0.15);
            --shadow-glow: 0 0 0 3px rgba(136, 77, 255, 0.15);
            
            /* Border Radius */
            --radius-sm: 0.5rem;
            --radius-md: 0.75rem;
            --radius-lg: 1rem;
            --radius-xl: 1.25rem;
            --radius-2xl: 1.5rem;
            --radius-3xl: 2rem;
            --radius-full: 9999px;
            
            /* Transitions */
            --transition-base: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-smooth: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-bounce: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            --transition-elastic: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            
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
            
            /* Sidebar widths */
            --sidebar-width-expanded: 320px;
            --sidebar-width-collapsed: 100px;
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
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, var(--bg-main) 0%, #f0f3ff 100%);
            color: var(--text-dark);
            line-height: 1.6;
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
          MOBILE HEADER
        ---------------------------------------*/
        .mobile-header {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(11, 17, 32, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            color: var(--text-primary);
            padding: var(--spacing-4) var(--spacing-6);
            z-index: 100;
            box-shadow: var(--shadow-xl);
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .mobile-brand {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #ffffff 0%, var(--primary-300) 50%, var(--secondary-300) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientFlow 3s ease infinite;
        }

        .mobile-brand small {
            font-size: 0.75rem;
            display: block;
            color: var(--text-secondary);
            -webkit-text-fill-color: var(--text-secondary);
            font-weight: 400;
        }

        @keyframes gradientFlow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .menu-toggle-btn {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
            width: 44px;
            height: 44px;
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.25rem;
            transition: var(--transition-bounce);
            backdrop-filter: blur(10px);
        }

        .menu-toggle-btn:hover {
            background: var(--sidebar-active);
            border-color: transparent;
            transform: scale(1.1) rotate(90deg);
            box-shadow: 0 0 20px var(--sidebar-active-glow);
        }

        /*---------------------------------------
          SIDEBAR - WITH PREMIUM COLLAPSE FEATURE
        ---------------------------------------*/
        .sidebar {
            width: var(--sidebar-width-expanded);
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, var(--sidebar-bg-light) 100%);
            color: var(--text-primary);
            padding: var(--spacing-8) var(--spacing-5);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 20px 0 30px -10px rgba(0, 0, 0, 0.15);
            border-right: 1px solid var(--sidebar-border);
            z-index: 90;
            transition: width 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .sidebar.collapsed {
            width: var(--sidebar-width-collapsed);
        }

        /* Premium Collapse Toggle Button - Flèche qui change de direction */
        .sidebar-toggle {
            position: absolute;
            top: 30px;
            right: -15px;
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
            border: 3px solid var(--sidebar-bg);
            border-radius: 50%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 16px;
            z-index: 100;
            transition: var(--transition-elastic);
            box-shadow: 0 4px 15px rgba(136, 77, 255, 0.4);
            animation: togglePulse 2s infinite;
        }

        @keyframes togglePulse {
            0%, 100% {
                box-shadow: 0 4px 15px rgba(136, 77, 255, 0.4);
            }
            50% {
                box-shadow: 0 4px 25px rgba(136, 77, 255, 0.8);
            }
        }

        .sidebar-toggle:hover {
            transform: scale(1.2);
            background: linear-gradient(135deg, var(--accent-500), var(--primary-500));
            box-shadow: 0 0 30px var(--accent-400);
            animation: none;
        }

        /* La flèche change de direction quand la sidebar est réduite */
        .sidebar.collapsed .sidebar-toggle i {
            transform: rotate(180deg);
        }

        .sidebar-toggle i {
            transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        }

        .sidebar-toggle:hover i {
            transform: scale(1.1);
        }

        .sidebar.collapsed .sidebar-toggle:hover i {
            transform: rotate(180deg) scale(1.1);
        }

        /* Custom Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: var(--radius-full);
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, var(--primary-500) 0%, var(--secondary-500) 100%);
        }

        /*---------------------------------------
          BRAND SECTION - DYNAMIC
        ---------------------------------------*/
        .brand {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: var(--spacing-12);
            text-align: left;
            padding: 0 var(--spacing-3);
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #ffffff 0%, var(--primary-300) 25%, var(--secondary-300) 50%, var(--accent-300) 75%, #ffffff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            background-size: 300% auto;
            animation: gradientShift 6s ease infinite;
            transition: var(--transition-elastic);
            white-space: nowrap;
            overflow: hidden;
            position: relative;
        }

        /* Quand la sidebar est réduite, le brand devient "follup" en minuscule */
        .sidebar.collapsed .brand {
            font-size: 1.2rem;
            text-align: center;
            padding: 0;
            animation: none;
            background: linear-gradient(135deg, var(--primary-400), var(--secondary-400));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-transform: lowercase;
        }

        .brand::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--primary-400), var(--secondary-400), var(--accent-400), transparent);
            transform: scaleX(0);
            transition: transform 0.5s ease;
            transform-origin: left;
        }

        .sidebar:not(.collapsed) .brand:hover::after {
            transform: scaleX(1);
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .brand span {
            display: block;
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-top: var(--spacing-3);
            font-weight: 400;
            -webkit-text-fill-color: var(--text-secondary);
            border-top: 1px solid var(--sidebar-border);
            padding-top: var(--spacing-4);
            transition: all 0.4s ease;
            opacity: 1;
            transform: translateY(0);
        }

        .sidebar.collapsed .brand span {
            opacity: 0;
            transform: translateY(-20px);
            margin: 0;
            padding: 0;
            height: 0;
            border: none;
        }

        /*---------------------------------------
          MENU STYLES - DYNAMIC
        ---------------------------------------*/
        .menu, .submenu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu > li {
            margin-bottom: var(--spacing-2);
            transform: translateZ(0);
        }

        /* Menu Links */
        .menu a {
            display: flex;
            align-items: center;
            gap: var(--spacing-3);
            padding: var(--spacing-3) var(--spacing-4);
            border-radius: var(--radius-xl);
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: var(--transition-elastic);
            position: relative;
            overflow: hidden;
            cursor: pointer;
            border: 1px solid transparent;
            white-space: nowrap;
        }

        .sidebar.collapsed .menu a {
            padding: var(--spacing-3) 0;
            justify-content: center;
            gap: 0;
        }

        .menu a i {
            width: 24px;
            font-size: 1.2rem;
            color: var(--text-muted);
            transition: var(--transition-bounce);
            text-align: center;
            filter: drop-shadow(0 2px 2px rgba(0, 0, 0, 0.1));
        }

        .sidebar.collapsed .menu a span {
            display: none;
        }

        /* Hover Effects */
        .menu a::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, 
                rgba(255, 255, 255, 0.05) 0%,
                rgba(255, 255, 255, 0.1) 50%,
                transparent 100%
            );
            transform: translateX(-100%);
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .menu a:hover::before {
            transform: translateX(0);
        }

        .menu a:hover {
            background: rgba(255, 255, 255, 0.03);
            border-color: rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
            transform: translateX(8px) scale(1.02);
            box-shadow: var(--shadow-glow);
        }

        .sidebar.collapsed .menu a:hover {
            transform: scale(1.15) translateX(0);
        }

        .menu a:hover i {
            color: var(--primary-400);
            transform: scale(1.2) rotate(5deg);
            filter: drop-shadow(0 0 8px var(--primary-400));
        }

        .sidebar.collapsed .menu a:hover i {
            transform: scale(1.3) rotate(10deg);
        }

        /* Active State */
        .menu a.active {
            background: var(--sidebar-active);
            color: white;
            box-shadow: 0 8px 20px -4px var(--sidebar-active-glow);
            animation: activeGlow 2s infinite;
        }

        @keyframes activeGlow {
            0%, 100% {
                box-shadow: 0 8px 20px -4px var(--sidebar-active-glow);
            }
            50% {
                box-shadow: 0 8px 30px 0px var(--sidebar-active-glow);
            }
        }

        .menu a.active i {
            color: white;
            transform: scale(1.15);
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
        }

        .menu a.active::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 60%;
            background: linear-gradient(180deg, var(--accent-400) 0%, var(--secondary-400) 100%);
            border-radius: var(--radius-full) 0 0 var(--radius-full);
            box-shadow: 0 0 15px var(--accent-400);
            animation: pulseHeight 1.5s ease infinite;
        }

        .sidebar.collapsed .menu a.active::after {
            display: none;
        }

        @keyframes pulseHeight {
            0%, 100% {
                height: 60%;
            }
            50% {
                height: 80%;
            }
        }

        /* Menu Item with Submenu Indicator */
        .menu > li > a:not(:only-child)::after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            margin-left: auto;
            font-size: 1rem;
            color: var(--text-muted);
            transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .sidebar.collapsed .menu > li > a:not(:only-child)::after {
            display: none;
        }

        .menu > li > a.open:not(:only-child)::after {
            transform: rotate(-180deg) translateY(2px);
            color: var(--primary-400);
        }

        /*---------------------------------------
          SUBMENU STYLES - FLOATING FOR COLLAPSED
        ---------------------------------------*/
        .submenu {
            margin-left: var(--spacing-8);
            margin-top: var(--spacing-1);
            margin-bottom: var(--spacing-1);
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: max-height 0.5s cubic-bezier(0.4, 0, 0.2, 1),
                        opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: top;
        }

        .sidebar.collapsed .submenu {
            position: fixed;
            left: var(--sidebar-width-collapsed);
            background: linear-gradient(135deg, var(--sidebar-bg-light), var(--sidebar-bg));
            border-radius: 0 var(--radius-2xl) var(--radius-2xl) 0;
            padding: var(--spacing-3);
            min-width: 220px;
            margin-left: 0;
            box-shadow: 10px 10px 30px rgba(0, 0, 0, 0.3);
            border: 1px solid var(--sidebar-border);
            border-left: 3px solid var(--accent-500);
            z-index: 1000;
            backdrop-filter: blur(10px);
            animation: slideInRight 0.3s ease;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .submenu.open {
            max-height: 800px;
            opacity: 1;
        }

        .sidebar.collapsed .submenu.open {
            max-height: none;
        }

        .submenu a {
            padding: var(--spacing-2) var(--spacing-4);
            color: var(--text-muted);
            font-size: 0.875rem;
            border-left: 2px solid transparent;
            transition: var(--transition-smooth);
            gap: var(--spacing-3);
            border-radius: var(--radius-lg);
            margin-bottom: var(--spacing-1);
            display: flex;
            align-items: center;
        }

        .sidebar.collapsed .submenu a {
            padding: var(--spacing-2) var(--spacing-4);
            white-space: nowrap;
        }

        .submenu a i {
            font-size: 0.95rem;
            width: 20px;
            color: var(--text-muted);
            transition: var(--transition-bounce);
        }

        .submenu a:hover {
            color: var(--text-primary);
            background: rgba(255, 255, 255, 0.02);
            border-left-color: var(--accent-500);
            transform: translateX(8px);
            box-shadow: var(--shadow-sm);
        }

        .sidebar.collapsed .submenu a:hover {
            transform: translateX(12px);
        }

        .submenu a:hover i {
            color: var(--accent-400);
            transform: scale(1.15) translateX(2px);
        }

        .submenu a.active {
            background: linear-gradient(90deg, rgba(136, 77, 255, 0.1) 0%, transparent 100%);
            color: var(--primary-300);
            border-left-color: var(--accent-500);
            position: relative;
        }

        /*---------------------------------------
          LOGOUT BUTTON - DYNAMIC
        ---------------------------------------*/
        .logout {
            margin-top: auto;
            padding-top: var(--spacing-8);
            border-top: 1px solid var(--sidebar-border);
            position: relative;
        }

        .logout::before {
            content: '';
            position: absolute;
            top: -1px;
            left: 10%;
            right: 10%;
            height: 1px;
            background: linear-gradient(90deg, 
                transparent, 
                var(--accent-500), 
                var(--primary-500), 
                var(--secondary-500), 
                transparent
            );
            animation: borderFlow 3s linear infinite;
            background-size: 200% 100%;
        }

        @keyframes borderFlow {
            0% { background-position: 0% 0%; }
            100% { background-position: 200% 0%; }
        }

        .logout button {
            width: 100%;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--sidebar-border);
            color: var(--text-secondary);
            padding: var(--spacing-4) var(--spacing-4);
            border-radius: var(--radius-xl);
            cursor: pointer;
            font-weight: 600;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-3);
            transition: var(--transition-bounce);
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }

        .sidebar.collapsed .logout button span {
            display: none;
        }

        .sidebar.collapsed .logout button {
            padding: var(--spacing-4) 0;
        }

        .logout button i {
            font-size: 1.2rem;
            transition: var(--transition-bounce);
            color: var(--accent-500);
            filter: drop-shadow(0 2px 5px rgba(244, 63, 94, 0.3));
        }

        .logout button:hover {
            background: rgba(244, 63, 94, 0.1);
            color: var(--text-primary);
            border-color: var(--accent-500);
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 10px 25px -5px rgba(244, 63, 94, 0.4);
        }

        .logout button:hover i {
            transform: translateX(8px) scale(1.2);
            color: var(--accent-400);
        }

        .sidebar.collapsed .logout button:hover i {
            transform: scale(1.3);
        }

        /*---------------------------------------
          MAIN CONTENT - DYNAMIC
        ---------------------------------------*/
        .content {
            flex: 1;
            padding: var(--spacing-10);
            margin-left: var(--sidebar-width-expanded);
            min-height: 100vh;
            overflow-y: auto;
            background: var(--bg-main);
            transition: margin-left 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
        }

        .content.expanded {
            margin-left: var(--sidebar-width-collapsed);
        }

        .content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 30%, rgba(136, 77, 255, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(59, 130, 246, 0.03) 0%, transparent 50%);
            pointer-events: none;
        }

        /* Content Scrollbar */
        .content::-webkit-scrollbar {
            width: 10px;
        }

        .content::-webkit-scrollbar-track {
            background: var(--bg-main);
        }

        .content::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--primary-400) 0%, var(--secondary-500) 100%);
            border-radius: var(--radius-full);
            border: 2px solid var(--bg-main);
        }

        /*---------------------------------------
          CARD STYLES
        ---------------------------------------*/
        .card {
            background: var(--bg-card);
            border-radius: var(--radius-2xl);
            padding: var(--spacing-8);
            box-shadow: var(--shadow-xl);
            border: 1px solid rgba(0, 0, 0, 0.02);
            transition: var(--transition-bounce);
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-500) 0%, var(--secondary-500) 50%, var(--accent-500) 100%);
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }

        .card:hover {
            box-shadow: var(--shadow-2xl);
            transform: translateY(-6px);
        }

        .card:hover::before {
            transform: translateX(0);
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
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
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
          RESPONSIVE DESIGN
        ---------------------------------------*/
        @media (max-width: 1024px) {
            .sidebar {
                width: 280px;
            }
            
            .content {
                margin-left: 280px;
            }
        }

        @media (max-width: 768px) {
            .mobile-header {
                display: flex;
            }

            .layout {
                padding-top: 72px;
            }

            .sidebar {
                transform: translateX(-100%);
                width: 280px;
                top: 72px;
                height: calc(100vh - 72px);
                border-top: 1px solid var(--sidebar-border);
                transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .sidebar.collapsed {
                transform: translateX(-100%);
                width: 280px;
            }

            .sidebar-toggle {
                display: none;
            }

            .content {
                margin-left: 0;
                padding: var(--spacing-6);
            }

            .content.expanded {
                margin-left: 0;
            }

            .sidebar-overlay {
                display: block;
            }

            .sidebar.collapsed .submenu {
                left: 280px;
            }
        }

        @media (max-width: 480px) {
            .mobile-header {
                padding: var(--spacing-3) var(--spacing-4);
            }

            .mobile-brand {
                font-size: 1.25rem;
            }

            .sidebar {
                width: 260px;
            }

            .content {
                padding: var(--spacing-4);
            }

            .card {
                padding: var(--spacing-4);
                border-radius: var(--radius-xl);
            }
        }

        /*---------------------------------------
          ANIMATIONS
        ---------------------------------------*/
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .sidebar {
            animation: slideIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        /* Ripple Effect */
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            transform: scale(0);
            animation: ripple-animation 0.6s ease-out;
            pointer-events: none;
            z-index: 999;
        }

        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        .menu a, .logout button {
            position: relative;
            overflow: hidden;
        }
    </style>
</head>
<body>

<!-- Mobile Header -->
<div class="mobile-header">
    <div class="mobile-brand">
        FOLLUP.IO
        <small>{{ session('client.company') }}</small>
    </div>
    <button class="menu-toggle-btn" id="mobileMenuToggle" aria-label="Menu">
        <i class="fa-solid fa-bars-staggered"></i>
    </button>
</div>

<!-- Overlay for mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="layout">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-toggle" id="sidebarToggle">
            <i class="fa-solid fa-chevron-left"></i>
        </div>
        
        <div class="brand">
            FOLLUP
            <span>{{ session('client.company') }}</span>
        </div>

        <ul class="menu" id="mainMenu">
            <!-- CRM -->
            <li>
                <a href="#" class="menu-toggle" data-target="crm-submenu">
                    <i class="fa-solid fa-chart-pie"></i>
                    <span>CRM</span>
                </a>
                <ul class="submenu" id="crm-submenu">
                    @if(session('client.role') === 'superadmin')
                    <li><a href="{{ route('client.crm.dashboard') }}"><i class="fa-solid fa-gauge-high"></i> <span>Dashboard</span></a></li>
                    @endif
                    <li><a href="{{ route('client.crm.leads') }}"><i class="fa-solid fa-users"></i> <span>Mes Leads</span></a></li>
                </ul>
            </li>

            <!-- MAILS -->
            <li>
                <a href="#" class="menu-toggle" data-target="mails-submenu">
                    <i class="fa-regular fa-envelope"></i>
                    <span>Mails</span>
                </a>
                <ul class="submenu" id="mails-submenu">
                    <li><a href="{{ route('client.mails.programmes') }}"><i class="fa-regular fa-clock"></i> <span>Mes mails programmés</span></a></li>
                    <li>
                        <a href="{{ route('client.mails.plus') }}">
                            <i class="fa-solid fa-bullhorn"></i> <span>Envoi mail en masse</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="menu-toggle" data-target="inbox-submenu">
                            <i class="fa-regular fa-inbox"></i> <span>Boite de réception</span>
                        </a>
                        <ul class="submenu" id="inbox-submenu">
                            <li><a href="{{ route('client.mails.envoyes') }}"><i class="fa-regular fa-paper-plane"></i> <span>Mails envoyés</span></a></li>
                            <li><a href="{{ route('client.mails.recus') }}"><i class="fa-regular fa-circle-down"></i> <span>Mails reçus</span></a></li>
                        </ul>
                    </li>
                </ul>
            </li>

            <!-- SCRAPPING -->
            <li>
                <a href="#" class="menu-toggle" data-target="scraping-submenu">
                    <i class="fa-solid fa-robot"></i>
                    <span>SCRAPPING</span>
                </a>
                <ul class="submenu" id="scraping-submenu">
                    <li><a href="{{ route('client.google') }}"><i class="fa-solid fa-map-location-dot"></i> <span>Google Maps</span></a></li>
                    <li><a href="{{ route('client.web') }}"><i class="fa-solid fa-globe"></i> <span>Site Web</span></a></li>
                </ul>
            </li>

            <!-- PROMPT IA -->
            <li>
                <a href="{{ route('client.prompt-ia') }}">
                    <i class="fa-solid fa-brain"></i>
                    <span>Prompt IA</span>
                </a>
            </li>

            <!-- COMMUNICATION -->
            <li class="disabled-communication">
                <a href="#" class="menu-toggle" data-target="communication-submenu">
                    <i class="fa-solid fa-comments"></i>
                    <span>Communication</span>
                </a>
                <ul class="submenu" id="communication-submenu">
                    <li>
                        <a href="{{ route('client.communication.whatsapp') }}">
                            <i class="fa-brands fa-whatsapp"></i> <span>WhatsApp</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('client.communication.sms') }}">
                            <i class="fa-solid fa-message"></i> <span>SMS</span>
                        </a>
                    </li>
                </ul>
            </li>

            <style>
            .disabled-communication {
                position: relative;
                opacity: 0.6;
                cursor: not-allowed;
            }
            .disabled-communication a {
                pointer-events: none;
            }
            #communication-submenu {
                display: none !important;
            }
            .disabled-communication:hover::after {
                content: "En cours de développement";
                position: absolute;
                left: 60px;
                top: 0;
                background: #333;
                color: #fff;
                font-size: 12px;
                padding: 6px 10px;
                border-radius: 5px;
                white-space: nowrap;
                z-index: 1000;
                animation: tooltipFade 0.3s ease;
            }
            @keyframes tooltipFade {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            .sidebar.collapsed .disabled-communication:hover::after {
                left: var(--sidebar-width-collapsed);
            }
            </style>

            @if(session('client.role') === 'superadmin')
            <!-- INVOICE -->
            <li>
                <a href="#" class="menu-toggle" data-target="invoice-submenu">
                    <i class="fa-solid fa-file-invoice"></i>
                    <span>Invoice</span>
                </a>
                <ul class="submenu" id="invoice-submenu">
                    <li>
                        <a href="{{ route('clients.index') }}">
                            <i class="fa-solid fa-users"></i>
                            <span>Clients</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('client.invoice.devis') }}">
                            <i class="fa-solid fa-file-signature"></i>
                            <span>Devis</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('client.invoice.factures') }}">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                            <span>Factures</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('client.invoice.flux') }}">
                            <i class="fa-solid fa-gear"></i>
                            <span>Flux Auto</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- RESEAU IA -->
            <li>
                <a href="{{ route('client.reseau.ia') }}">
                    <i class="fa-solid fa-network-wired"></i>
                    <span>Réseau IA</span>
                </a>
            </li>
            @endif

            <!-- PARAMETRES -->
            <li>
                <a href="#" class="menu-toggle" data-target="params-submenu">
                    <i class="fa-solid fa-sliders"></i>
                    <span>Paramètres</span>
                </a>
                <ul class="submenu" id="params-submenu">
                    <li><a href="{{ route('client.profil') }}"><i class="fa-regular fa-user"></i> <span>Profil</span></a></li>
                    @if(session('client.role') === 'superadmin')
                        <li><a href="{{ route('client.users') }}"><i class="fa-solid fa-users-cog"></i> <span>Utilisateurs</span></a></li>
                    @endif
                </ul>
            </li>
        </ul>

        <div class="logout">
            <form method="POST" action="{{ route('client.logout') }}">
                @csrf
                <button type="submit">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>Déconnexion</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="content" id="mainContent">
        @yield('content')
    </main>
</div>

<script>
(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        initializeSidebarToggle();
        initializeMobileMenu();
        initializeMenuSystem();
        initializeActiveLinks();
        handleResize();
        addRippleEffect();
        loadSidebarState();
    });

    // Premium Sidebar Toggle with direction-changing arrow
    function initializeSidebarToggle() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');

        if (!sidebarToggle || !sidebar || !mainContent) return;

        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const isCollapsed = sidebar.classList.contains('collapsed');
            
            // Animate the toggle button
            this.style.transform = 'scale(1.3)';
            setTimeout(() => {
                this.style.transform = '';
            }, 200);

            if (!isCollapsed) {
                // Collapse sidebar
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
                
                // Close all open submenus
                document.querySelectorAll('.submenu.open').forEach(menu => {
                    menu.style.transition = 'opacity 0.2s ease';
                    menu.classList.remove('open');
                });
                
                document.querySelectorAll('.menu-toggle.open').forEach(toggle => {
                    toggle.classList.remove('open');
                });

            } else {
                // Expand sidebar
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('expanded');
            }

            // Save state
            localStorage.setItem('sidebarCollapsed', !isCollapsed);
        });

        // Hover effect
        sidebarToggle.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.2)';
        });

        sidebarToggle.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    }

    // Load sidebar state
    function loadSidebarState() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const savedState = localStorage.getItem('sidebarCollapsed');

        if (savedState === 'true' && sidebar && mainContent && window.innerWidth > 768) {
            sidebar.classList.add('collapsed');
            mainContent.classList.add('expanded');
        }
    }

    // Mobile Menu
    function initializeMobileMenu() {
        const mobileToggle = document.getElementById('mobileMenuToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        
        if (!mobileToggle || !sidebar || !overlay) return;

        mobileToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const isActive = sidebar.classList.contains('active');
            
            if (!isActive) {
                sidebar.classList.add('active');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
                const icon = this.querySelector('i');
                icon.classList.remove('fa-bars-staggered');
                icon.classList.add('fa-xmark');
            } else {
                closeMobileMenu();
            }
        });

        overlay.addEventListener('click', closeMobileMenu);

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar.classList.contains('active')) {
                closeMobileMenu();
            }
        });

        function closeMobileMenu() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
            const icon = mobileToggle.querySelector('i');
            icon.classList.remove('fa-xmark');
            icon.classList.add('fa-bars-staggered');
        }

        const menuLinks = sidebar.querySelectorAll('a:not(.menu-toggle)');
        menuLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    closeMobileMenu();
                }
            });
        });
    }

    // Menu System
    function initializeMenuSystem() {
        const menuToggles = document.querySelectorAll('.menu-toggle');
        
        menuToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const targetId = this.dataset.target;
                const targetMenu = document.getElementById(targetId);
                
                if (targetMenu) {
                    const isOpening = !targetMenu.classList.contains('open');
                    
                    // Animate the toggle icon
                    const icon = this.querySelector('i:first-child');
                    if (icon && isOpening) {
                        icon.style.transform = 'rotate(90deg) scale(1.2)';
                        setTimeout(() => {
                            icon.style.transform = '';
                        }, 400);
                    }
                    
                    // Toggle current menu
                    targetMenu.classList.toggle('open');
                    this.classList.toggle('open');
                    
                    // Close sibling menus
                    const parent = this.closest('li');
                    if (parent) {
                        const siblingMenus = parent.parentElement.querySelectorAll(':scope > li > .submenu');
                        siblingMenus.forEach(menu => {
                            if (menu.id !== targetId && menu.classList.contains('open')) {
                                menu.classList.remove('open');
                                const siblingToggle = menu.parentElement.querySelector('.menu-toggle');
                                if (siblingToggle) {
                                    siblingToggle.classList.remove('open');
                                }
                            }
                        });
                    }
                }
            });
        });
    }

    // Active Links
    function initializeActiveLinks() {
        const currentPath = window.location.pathname;
        const menuLinks = document.querySelectorAll('.menu a:not(.menu-toggle)');
        
        menuLinks.forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
                
                // Open parent menus
                let parent = link.closest('.submenu');
                while (parent) {
                    parent.classList.add('open');
                    
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

    // Handle resize
    function handleResize() {
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebarOverlay');
                const mobileToggle = document.getElementById('mobileMenuToggle');
                
                if (window.innerWidth > 768) {
                    if (sidebar) sidebar.classList.remove('active');
                    if (overlay) overlay.classList.remove('active');
                    if (mobileToggle) {
                        const icon = mobileToggle.querySelector('i');
                        icon.classList.remove('fa-xmark');
                        icon.classList.add('fa-bars-staggered');
                    }
                    document.body.style.overflow = '';
                }
            }, 250);
        });
    }

    // Ripple effect
    function addRippleEffect() {
        const buttons = document.querySelectorAll('.menu a, .logout button');
        
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple');
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });
    }

    // Save menu state
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

    loadMenuState();
    window.addEventListener('beforeunload', function() {
        saveMenuState();
    });

})();
</script>

<style>
/* Additional animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Premium hover effects */
.menu a {
    position: relative;
    z-index: 1;
}

.menu a::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, var(--primary-500), var(--secondary-500));
    transition: all 0.3s ease;
    transform: translateX(-50%);
}

.menu a:hover::after {
    width: 80%;
}

.sidebar.collapsed .menu a:hover::after {
    display: none;
}

/* Glowing effect for active items */
.menu a.active i {
    animation: glowPulse 2s infinite;
}

@keyframes glowPulse {
    0%, 100% {
        filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.5));
    }
    50% {
        filter: drop-shadow(0 0 15px rgba(255, 255, 255, 0.8));
    }
}

/* Smooth transitions */
* {
    transition: background-color 0.3s ease,
                border-color 0.3s ease,
                box-shadow 0.3s ease;
}
</style>

</body>
</html>