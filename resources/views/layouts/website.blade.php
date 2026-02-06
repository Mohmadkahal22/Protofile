<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="HexaTerminal - Professional Software Development Company specializing in innovative digital solutions">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#060918">

    {{-- Prevent flash: apply saved theme before any CSS paints --}}
    <script>!function(){var t=localStorage.getItem('ht-theme')||'dark';document.documentElement.setAttribute('data-theme',t);var m=document.querySelector('meta[name="theme-color"]');if(m)m.content=t==='light'?'#f8fafc':'#060918'}()</script>

    <title>@yield('title', 'HexaTerminal - Software Development Company')</title>

    {{-- ═══ ALL ASSETS LOCAL — ZERO CDN REQUESTS ═══ --}}
    {{-- CSS: Fonts + Font Awesome + AOS styles bundled by Vite --}}
    @vite(['resources/css/website.css'])

    {{-- JS: Loaded as regular scripts (not modules) so they're available to inline scripts --}}
    <script src="{{ asset('vendor/axios.min.js') }}"></script>
    <script src="{{ asset('vendor/aos.js') }}"></script>

    <style>
        /* ═══════════════════════════════════════════
           DESIGN SYSTEM — HexaTerminal Pro
           Critical CSS inlined for instant render
           ═══════════════════════════════════════════ */
        :root {
            --primary: #2B9BFF;
            --primary-dark: #1a7fe0;
            --primary-light: #5cb3ff;
            --primary-glow: rgba(43, 155, 255, 0.35);
            --secondary: #667eea;
            --accent: #a855f7;
            --accent-2: #ec4899;
            --green: #00d084;
            --green-glow: rgba(0, 208, 132, 0.25);
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
            --gold: #fbbf24;

            --dark-bg: #060918;
            --dark-bg-2: #0a0e23;
            --dark-bg-3: #0e1230;
            --card-bg: rgba(17, 22, 56, 0.65);
            --card-bg-solid: #111638;
            --card-bg-hover: #161d48;
            --card-border: rgba(43, 155, 255, 0.08);

            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --border-color: rgba(43, 155, 255, 0.1);
            --border-hover: rgba(43, 155, 255, 0.3);

            --glass: rgba(17, 22, 56, 0.6);
            --glass-border: rgba(255, 255, 255, 0.06);

            --radius-xs: 0.375rem;
            --radius-sm: 0.5rem;
            --radius-md: 0.875rem;
            --radius-lg: 1.25rem;
            --radius-xl: 1.75rem;
            --radius-2xl: 2.5rem;

            --shadow-sm: 0 2px 8px rgba(0,0,0,0.15);
            --shadow-md: 0 8px 30px rgba(0,0,0,0.2);
            --shadow-lg: 0 20px 60px rgba(0,0,0,0.3);
            --shadow-glow: 0 0 40px var(--primary-glow);
            --shadow-card: 0 4px 20px rgba(0,0,0,0.15), 0 0 0 1px rgba(43,155,255,0.05);

            --transition: all 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            --transition-fast: all 0.2s ease;
            --transition-slow: all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        /* ═══════════════════════════════════════════
           LIGHT THEME
           ═══════════════════════════════════════════ */
        [data-theme="light"] {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #60a5fa;
            --primary-glow: rgba(37, 99, 235, 0.12);
            --secondary: #6366f1;
            --accent: #8b5cf6;
            --accent-2: #ec4899;
            --green: #059669;
            --green-glow: rgba(5, 150, 105, 0.1);
            --success: #16a34a;
            --warning: #d97706;
            --danger: #dc2626;
            --gold: #d97706;

            --dark-bg: #f8fafc;
            --dark-bg-2: #ffffff;
            --dark-bg-3: #f1f5f9;
            --card-bg: rgba(255, 255, 255, 0.92);
            --card-bg-solid: #ffffff;
            --card-bg-hover: #f8fafc;
            --card-border: rgba(0, 0, 0, 0.07);

            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --border-color: rgba(0, 0, 0, 0.1);
            --border-hover: rgba(37, 99, 235, 0.3);

            --glass: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(0, 0, 0, 0.06);

            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 16px rgba(0,0,0,0.06);
            --shadow-lg: 0 10px 40px rgba(0,0,0,0.08);
            --shadow-glow: 0 0 30px rgba(37,99,235,0.06);
            --shadow-card: 0 1px 8px rgba(0,0,0,0.06), 0 0 0 1px rgba(0,0,0,0.04);
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        html { scroll-behavior: smooth; -webkit-text-size-adjust: 100%; }

        body {
            font-family: 'Inter', 'Cairo', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            line-height: 1.7;
            color: var(--text-primary);
            background: var(--dark-bg);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ─── RESPONSIVE IMAGES ─── */
        img { max-width: 100%; height: auto; }
        video, iframe { max-width: 100%; }

        /* ─── SELECTION ─── */
        ::selection { background: rgba(43, 155, 255, 0.3); color: #fff; }

        /* ─── SCROLLBAR ─── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--dark-bg); }
        ::-webkit-scrollbar-thumb { background: linear-gradient(180deg, var(--primary), var(--accent)); border-radius: 3px; }

        /* ═══════════════════════════════════════════
           HEADER
           ═══════════════════════════════════════════ */
        .site-header {
            background: rgba(6, 9, 24, 0.88);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-bottom: 1px solid rgba(255,255,255,0.04);
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            padding: 0.85rem 0;
            transition: padding 0.3s ease, background 0.3s ease, box-shadow 0.3s ease;
            will-change: padding, background-color, box-shadow;
        }
        .site-header.scrolled {
            padding: 0.5rem 0;
            background: rgba(6, 9, 24, 0.96);
            box-shadow: 0 4px 30px rgba(0,0,0,0.5), 0 0 0 1px rgba(43,155,255,0.06);
        }
        .header-inner {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
        }
        .header-right {
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        /* Logo */
        .logo-container {
            display: flex; align-items: center; gap: 0.75rem;
            text-decoration: none; transition: var(--transition-fast);
        }
        .logo-container:hover { transform: scale(1.02); }
        .logo-hex { width: 48px; height: 48px; position: relative; }
        .logo-text { display: flex; flex-direction: column; line-height: 1.1; }
        .logo-text-part1 { font-size: 1.2rem; font-weight: 800; color: var(--text-primary); letter-spacing: -0.02em; }
        .logo-text-part2 {
            font-size: 1.2rem; font-weight: 800; letter-spacing: -0.02em;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }

        /* Navigation */
        .site-nav { margin-left: auto; }
        .nav-menu { display: flex; gap: 0.15rem; list-style: none; align-items: center; }
        .nav-link {
            color: var(--text-secondary); text-decoration: none; font-weight: 500; font-size: 0.88rem;
            transition: var(--transition-fast); padding: 0.55rem 1rem; border-radius: var(--radius-sm);
            position: relative; letter-spacing: 0.01em;
        }
        .nav-link::after {
            content: ''; position: absolute; bottom: 2px; left: 50%; transform: translateX(-50%);
            width: 0; height: 2px; background: var(--primary); border-radius: 1px;
            transition: width 0.3s ease;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--text-primary); background: rgba(43, 155, 255, 0.06);
        }
        .nav-link:hover::after, .nav-link.active::after { width: 60%; }

        /* Hamburger */
        .hamburger {
            display: none; background: none; border: 1px solid var(--border-color);
            cursor: pointer; padding: 0.6rem; flex-direction: column; gap: 5px;
            border-radius: var(--radius-sm); transition: var(--transition-fast);
        }
        .hamburger:hover { border-color: var(--primary); background: rgba(43,155,255,0.06); }
        .hamburger span {
            display: block; width: 22px; height: 2px; background: var(--text-primary);
            transition: var(--transition); border-radius: 2px;
        }
        .hamburger.active { border-color: var(--primary); }
        .hamburger.active span:nth-child(1) { transform: rotate(45deg) translate(5px, 5px); }
        .hamburger.active span:nth-child(2) { opacity: 0; }
        .hamburger.active span:nth-child(3) { transform: rotate(-45deg) translate(5px, -5px); }

        /* ═══ MAIN ═══ */
        main { padding-top: 76px; min-height: calc(100vh - 200px); }

        /* ═══ SECTIONS ═══ */
        .section { padding: 7rem 0; position: relative; }
        .section-header { text-align: center; margin-bottom: 4.5rem; }
        .section-badge {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.45rem 1.25rem;
            background: linear-gradient(135deg, rgba(43,155,255,0.08), rgba(168,85,247,0.06));
            border: 1px solid rgba(43,155,255,0.15);
            border-radius: 999px;
            color: var(--primary); font-size: 0.78rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: 2px; margin-bottom: 1.25rem;
        }
        .section-badge i { font-size: 0.7rem; }
        .section-title {
            font-size: clamp(2.2rem, 4vw, 3.2rem); font-weight: 800; margin-bottom: 1rem;
            color: var(--text-primary); line-height: 1.2; letter-spacing: -0.03em;
        }
        .section-subtitle {
            font-size: 1.05rem; color: var(--text-secondary); max-width: 580px;
            margin: 0 auto; line-height: 1.75;
        }
        .container { max-width: 1400px; margin: 0 auto; padding: 0 2rem; }

        /* ═══ CARDS ═══ */
        .card {
            background: var(--card-bg); backdrop-filter: blur(12px);
            border: 1px solid var(--card-border); border-radius: var(--radius-lg);
            transition: var(--transition); overflow: hidden; position: relative;
        }
        .card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(43,155,255,0.15), transparent);
            opacity: 0; transition: opacity 0.4s;
        }
        .card:hover {
            transform: translateY(-6px);
            border-color: rgba(43,155,255,0.18);
            box-shadow: 0 20px 50px rgba(0,0,0,0.25), 0 0 30px rgba(43,155,255,0.06);
        }
        .card:hover::before { opacity: 1; }

        /* ═══ GRID ═══ */
        .grid-2 { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; }
        .grid-3 { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem; }
        .grid-4 { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; }

        /* ═══ BUTTONS ═══ */
        .btn {
            display: inline-flex; align-items: center; gap: 0.6rem;
            padding: 0.85rem 2rem; border-radius: var(--radius-md); font-weight: 600;
            font-size: 0.92rem; text-decoration: none; border: none; cursor: pointer;
            transition: var(--transition); position: relative; overflow: hidden;
            letter-spacing: 0.01em;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            box-shadow: 0 4px 20px rgba(43,155,255,0.25), inset 0 1px 0 rgba(255,255,255,0.1);
        }
        .btn-primary::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            opacity: 0; transition: opacity 0.4s;
        }
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(43,155,255,0.35), inset 0 1px 0 rgba(255,255,255,0.15);
        }
        .btn-primary:hover::before { opacity: 1; }
        .btn-primary span, .btn-primary svg { position: relative; z-index: 1; }

        .btn-outline {
            background: transparent; color: var(--primary);
            border: 1.5px solid rgba(43,155,255,0.25);
        }
        .btn-outline:hover {
            background: rgba(43,155,255,0.08);
            border-color: var(--primary);
            transform: translateY(-2px);
        }

        .btn-green {
            background: linear-gradient(135deg, var(--green), #00b874);
            color: #000; font-weight: 700;
            box-shadow: 0 4px 20px var(--green-glow);
        }
        .btn-green:hover { transform: translateY(-3px); box-shadow: 0 8px 30px var(--green-glow); }

        /* ═══ SKELETON ═══ */
        .skeleton {
            background: linear-gradient(90deg, rgba(17,22,56,0.8) 25%, rgba(43,155,255,0.04) 50%, rgba(17,22,56,0.8) 75%);
            background-size: 200% 100%;
            animation: shimmer 1.8s ease-in-out infinite;
            border-radius: var(--radius-sm);
        }
        @keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
        .skeleton-card { height: 380px; border-radius: var(--radius-lg); border: 1px solid var(--card-border); }
        .skeleton-text { height: 1rem; margin-bottom: 0.75rem; }
        .skeleton-text.short { width: 60%; }

        /* ═══ TOAST ═══ */
        .toast-container {
            position: fixed; top: 90px; right: 2rem; z-index: 9999;
            display: flex; flex-direction: column; gap: 0.75rem;
        }
        .toast {
            padding: 1rem 1.5rem; border-radius: var(--radius-md);
            background: var(--card-bg-solid); border: 1px solid var(--border-color);
            backdrop-filter: blur(20px);
            box-shadow: var(--shadow-lg); color: var(--text-primary);
            display: flex; align-items: center; gap: 0.75rem; min-width: 320px;
            animation: slideIn 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94); font-size: 0.9rem;
        }
        .toast.success { border-left: 4px solid var(--green); }
        .toast.error { border-left: 4px solid var(--danger); }
        .toast.info { border-left: 4px solid var(--primary); }
        @keyframes slideIn { from { transform: translateX(120%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes slideOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(120%); opacity: 0; } }

        /* ═══ FORM ═══ */
        .form-group { margin-bottom: 1.5rem; }
        .form-label { display: block; margin-bottom: 0.5rem; color: var(--text-primary); font-weight: 600; font-size: 0.88rem; }
        .form-input {
            width: 100%; padding: 0.9rem 1.15rem; background: rgba(6,9,24,0.8);
            border: 1px solid rgba(43,155,255,0.1); border-radius: var(--radius-md);
            color: var(--text-primary); font-size: 0.92rem; font-family: inherit;
            transition: var(--transition); outline: none;
        }
        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(43,155,255,0.1), 0 0 20px rgba(43,155,255,0.05);
        }
        .form-input::placeholder { color: var(--text-muted); }

        /* ═══ FOOTER ═══ */
        .site-footer {
            background: var(--dark-bg-2); border-top: 1px solid rgba(255,255,255,0.04);
            position: relative; overflow: hidden;
        }
        .site-footer::before {
            content: ''; position: absolute; top: 0; left: 50%; transform: translateX(-50%);
            width: 600px; height: 1px;
            background: linear-gradient(90deg, transparent, var(--primary), transparent);
        }
        .footer-main { padding: 5rem 2rem 3rem; }
        .footer-grid {
            display: grid; grid-template-columns: 2fr 1fr 1fr 1.2fr;
            gap: 3rem; max-width: 1400px; margin: 0 auto;
        }
        .footer-title {
            font-size: 1rem; font-weight: 700; margin-bottom: 1.5rem;
            color: var(--text-primary); letter-spacing: 0.02em;
            position: relative; padding-bottom: 0.75rem;
        }
        .footer-title::after {
            content: ''; position: absolute; bottom: 0; left: 0;
            width: 30px; height: 2px; background: var(--primary); border-radius: 1px;
        }
        .footer-link {
            display: flex; align-items: center; gap: 0.5rem;
            color: var(--text-secondary); text-decoration: none;
            padding: 0.4rem 0; font-size: 0.88rem; transition: var(--transition);
        }
        .footer-link:hover { color: var(--primary); transform: translateX(6px); }
        .footer-link i { font-size: 0.65rem; transition: var(--transition); }
        .footer-social { display: flex; gap: 0.75rem; margin-top: 1.25rem; }
        .footer-social a {
            width: 42px; height: 42px; border-radius: var(--radius-sm);
            background: rgba(43,155,255,0.06); border: 1px solid rgba(43,155,255,0.1);
            display: flex; align-items: center; justify-content: center;
            color: var(--text-secondary); text-decoration: none;
            transition: var(--transition); font-size: 0.95rem;
        }
        .footer-social a:hover {
            background: var(--primary); color: #fff; border-color: var(--primary);
            transform: translateY(-3px); box-shadow: 0 8px 20px rgba(43,155,255,0.25);
        }
        .footer-newsletter-form { display: flex; gap: 0.5rem; margin-top: 1rem; }
        .footer-newsletter-form input {
            flex: 1; padding: 0.75rem 1rem; background: rgba(6,9,24,0.6);
            border: 1px solid rgba(43,155,255,0.1); border-radius: var(--radius-sm);
            color: var(--text-primary); font-size: 0.88rem; outline: none;
            transition: var(--transition);
        }
        .footer-newsletter-form input:focus { border-color: var(--primary); }
        .footer-newsletter-form button {
            padding: 0.75rem 1.25rem; background: var(--primary);
            border: none; border-radius: var(--radius-sm); color: #fff;
            cursor: pointer; transition: var(--transition); font-size: 0.88rem;
        }
        .footer-newsletter-form button:hover { background: var(--primary-dark); }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.04);
            padding: 1.5rem 2rem; max-width: 1400px; margin: 0 auto;
            display: flex; justify-content: space-between; align-items: center;
            color: var(--text-muted); font-size: 0.82rem;
        }

        /* ═══ UTILITIES ═══ */
        .text-gradient {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .text-gradient-green {
            background: linear-gradient(135deg, var(--green) 0%, #22c55e 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .glow-dot {
            position: absolute; border-radius: 50%;
            filter: blur(120px); pointer-events: none; opacity: 0.08;
        }
        .glow-line {
            position: absolute; pointer-events: none;
            background: linear-gradient(90deg, transparent, var(--primary), transparent);
            height: 1px; opacity: 0.15;
        }
        .divider {
            width: 100%; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(43,155,255,0.15), transparent);
            margin: 2rem 0;
        }

        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        @keyframes pulse-glow { 0%, 100% { opacity: 0.08; } 50% { opacity: 0.15; } }
        @keyframes rotate-slow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        @keyframes countUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* ═══ RESPONSIVE ═══ */
        @media (max-width: 1024px) {
            .footer-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 768px) {
            .hamburger { display: flex; }
            .site-nav {
                position: fixed; top: 76px; left: 0; right: 0;
                background: rgba(6,9,24,0.98); backdrop-filter: blur(20px);
                padding: 1rem 1.5rem 1.5rem;
                transform: translateY(-120%);
                transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
                border-bottom: 1px solid var(--border-color);
                box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            }
            .site-nav.open { transform: translateY(0); }
            .nav-menu { flex-direction: column; gap: 0.15rem; }
            .nav-link { width: 100%; padding: 0.85rem 1.15rem; border-radius: var(--radius-sm); }
            .nav-link::after { display: none; }
            .footer-grid { grid-template-columns: 1fr; gap: 2.5rem; }
            .footer-bottom { flex-direction: column; gap: 0.75rem; text-align: center; }
            .section { padding: 4.5rem 0; }
            .section-header { margin-bottom: 3rem; }
            .grid-3, .grid-4 { grid-template-columns: 1fr; }
            .container { padding: 0 1.25rem; }
        }
        @media (max-width: 480px) {
            .section { padding: 3.5rem 0; }
            .section-title { font-size: 1.75rem; }
        }

        /* ═══ PERFORMANCE: GPU hint for fixed header ═══ */
        .site-header { transform: translateZ(0); }

        .btn-nav-cta {
            background: linear-gradient(135deg, rgba(43,155,255,0.12), rgba(168,85,247,0.08)) !important;
            border: 1px solid rgba(43,155,255,0.2) !important;
            color: var(--primary) !important; font-weight: 600 !important;
        }
        .btn-nav-cta:hover {
            background: linear-gradient(135deg, rgba(43,155,255,0.2), rgba(168,85,247,0.12)) !important;
            border-color: var(--primary) !important;
        }

        /* ═══════════════════════════════════════════
           THEME TOGGLE BUTTON
           ═══════════════════════════════════════════ */
        .theme-toggle {
            width: 42px; height: 42px; border-radius: 50%;
            border: 1px solid var(--border-color); background: var(--card-bg);
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            color: var(--text-secondary); font-size: 1.05rem;
            transition: all 0.35s cubic-bezier(0.25,0.46,0.45,0.94);
            position: relative; overflow: hidden; flex-shrink: 0;
        }
        .theme-toggle:hover {
            border-color: var(--primary); color: var(--primary);
            background: rgba(43,155,255,0.08); transform: rotate(20deg) scale(1.05);
            box-shadow: 0 0 20px var(--primary-glow);
        }
        .theme-icon-sun, .theme-icon-moon {
            position: absolute;
            transition: transform 0.5s cubic-bezier(0.34,1.56,0.64,1), opacity 0.3s ease;
        }
        [data-theme="dark"] .theme-icon-sun  { opacity: 1; transform: rotate(0) scale(1); }
        [data-theme="dark"] .theme-icon-moon { opacity: 0; transform: rotate(90deg) scale(0); }
        [data-theme="light"] .theme-icon-sun  { opacity: 0; transform: rotate(-90deg) scale(0); }
        [data-theme="light"] .theme-icon-moon { opacity: 1; transform: rotate(0) scale(1); }

        /* ═══════════════════════════════════════════
           LIGHT THEME — Element Overrides
           ═══════════════════════════════════════════ */
        [data-theme="light"] ::selection { background: rgba(37,99,235,0.2); color: #0f172a; }
        [data-theme="light"] ::-webkit-scrollbar-track { background: #f1f5f9; }
        [data-theme="light"] ::-webkit-scrollbar-thumb { background: linear-gradient(180deg, #2563eb, #8b5cf6); }

        /* Header */
        [data-theme="light"] .site-header { background: rgba(255,255,255,0.92); border-bottom-color: rgba(0,0,0,0.06); }
        [data-theme="light"] .site-header.scrolled { background: rgba(255,255,255,0.97); box-shadow: 0 1px 12px rgba(0,0,0,0.06); }
        [data-theme="light"] .site-nav { background: rgba(255,255,255,0.98) !important; border-bottom-color: rgba(0,0,0,0.06) !important; box-shadow: 0 10px 30px rgba(0,0,0,0.06) !important; }
        [data-theme="light"] .nav-link { color: var(--text-secondary); }
        [data-theme="light"] .nav-link:hover, [data-theme="light"] .nav-link.active { color: #0f172a; background: rgba(37,99,235,0.06); }
        [data-theme="light"] .hamburger { border-color: rgba(0,0,0,0.12); }
        [data-theme="light"] .hamburger:hover { border-color: #2563eb; background: rgba(37,99,235,0.04); }
        [data-theme="light"] .hamburger span { background: #0f172a; }

        /* Cards */
        [data-theme="light"] .card { backdrop-filter: none; box-shadow: var(--shadow-card); background: var(--card-bg); }
        [data-theme="light"] .card:hover { border-color: rgba(37,99,235,0.15); box-shadow: 0 12px 35px rgba(0,0,0,0.08), 0 0 0 1px rgba(37,99,235,0.08); }
        [data-theme="light"] .card::before { background: linear-gradient(90deg, transparent, rgba(37,99,235,0.12), transparent); }

        /* Skeleton */
        [data-theme="light"] .skeleton { background: linear-gradient(90deg, #e2e8f0 25%, #f8fafc 50%, #e2e8f0 75%); background-size: 200% 100%; }

        /* Glow effects — subtle in light mode */
        [data-theme="light"] .glow-dot { opacity: 0.03 !important; }
        [data-theme="light"] .glow-line { opacity: 0.08 !important; }

        /* Forms */
        [data-theme="light"] .form-input { background: #fff; border-color: rgba(0,0,0,0.12); }
        [data-theme="light"] .form-input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.08), 0 0 15px rgba(37,99,235,0.04); }
        [data-theme="light"] .form-input::placeholder { color: #94a3b8; }

        /* Footer */
        [data-theme="light"] .site-footer { background: #f1f5f9; border-top-color: rgba(0,0,0,0.06); }
        [data-theme="light"] .site-footer::before { background: linear-gradient(90deg, transparent, #2563eb, transparent); opacity: 0.3; }
        [data-theme="light"] .footer-social a { background: rgba(37,99,235,0.04); border-color: rgba(37,99,235,0.08); }
        [data-theme="light"] .footer-social a:hover { background: #2563eb; color: #fff; border-color: #2563eb; }
        [data-theme="light"] .footer-newsletter-form input { background: #fff; border-color: rgba(0,0,0,0.1); }
        [data-theme="light"] .footer-newsletter-form button { background: #2563eb; }
        [data-theme="light"] .footer-newsletter-form button:hover { background: #1d4ed8; }

        /* Toast */
        [data-theme="light"] .toast { background: #fff; border-color: rgba(0,0,0,0.08); box-shadow: 0 4px 20px rgba(0,0,0,0.1); }

        /* Buttons */
        [data-theme="light"] .btn-primary { box-shadow: 0 4px 15px rgba(37,99,235,0.2); }
        [data-theme="light"] .btn-primary:hover { box-shadow: 0 6px 25px rgba(37,99,235,0.25); }
        [data-theme="light"] .btn-outline { border-color: rgba(37,99,235,0.25); color: #2563eb; }
        [data-theme="light"] .btn-outline:hover { background: rgba(37,99,235,0.06); border-color: #2563eb; }
        [data-theme="light"] .btn-green { color: #fff; }
        [data-theme="light"] .btn-nav-cta { background: linear-gradient(135deg, rgba(37,99,235,0.06), rgba(139,92,246,0.04)) !important; border-color: rgba(37,99,235,0.15) !important; }

        /* Misc */
        [data-theme="light"] .section-badge { background: linear-gradient(135deg, rgba(37,99,235,0.06), rgba(139,92,246,0.04)); border-color: rgba(37,99,235,0.12); }
        [data-theme="light"] .divider { background: linear-gradient(90deg, transparent, rgba(37,99,235,0.1), transparent); }
        [data-theme="light"] .text-gradient { background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }

    @stack('styles')
    </style>
</head>
<body>
    <!-- ═══ HEADER ═══ -->
    <header class="site-header" id="site-header">
        <div class="header-inner">
            <a href="{{ url('/') }}" class="logo-container">
                <svg class="logo-hex" viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="logoGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#2B9BFF"/>
                            <stop offset="100%" style="stop-color:#a855f7"/>
                        </linearGradient>
                    </defs>
                    <polygon points="60,10 95,30 95,70 60,90 25,70 25,30" fill="none" stroke="url(#logoGrad)" stroke-width="2.5" stroke-linejoin="round"/>
                    <polygon points="60,22 83,35 83,65 60,78 37,65 37,35" fill="rgba(43,155,255,0.06)" stroke="url(#logoGrad)" stroke-width="1" stroke-linejoin="round" opacity="0.6"/>
                    <text x="60" y="53" font-family="'Courier New', monospace" font-weight="700" font-size="24" fill="url(#logoGrad)" text-anchor="middle" dominant-baseline="middle">&gt;_</text>
                </svg>
                <div class="logo-text">
                    <span class="logo-text-part1">Hexa</span>
                    <span class="logo-text-part2">Terminal</span>
                </div>
            </a>

            <nav class="site-nav" id="main-nav">
                <ul class="nav-menu">
                    <li><a href="{{ url('/') }}#home" class="nav-link"><i class="fas fa-home" style="font-size:0.7rem;margin-right:0.3rem;opacity:0.6;"></i> Home</a></li>
                    <li><a href="{{ url('/') }}#team" class="nav-link"><i class="fas fa-users" style="font-size:0.7rem;margin-right:0.3rem;opacity:0.6;"></i> Team</a></li>
                    <li><a href="{{ url('/') }}#services" class="nav-link"><i class="fas fa-cogs" style="font-size:0.7rem;margin-right:0.3rem;opacity:0.6;"></i> Services</a></li>
                    <li><a href="{{ url('/') }}#projects" class="nav-link"><i class="fas fa-briefcase" style="font-size:0.7rem;margin-right:0.3rem;opacity:0.6;"></i> Projects</a></li>
                    <li><a href="{{ url('/') }}#about" class="nav-link"><i class="fas fa-info-circle" style="font-size:0.7rem;margin-right:0.3rem;opacity:0.6;"></i> About</a></li>
                    <li><a href="{{ url('/') }}#reviews" class="nav-link"><i class="fas fa-star" style="font-size:0.7rem;margin-right:0.3rem;opacity:0.6;"></i> Reviews</a></li>
                    <li><a href="{{ url('/') }}#faq" class="nav-link"><i class="fas fa-question-circle" style="font-size:0.7rem;margin-right:0.3rem;opacity:0.6;"></i> FAQ</a></li>
                    <li><a href="{{ url('/') }}#contact" class="nav-link btn-nav-cta"><i class="fas fa-envelope" style="font-size:0.7rem;margin-right:0.3rem;"></i> Contact</a></li>
                </ul>
            </nav>

            <div class="header-right">
                <button class="theme-toggle" id="theme-toggle" onclick="toggleTheme()" aria-label="Toggle light/dark mode" title="Toggle theme">
                    <i class="fas fa-sun theme-icon-sun"></i>
                    <i class="fas fa-moon theme-icon-moon"></i>
                </button>
                <button class="hamburger" id="hamburger" onclick="toggleMobileMenu()" aria-label="Toggle menu">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </header>

    <!-- Toast container -->
    <div class="toast-container" id="toast-container"></div>

    <main>
        @yield('content')
    </main>

    <!-- ═══ FOOTER ═══ -->
    <footer class="site-footer">
        <div class="footer-main">
            <div class="footer-grid">
                <!-- Brand -->
                <div>
                    <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1.25rem;">
                        <svg width="40" height="40" viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg">
                            <polygon points="60,10 95,30 95,70 60,90 25,70 25,30" fill="none" stroke="#2B9BFF" stroke-width="2.5" stroke-linejoin="round"/>
                            <text x="60" y="53" font-family="'Courier New', monospace" font-weight="700" font-size="24" fill="#2B9BFF" text-anchor="middle" dominant-baseline="middle">&gt;_</text>
                        </svg>
                        <div>
                            <span style="font-size:1.1rem;font-weight:800;color:var(--text-primary);">Hexa</span><span style="font-size:1.1rem;font-weight:800;color:var(--primary);">Terminal</span>
                        </div>
                    </div>
                    <p style="color:var(--text-secondary);font-size:0.88rem;line-height:1.9;max-width:350px;margin-bottom:1.5rem;">
                        Professional software development company delivering innovative digital solutions with cutting-edge technologies and creative design.
                    </p>
                    <div class="footer-social">
                        <a href="#" aria-label="GitHub"><i class="fab fa-github"></i></a>
                        <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="footer-title">Quick Links</h4>
                    <a href="{{ url('/') }}#team" class="footer-link"><i class="fas fa-chevron-right"></i> Our Team</a>
                    <a href="{{ url('/') }}#services" class="footer-link"><i class="fas fa-chevron-right"></i> Services</a>
                    <a href="{{ url('/') }}#projects" class="footer-link"><i class="fas fa-chevron-right"></i> Projects</a>
                    <a href="{{ url('/') }}#reviews" class="footer-link"><i class="fas fa-chevron-right"></i> Reviews</a>
                    <a href="{{ url('/') }}#faq" class="footer-link"><i class="fas fa-chevron-right"></i> FAQ</a>
                </div>

                <!-- Company -->
                <div>
                    <h4 class="footer-title">Company</h4>
                    <a href="{{ url('/') }}#about" class="footer-link"><i class="fas fa-chevron-right"></i> About Us</a>
                    <a href="{{ url('/') }}#contact" class="footer-link"><i class="fas fa-chevron-right"></i> Contact</a>
                    <a href="#" class="footer-link"><i class="fas fa-chevron-right"></i> Privacy Policy</a>
                    <a href="#" class="footer-link"><i class="fas fa-chevron-right"></i> Terms of Service</a>
                </div>

                <!-- Newsletter -->
                <div>
                    <h4 class="footer-title">Newsletter</h4>
                    <p style="color:var(--text-secondary);font-size:0.85rem;line-height:1.7;margin-bottom:0.5rem;">
                        Stay updated with our latest news and projects.
                    </p>
                    <div class="footer-newsletter-form">
                        <input type="email" placeholder="Your email..." />
                        <button type="button"><i class="fas fa-paper-plane"></i></button>
                    </div>
                    <div style="margin-top:1.5rem;">
                        <div style="display:flex;align-items:center;gap:0.6rem;margin-bottom:0.6rem;">
                            <i class="fas fa-envelope" style="color:var(--primary);font-size:0.8rem;width:16px;"></i>
                            <span style="color:var(--text-secondary);font-size:0.85rem;">contact@hexaterminal.com</span>
                        </div>
                        <div style="display:flex;align-items:center;gap:0.6rem;">
                            <i class="fas fa-map-marker-alt" style="color:var(--primary);font-size:0.8rem;width:16px;"></i>
                            <span style="color:var(--text-secondary);font-size:0.85rem;">Damascus, Syria</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} HexaTerminal. All rights reserved.</p>
            <p style="display:flex;align-items:center;gap:0.5rem;">Crafted with <span style="color:var(--danger);">&#10084;</span> by <span style="color:var(--primary);font-weight:600;">HexaTerminal</span></p>
        </div>
    </footer>

    {{-- ═══ INLINE JS — Globals needed by component scripts ═══ --}}
    <script>
        // API base URL
        const API_BASE = '{{ str_replace("localhost", "127.0.0.1", config("app.url")) }}/api';
        const STORAGE_URL = '{{ str_replace("localhost", "127.0.0.1", config("app.url")) }}/storage/';

        // Initialize AOS
        AOS.init({ duration: 700, once: true, offset: 60, easing: 'ease-out-cubic' });

        // ── Single unified scroll handler (rAF-throttled, passive) ──
        var _scrollTicking = false;
        var _header = document.getElementById('site-header');
        var _navLinks = document.querySelectorAll('.nav-link');
        var _sections = null; // lazy-cached

        function _onScroll() {
            var sy = window.scrollY;

            // Header shrink
            if (sy > 50) { _header.classList.add('scrolled'); } else { _header.classList.remove('scrolled'); }

            // Active nav highlight
            if (!_sections) _sections = document.querySelectorAll('section[id]');
            var scrollPos = sy + 140;
            _sections.forEach(function(sec) {
                var top = sec.offsetTop;
                var id = sec.getAttribute('id');
                var link = document.querySelector('.nav-link[href*="#' + id + '"]');
                if (link) {
                    if (scrollPos >= top && scrollPos < top + sec.offsetHeight) {
                        _navLinks.forEach(function(l) { l.classList.remove('active'); });
                        link.classList.add('active');
                    }
                }
            });
            _scrollTicking = false;
        }

        window.addEventListener('scroll', function() {
            if (!_scrollTicking) { requestAnimationFrame(_onScroll); _scrollTicking = true; }
        }, { passive: true });

        // Mobile menu
        function toggleMobileMenu() {
            document.getElementById('hamburger').classList.toggle('active');
            document.getElementById('main-nav').classList.toggle('open');
        }

        // Close mobile menu on link click
        _navLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                document.getElementById('hamburger').classList.remove('active');
                document.getElementById('main-nav').classList.remove('open');
            });
        });

        // Toast
        function showToast(message, type) {
            type = type || 'info';
            var container = document.getElementById('toast-container');
            var icons = { success: '<i class="fas fa-check-circle"></i>', error: '<i class="fas fa-times-circle"></i>', info: '<i class="fas fa-info-circle"></i>' };
            var colorMap = { success: 'var(--green)', error: 'var(--danger)', info: 'var(--primary)' };
            var toast = document.createElement('div');
            toast.className = 'toast ' + type;
            toast.innerHTML = '<span style="font-size:1.1rem;color:' + colorMap[type] + ';">' + (icons[type] || icons.info) + '</span><span>' + message + '</span>';
            container.appendChild(toast);
            setTimeout(function() {
                toast.style.animation = 'slideOut 0.4s ease forwards';
                setTimeout(function() { toast.remove(); }, 400);
            }, 4000);
        }

        // ── Theme Toggle ──
        function toggleTheme() {
            var html = document.documentElement;
            var current = html.getAttribute('data-theme') || 'dark';
            var next = current === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', next);
            localStorage.setItem('ht-theme', next);
            document.querySelector('meta[name="theme-color"]').content = next === 'dark' ? '#060918' : '#f8fafc';
        }

        // Image URL helper
        function getImageUrl(path) {
            if (!path) return '';
            if (path.startsWith('http')) return path;
            return STORAGE_URL + path;
        }

        // ── Smooth scroll — scrolls so the section title is visible below the header ──
        function scrollToSection(hash) {
            var target = document.getElementById(hash);
            if (!target) return false;
            var headerH = _header.offsetHeight + 20; // header + 20px breathing room
            var top = target.getBoundingClientRect().top + window.pageYOffset - headerH;
            window.scrollTo({ top: top, behavior: 'smooth' });
            history.replaceState(null, '', '#' + hash);
            return true;
        }

        document.addEventListener('click', function(e) {
            var link = e.target.closest('a[href*="#"]');
            if (!link) return;
            var href = link.getAttribute('href');
            var hash = href.split('#')[1];
            if (!hash) return;
            // Only intercept if target section exists on this page
            if (document.getElementById(hash)) {
                e.preventDefault();
                scrollToSection(hash);
            }
        });

        // Handle initial hash in URL (e.g. arriving from another page)
        if (window.location.hash) {
            var initHash = window.location.hash.substring(1);
            // Wait for DOM + Axios renders to settle, then scroll
            setTimeout(function() { scrollToSection(initHash); }, 350);
        }

        // Counter animation helper
        function animateCounter(el, target) {
            var current = 0;
            var increment = target / 40;
            var timer = setInterval(function() {
                current += increment;
                if (current >= target) { current = target; clearInterval(timer); }
                el.textContent = Math.floor(current) + '+';
            }, 30);
        }
    </script>

    @stack('scripts')
</body>
</html>
