<!DOCTYPE html>
<html lang="sw">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
  <meta name="theme-color" content="#0e8f80">
  <title>DalaliFasta - Nyumba, Mali, Viwanja</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
  <style>
    :root {
      --brand: #0e8f80;
      --brand-dark: #0a6b60;
      --brand-soft: #e3f4f1;
      --ink: #14201e;
      --muted: #6b7a77;
      --line: #e6ecea;
      --bg: #f4f7f6;
      --card: #ffffff;
      --gold: #ffb300;
      --radius: 24px;
      --container-max: 1200px;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; -webkit-tap-highlight-color: transparent; }
    html { scroll-behavior: smooth; }
    body {
      font-family: 'Poppins', 'Segoe UI', Tahoma, sans-serif;
      background: var(--bg);
      color: var(--ink);
      padding-bottom: calc(88px + env(safe-area-inset-bottom, 0px));
      -webkit-font-smoothing: antialiased;
      overflow-x: hidden;
    }

    a { color: inherit; text-decoration: none; }
    button { font-family: inherit; cursor: pointer; border: none; }
    :focus-visible { outline: 3px solid var(--gold); outline-offset: 2px; }

    /* Splash Screen */
    #welcome-splash {
      position: fixed; inset: 0; z-index: 9999;
      background: linear-gradient(160deg, var(--brand-dark), var(--brand));
      color: #fff;
      display: flex; flex-direction: column; align-items: center; justify-content: center;
      gap: 6px; text-align: center; padding: 24px;
      transition: opacity .6s ease;
    }
    .splash-logo-img {
      width: 104px; height: 104px; object-fit: contain;
      background: #fff; padding: 12px; border-radius: 32px;
      box-shadow: 0 18px 40px rgba(0,0,0,.25);
      margin-bottom: 18px;
      animation: pop 1.4s ease-in-out infinite alternate;
    }
    @keyframes pop { to { transform: scale(1.07); } }
    .splash-title { font-size: 26px; font-weight: 700; }
    .splash-sub { font-size: 14px; opacity: .85; }

    /* Header */
    header {
      position: sticky; top: 0; z-index: 100;
      background: rgba(244,247,246,.92);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border-bottom: 1px solid rgba(230,236,234,0.6);
    }
    .navbar {
      max-width: var(--container-max); margin: 0 auto;
      padding: 14px 18px;
      display: flex; align-items: center; justify-content: space-between; gap: 12px;
    }
    .brand { display: flex; align-items: center; gap: 12px; }
    .header-logo-img { width: 44px; height: 44px; object-fit: contain; border-radius: 14px; background: #fff; }
    .brand-hello { font-size: 12px; color: var(--muted); line-height: 1.2; }
    .logo-text { font-size: 18px; font-weight: 700; line-height: 1.2; }
    .logo-text span { color: var(--brand); }

    .desktop-links {
      display: none;
      align-items: center;
      gap: 24px;
      list-style: none;
    }
    .desktop-links a {
      font-size: 14px;
      font-weight: 600;
      color: var(--muted);
      display: flex;
      align-items: center;
      gap: 6px;
      transition: color 0.2s;
    }
    .desktop-links a:hover, .desktop-links a.active {
      color: var(--brand);
    }
    .desktop-links svg { width: 18px; height: 18px; }

    .header-actions { display: flex; align-items: center; gap: 10px; }
    .icon-btn {
      position: relative; width: 44px; height: 44px; border-radius: 50%;
      background: #fff; color: var(--ink);
      display: grid; place-items: center;
      box-shadow: 0 2px 10px rgba(20,32,30,.07);
    }
    .icon-btn svg { width: 22px; height: 22px; }
    .notif-badge {
      position: absolute; top: 6px; right: 7px;
      width: 11px; height: 11px; border-radius: 50%;
      background: #e53935; border: 2px solid #fff;
    }
    .auth-btns { display: none; gap: 8px; }
    .btn {
      padding: 10px 18px; border-radius: 999px;
      font-size: 13px; font-weight: 600;
    }
    .btn-outline { border: 2px solid var(--brand); color: var(--brand); background: transparent; }
    .btn-primary { background: var(--brand); color: #fff; }

    /* Search */
    .hero { max-width: var(--container-max); margin: 0 auto; padding: 14px 18px 0; }
    .hero-title { font-size: clamp(24px, 3vw, 34px); font-weight: 700; line-height: 1.3; max-width: 600px; }
    .hero-sub { font-size: 13.5px; color: var(--muted); margin-top: 6px; }
    .search-box {
      margin-top: 18px; max-width: 640px;
      background: #fff; border-radius: 999px;
      padding: 6px 6px 6px 20px;
      display: flex; align-items: center; gap: 10px;
      box-shadow: 0 6px 22px rgba(20,32,30,.08);
    }
    .search-input {
      flex: 1; min-width: 0; border: none; outline: none; background: transparent;
      font: inherit; font-size: 14px; color: var(--ink); padding: 10px 0;
    }
    .btn-search {
      width: 46px; height: 46px; border-radius: 50%;
      background: var(--brand); color: #fff; display: grid; place-items: center;
      flex-shrink: 0;
    }
    .btn-search svg { width: 20px; height: 20px; }

    /* Category pills */
    .pills {
      max-width: var(--container-max); margin: 18px auto 0; padding: 0 18px;
      display: flex; gap: 10px; overflow-x: auto; scrollbar-width: none;
    }
    .pills::-webkit-scrollbar { display: none; }
    .pill {
      flex-shrink: 0; padding: 10px 18px; border-radius: 999px;
      background: #fff; color: var(--ink);
      font-size: 13px; font-weight: 500;
      box-shadow: 0 2px 8px rgba(20,32,30,.05);
      transition: background .2s, color .2s;
    }
    .pill.active { background: var(--brand); color: #fff; }

    /* Properties */
    .properties-section { max-width: var(--container-max); margin: 26px auto 0; padding: 0 18px; }
    .section-title { font-size: 18px; font-weight: 600; margin-bottom: 14px; }
    .property-grid { display: grid; grid-template-columns: 1fr; gap: 18px; }

    .property-card {
      background: var(--card); border-radius: var(--radius);
      padding: 10px; box-shadow: 0 6px 22px rgba(20,32,30,.07);
      transition: transform .2s ease;
      display: flex; flex-direction: column;
    }
    .property-card:hover { transform: translateY(-4px); }
    .property-img-wrapper {
      position: relative; height: 220px; border-radius: 18px; overflow: hidden; background: #dfe8e6; display: block;
    }
    .property-img-wrapper img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .property-img-wrapper::after {
      content: ""; position: absolute; inset: 45% 0 0 0;
      background: linear-gradient(to top, rgba(0,0,0,.6), transparent);
    }
    .category-badge {
      position: absolute; top: 12px; left: 12px; z-index: 1;
      background: rgba(255,255,255,.92); color: var(--brand-dark);
      padding: 6px 12px; border-radius: 999px; font-size: 11px; font-weight: 600;
    }
    .img-info {
      position: absolute; left: 14px; right: 14px; bottom: 12px; z-index: 1; color: #fff;
    }
    .img-info .property-title {
      font-size: 17px; font-weight: 600;
      white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .property-location { font-size: 12px; opacity: .9; display: flex; align-items: center; gap: 4px; }
    .property-location svg { width: 13px; height: 13px; flex-shrink: 0; }

    .property-details { padding: 12px 8px 6px; display: flex; flex-direction: column; flex: 1; }
    .price-row { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
    .property-price { font-size: 19px; font-weight: 700; color: var(--brand-dark); }
    
    .go-btn {
      width: 38px; height: 38px; border-radius: 50%;
      background: var(--brand); color: #fff; display: grid; place-items: center;
      flex-shrink: 0; transition: transform 0.2s, background 0.2s; cursor: pointer;
    }
    .go-btn:hover { background: var(--brand-dark); transform: scale(1.08); }
    .go-btn svg { width: 18px; height: 18px; pointer-events: none; }

    .property-desc {
      font-size: 12.5px; color: var(--muted); margin-top: 8px; line-height: 1.55;
      display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2;
      -webkit-box-orient: vertical; overflow: hidden;
    }
    .card-agent-row {
      display: flex; align-items: center; gap: 10px;
      margin-top: auto; padding-top: 12px; border-top: 1px solid var(--line);
    }
    .card-agent-avatar {
      width: 36px; height: 36px; border-radius: 50%; object-fit: cover;
      background: var(--brand); color: #fff;
      display: flex; align-items: center; justify-content: center;
      font-size: 14px; font-weight: 600; flex-shrink: 0; overflow: hidden;
    }
    .card-agent-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .card-agent-info { font-size: 11.5px; color: var(--muted); min-width: 0; }
    .card-agent-name {
      font-weight: 600; font-size: 13px; color: var(--ink);
      white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }

    /* Bottom tab bar */
    .tabbar {
      position: fixed; left: 14px; right: 14px; bottom: calc(14px + env(safe-area-inset-bottom, 0px));
      z-index: 120; max-width: 460px; margin: 0 auto;
      background: #fff; border-radius: 999px; padding: 8px;
      display: flex; justify-content: space-between; align-items: center;
      box-shadow: 0 10px 30px rgba(20,32,30,.18);
    }
    .tab {
      flex: 1; display: flex; flex-direction: column; align-items: center; gap: 2px;
      padding: 8px 4px; border-radius: 999px;
      font-size: 10.5px; font-weight: 500; color: var(--muted); text-decoration: none;
    }
    .tab svg { width: 22px; height: 22px; }
    .tab.active { background: var(--brand); color: #fff; }

    /* Toast */
    .toast {
      position: fixed; left: 50%; top: 18px; transform: translate(-50%, -150%);
      z-index: 10000; width: min(92%, 420px);
      background: #fff; border-radius: 18px; padding: 14px 16px;
      box-shadow: 0 14px 40px rgba(20,32,30,.25);
      border-left: 5px solid var(--brand);
      transition: transform .35s ease;
    }
    .toast.show { transform: translate(-50%, 0); }
    .toast-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; }
    .toast-title { font-weight: 600; font-size: 14px; }
    .toast-close { background: none; font-size: 18px; color: var(--muted); padding: 0 4px; }
    .toast-msg { font-size: 13px; color: var(--muted); line