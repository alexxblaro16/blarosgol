<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BlarosGol — Mundial FIFA 2026</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Bebas+Neue&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        :root {
            --verde: #00a651;
            --verde-osc: #006d35;
            --verde-claro: #2ed573;
            --verde-glow: rgba(46,213,115,.25);
            --amarillo: #ffd60a;
            --amarillo-osc: #d4af0a;
            --gris-900: #15191c;
            --gris-850: #1c2125;
            --gris-800: #242a2e;
            --gris-700: #323a40;
            --gris-600: #4a545a;
            --gris-300: #b8c2c6;
            --gris-100: #f1f3f4;
            --rojo: #ef4444;
            --naranja: #ff9100;
            --blanco: #ffffff;
            --oro: #ffd700;
            --plata: #c0c0c0;
            --bronce: #cd7f32;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background:
                radial-gradient(ellipse at top, rgba(0,166,81,.08), transparent 70%),
                radial-gradient(ellipse at bottom right, rgba(255,214,10,.06), transparent 70%),
                linear-gradient(180deg, var(--gris-900) 0%, var(--gris-850) 100%);
            color: var(--gris-100);
            min-height: 100vh;
            line-height: 1.5;
        }
        a { color: inherit; text-decoration: none; }
        button {
            font-family: inherit;
            border: 0;
            cursor: pointer;
            transition: all .15s ease;
        }
        h1, h2, h3 { font-weight: 700; letter-spacing: -.01em; }
        code {
            font-family: 'Courier New', monospace;
            background: var(--gris-900);
            padding: 2px 8px;
            border-radius: 6px;
            color: var(--amarillo);
            font-weight: 600;
            font-size: .92em;
        }

        /* ===== TOPBAR ===== */
        .topbar {
            background: rgba(21,25,28,.85);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--gris-700);
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .topbar::after {
            content: '';
            position: absolute;
            left: 0; right: 0; bottom: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--verde) 0%, var(--amarillo) 50%, var(--verde) 100%);
        }
        .brand {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 26px;
            letter-spacing: 1.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .brand .ball {
            background: var(--amarillo);
            color: var(--gris-900);
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 18px;
            box-shadow: 0 0 24px rgba(255,214,10,.4);
        }
        .brand .txt { color: var(--blanco); }
        .brand .txt b { color: var(--verde-claro); font-weight: 400; }
        .nav {
            display: flex;
            gap: 4px;
            flex-wrap: wrap;
        }
        .nav a {
            padding: 9px 16px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            color: var(--gris-300);
            position: relative;
        }
        .nav a:hover { background: var(--gris-800); color: var(--blanco); }
        .nav a.active {
            background: var(--verde);
            color: var(--blanco);
            box-shadow: 0 4px 16px var(--verde-glow);
        }
        .user-pill {
            background: var(--gris-800);
            border: 1px solid var(--gris-700);
            padding: 6px 6px 6px 14px;
            border-radius: 999px;
            font-size: 13px;
            display: flex;
            gap: 10px;
            align-items: center;
            font-weight: 500;
        }
        .user-pill .pts {
            background: var(--amarillo);
            color: var(--gris-900);
            padding: 4px 11px;
            border-radius: 99px;
            font-weight: 800;
            font-size: 13px;
            box-shadow: 0 0 12px rgba(255,214,10,.25);
        }

        .container { max-width: 1240px; margin: 0 auto; padding: 28px 24px 60px; }

        .grid { display: grid; gap: 18px; }
        .grid.cols-2 { grid-template-columns: 1fr 1fr; }
        .grid.cols-3 { grid-template-columns: repeat(3, 1fr); }
        .grid.cols-4 { grid-template-columns: repeat(4, 1fr); }
        @media (max-width: 880px) {
            .grid.cols-2, .grid.cols-3, .grid.cols-4 { grid-template-columns: 1fr; }
        }

        /* ===== CARDS ===== */
        .card {
            background: linear-gradient(180deg, var(--gris-800) 0%, var(--gris-850) 100%);
            border-radius: 16px;
            padding: 22px;
            box-shadow: 0 4px 20px rgba(0,0,0,.22);
            border: 1px solid var(--gris-700);
            position: relative;
            overflow: hidden;
        }
        .card h2 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 14px;
            color: var(--blanco);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .card h2 .dot { width: 8px; height: 8px; border-radius: 50%; background: var(--amarillo); box-shadow: 0 0 12px var(--amarillo); }
        .card.green h2 .dot { background: var(--verde-claro); box-shadow: 0 0 12px var(--verde-claro); }
        .card.accent {
            background: linear-gradient(135deg, var(--verde) 0%, var(--verde-osc) 100%);
            border: 0;
        }

        /* ===== FORMS ===== */
        .input, select, textarea {
            width: 100%;
            background: var(--gris-900);
            border: 1.5px solid var(--gris-700);
            color: var(--gris-100);
            padding: 11px 14px;
            border-radius: 10px;
            font-family: inherit;
            font-size: 14px;
            outline: none;
            transition: all .15s;
        }
        .input:focus, select:focus, textarea:focus {
            border-color: var(--verde-claro);
            box-shadow: 0 0 0 3px var(--verde-glow);
        }
        .input::placeholder { color: var(--gris-600); }
        .label {
            display: block;
            font-size: 11px;
            color: var(--gris-300);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
        }
        .field { margin-bottom: 14px; }
        .btn {
            padding: 11px 20px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            letter-spacing: .3px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn:disabled { opacity: .5; cursor: not-allowed; }
        .btn.primary {
            background: linear-gradient(135deg, var(--verde-claro) 0%, var(--verde) 100%);
            color: white;
            box-shadow: 0 4px 14px rgba(0,166,81,.3);
        }
        .btn.primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(0,166,81,.4); }
        .btn.gold {
            background: linear-gradient(135deg, var(--amarillo) 0%, var(--amarillo-osc) 100%);
            color: var(--gris-900);
            box-shadow: 0 4px 14px rgba(255,214,10,.3);
        }
        .btn.gold:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(255,214,10,.45); }
        .btn.ghost {
            background: transparent;
            border: 1.5px solid var(--gris-700);
            color: var(--gris-100);
        }
        .btn.ghost:hover { background: var(--gris-800); border-color: var(--verde-claro); }
        .btn.danger { background: var(--rojo); color: white; }
        .btn.danger:hover { opacity: .85; }
        .btn.sm { padding: 7px 14px; font-size: 12px; }

        .row { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 11px;
            border-radius: 99px;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
        }
        .badge.green { background: rgba(46,213,115,.13); color: var(--verde-claro); border: 1px solid rgba(46,213,115,.4); }
        .badge.gold { background: rgba(255,214,10,.14); color: var(--amarillo); border: 1px solid rgba(255,214,10,.4); }
        .badge.gray { background: var(--gris-700); color: var(--gris-300); }
        .badge.red { background: rgba(239,68,68,.14); color: #ff7a7a; border: 1px solid rgba(239,68,68,.45); }

        /* ===== MATCH CARDS ===== */
        .match {
            background: linear-gradient(180deg, var(--gris-800) 0%, var(--gris-850) 100%);
            border-radius: 14px;
            padding: 18px;
            border: 1px solid var(--gris-700);
            display: flex;
            flex-direction: column;
            gap: 12px;
            position: relative;
            transition: all .2s ease;
        }
        .match:hover { border-color: var(--verde-claro); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.3); }
        .match.played { border-left: 3px solid var(--verde-claro); }
        .match .meta {
            font-size: 11px;
            color: var(--gris-300);
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            justify-content: space-between;
            font-weight: 600;
        }
        .match .teams {
            display: grid;
            grid-template-columns: 1fr 96px 1fr;
            gap: 8px;
            align-items: center;
        }
        .match .team {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }
        .match .team .flag-img {
            width: 56px;
            height: 38px;
            object-fit: cover;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,.35);
            background: var(--gris-900);
            border: 1px solid var(--gris-700);
        }
        .flag-mini {
            width: 22px;
            height: 15px;
            object-fit: cover;
            border-radius: 2px;
            box-shadow: 0 1px 3px rgba(0,0,0,.3);
            display: inline-block;
            vertical-align: middle;
            margin: 0 4px;
        }
        .flag-tiny {
            width: 18px;
            height: 12px;
            object-fit: cover;
            border-radius: 2px;
            display: inline-block;
            vertical-align: middle;
        }
        .match .team .name {
            font-size: 13px;
            font-weight: 700;
            text-align: center;
            color: var(--blanco);
            margin-top: 4px;
        }
        .match .team .code {
            font-size: 11px;
            color: var(--amarillo);
            letter-spacing: 2px;
            font-weight: 800;
            background: rgba(255,214,10,.1);
            padding: 2px 8px;
            border-radius: 4px;
            margin-top: 2px;
        }
        .match .score {
            text-align: center;
            font-family: 'Bebas Neue', sans-serif;
        }
        .match .score .real {
            color: var(--blanco);
            font-size: 38px;
            letter-spacing: 2px;
            line-height: 1;
            text-shadow: 0 2px 14px rgba(46,213,115,.3);
        }
        .match .score .vs {
            color: var(--gris-600);
            font-size: 18px;
            font-weight: 800;
            font-family: 'Inter', sans-serif;
        }
        .match .venue {
            font-size: 11px;
            color: var(--gris-300);
            text-align: center;
            opacity: .8;
        }
        .pred-inputs {
            display: grid;
            grid-template-columns: 1fr 24px 1fr auto;
            gap: 8px;
            align-items: center;
            margin-top: 4px;
            padding-top: 12px;
            border-top: 1px dashed var(--gris-700);
        }
        .pred-inputs input {
            text-align: center;
            font-weight: 700;
            font-size: 18px;
            padding: 8px;
        }
        .pred-inputs .vs { text-align: center; color: var(--gris-600); font-weight: 800; font-size: 16px; }
        .your-pred {
            background: rgba(255,214,10,.1);
            border: 1px dashed var(--amarillo);
            border-radius: 8px;
            padding: 6px 10px;
            font-size: 12px;
            color: var(--amarillo);
            font-weight: 700;
            text-align: center;
        }

        /* ===== HERO ===== */
        .hero {
            background:
                radial-gradient(circle at 80% 30%, rgba(255,214,10,.18), transparent 60%),
                linear-gradient(135deg, var(--verde) 0%, var(--verde-osc) 100%);
            border-radius: 20px;
            padding: 36px 40px;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(0,166,81,.2);
        }
        .hero::before {
            content: '⚽';
            position: absolute;
            right: -10px;
            top: -20px;
            font-size: 180px;
            opacity: .07;
            transform: rotate(-15deg);
        }
        .hero h1 {
            font-size: 34px;
            margin-bottom: 8px;
            color: var(--blanco);
            font-weight: 800;
            position: relative;
        }
        .hero p { color: rgba(255,255,255,.92); position: relative; font-size: 15px; }
        .hero .chip {
            display: inline-block;
            background: var(--amarillo);
            color: var(--gris-900);
            padding: 6px 14px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 800;
            margin-bottom: 14px;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            position: relative;
            box-shadow: 0 4px 14px rgba(255,214,10,.25);
        }

        /* ===== STATS ===== */
        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 22px; }
        @media (max-width: 880px) { .stats { grid-template-columns: 1fr 1fr; } }
        .stat {
            background: linear-gradient(180deg, var(--gris-800), var(--gris-850));
            border: 1px solid var(--gris-700);
            border-radius: 14px;
            padding: 18px;
            position: relative;
            overflow: hidden;
        }
        .stat::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 3px; height: 100%;
            background: linear-gradient(180deg, var(--verde-claro), transparent);
        }
        .stat.gold::before { background: linear-gradient(180deg, var(--amarillo), transparent); }
        .stat .label { font-size: 11px; color: var(--gris-300); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px; font-weight: 700; }
        .stat .value { font-family: 'Bebas Neue', sans-serif; font-size: 40px; letter-spacing: 1px; color: var(--blanco); line-height: 1; }
        .stat .value.gold { color: var(--amarillo); }
        .stat .value.green { color: var(--verde-claro); }
        .stat .sub { font-size: 11px; color: var(--gris-300); margin-top: 6px; }

        /* ===== TABLE / RANKING ===== */
        table { width: 100%; border-collapse: collapse; }
        table th, table td {
            text-align: left;
            padding: 11px 14px;
            border-bottom: 1px solid var(--gris-700);
            font-size: 14px;
        }
        table th {
            font-size: 11px;
            color: var(--gris-300);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
            background: var(--gris-900);
        }
        table tr:hover td { background: rgba(255,255,255,.02); }
        .pos {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 22px;
            color: var(--amarillo);
            width: 44px;
            text-align: center;
        }
        .pos.first { color: var(--oro); }
        .pos.second { color: var(--plata); }
        .pos.third { color: var(--bronce); }

        /* ===== PODIUM ===== */
        .podium {
            display: grid;
            grid-template-columns: 1fr 1.2fr 1fr;
            gap: 14px;
            align-items: end;
            margin-bottom: 20px;
        }
        .podium .place {
            background: linear-gradient(180deg, var(--gris-800), var(--gris-850));
            border-radius: 14px 14px 0 0;
            text-align: center;
            padding: 18px 12px 22px;
            position: relative;
            border: 1px solid var(--gris-700);
            border-bottom: 0;
        }
        .podium .place.first {
            background: linear-gradient(180deg, rgba(255,215,0,.18), var(--gris-850));
            border-color: var(--oro);
            padding-top: 28px;
            padding-bottom: 32px;
        }
        .podium .place.second {
            background: linear-gradient(180deg, rgba(192,192,192,.14), var(--gris-850));
            border-color: var(--plata);
        }
        .podium .place.third {
            background: linear-gradient(180deg, rgba(205,127,50,.14), var(--gris-850));
            border-color: var(--bronce);
        }
        .podium .medal { font-size: 38px; margin-bottom: 6px; line-height: 1; }
        .podium .first .medal { font-size: 50px; }
        .podium .name { font-weight: 700; font-size: 14px; color: var(--blanco); margin-bottom: 6px; }
        .podium .pts {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 30px;
            color: var(--amarillo);
            letter-spacing: 1.5px;
        }
        .podium .first .pts { font-size: 38px; color: var(--oro); }

        /* ===== TOAST ===== */
        .toast {
            position: fixed;
            top: 86px;
            right: 24px;
            padding: 14px 20px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--verde-claro), var(--verde));
            color: white;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 12px 36px rgba(0,166,81,.4);
            z-index: 100;
            min-width: 280px;
            animation: slideIn .25s ease;
        }
        .toast.error { background: linear-gradient(135deg, #ff6b6b, var(--rojo)); box-shadow: 0 12px 36px rgba(239,68,68,.35); }
        @keyframes slideIn {
            from { transform: translateX(40px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* ===== AUTH ===== */
        .auth-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow: hidden;
        }
        .auth-wrap::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 20% 30%, rgba(0,166,81,.18), transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(255,214,10,.12), transparent 50%);
            pointer-events: none;
        }
        .auth-box {
            max-width: 440px;
            width: 100%;
            background: linear-gradient(180deg, var(--gris-800) 0%, var(--gris-850) 100%);
            border-radius: 20px;
            padding: 36px;
            border: 1px solid var(--gris-700);
            box-shadow: 0 20px 60px rgba(0,0,0,.4);
            position: relative;
            z-index: 1;
        }
        .auth-box .logo {
            text-align: center;
            margin-bottom: 22px;
        }
        .auth-box .logo .ball {
            display: inline-block;
            background: linear-gradient(135deg, var(--amarillo), var(--amarillo-osc));
            color: var(--gris-900);
            padding: 14px;
            border-radius: 16px;
            font-size: 32px;
            box-shadow: 0 8px 24px rgba(255,214,10,.4);
            margin-bottom: 14px;
        }
        .auth-box h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 34px;
            letter-spacing: 2px;
            margin-bottom: 4px;
        }
        .auth-box .sub { text-align: center; color: var(--gris-300); margin-bottom: 24px; font-size: 13px; }
        .tabs {
            display: flex;
            gap: 4px;
            margin-bottom: 22px;
            background: var(--gris-900);
            padding: 4px;
            border-radius: 12px;
            border: 1px solid var(--gris-700);
        }
        .tabs button {
            flex: 1;
            padding: 10px 14px;
            background: transparent;
            color: var(--gris-300);
            border-radius: 10px;
            font-weight: 700;
            font-size: 13px;
            letter-spacing: .5px;
        }
        .tabs button.active {
            background: linear-gradient(135deg, var(--verde-claro), var(--verde));
            color: white;
            box-shadow: 0 4px 14px rgba(0,166,81,.3);
        }

        .empty {
            text-align: center;
            padding: 40px;
            color: var(--gris-300);
            font-size: 14px;
        }
        .empty .icon { font-size: 48px; margin-bottom: 12px; opacity: .5; }

        /* ===== MODAL ===== */
        .modal-bg {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.75);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 80;
            padding: 20px;
            animation: fadeIn .15s;
        }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .modal {
            max-width: 600px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
        }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body x-data="app()" x-init="init()" x-cloak>

<!-- TOAST -->
<template x-if="toast">
    <div class="toast" :class="toast.kind === 'error' ? 'error' : ''" x-text="toast.message"></div>
</template>

<!-- AUTH -->
<template x-if="!user">
    <div class="auth-wrap">
        <div class="auth-box">
            <div class="logo">
                <div class="ball">⚽</div>
                <h1>BLAROS<span style="color:var(--amarillo)">GOL</span></h1>
                <p class="sub">Predicciones · Mundial FIFA 2026</p>
            </div>

            <div class="tabs">
                <button @click="authTab='login'" :class="authTab==='login' ? 'active' : ''">Entrar</button>
                <button @click="authTab='register'" :class="authTab==='register' ? 'active' : ''">Registrarse</button>
            </div>

            <form x-show="authTab==='login'" @submit.prevent="login()">
                <div class="field"><label class="label">Email</label><input class="input" type="email" x-model="loginForm.email" required></div>
                <div class="field"><label class="label">Contraseña</label><input class="input" type="password" x-model="loginForm.password" required></div>
                <button class="btn primary" style="width:100%" :disabled="loading">
                    <span x-text="loading ? 'Entrando...' : 'Entrar'"></span>
                </button>
                <p style="margin-top:18px; font-size:12px; color:var(--gris-300); text-align:center">
                    Demo admin: <code>admin@mundial2026.test</code> / <code>admin1234</code>
                </p>
            </form>

            <form x-show="authTab==='register'" @submit.prevent="register()">
                <div class="field"><label class="label">Nombre</label><input class="input" type="text" x-model="registerForm.name" required></div>
                <div class="field"><label class="label">Email</label><input class="input" type="email" x-model="registerForm.email" required></div>
                <div class="field"><label class="label">Contraseña (mín. 6)</label><input class="input" type="password" x-model="registerForm.password" required minlength="6"></div>
                <button class="btn gold" style="width:100%" :disabled="loading">
                    <span x-text="loading ? 'Creando cuenta...' : 'Crear cuenta'"></span>
                </button>
                <p style="margin-top:18px; font-size:12px; color:var(--gris-300); text-align:center">Recibirás un email con tus credenciales</p>
            </form>
        </div>
    </div>
</template>

<!-- APP -->
<template x-if="user">
    <div>
        <div class="topbar">
            <div class="brand"><span class="ball">⚽</span><span class="txt">BLAROS<b>GOL</b></span></div>
            <div class="nav">
                <a href="#" :class="route==='dashboard' ? 'active' : ''" @click.prevent="go('dashboard')">Inicio</a>
                <a href="#" :class="route==='matches' ? 'active' : ''" @click.prevent="go('matches')">Partidos</a>
                <a href="#" :class="route==='predictions' ? 'active' : ''" @click.prevent="go('predictions')">Mis quinielas</a>
                <a href="#" :class="route==='communities' ? 'active' : ''" @click.prevent="go('communities')">Comunidades</a>
                <template x-if="user.is_admin">
                    <a href="#" :class="route==='admin' ? 'active' : ''" @click.prevent="go('admin')">Admin</a>
                </template>
            </div>
            <div class="row">
                <div class="user-pill">
                    <span x-text="user.name"></span>
                    <span class="pts" x-text="totalPoints + ' pts'"></span>
                </div>
                <button class="btn ghost sm" @click="logout()">Salir</button>
            </div>
        </div>

        <div class="container">

            <!-- ===== DASHBOARD ===== -->
            <template x-if="route==='dashboard'">
                <div>
                    <div class="hero">
                        <span class="chip">⚡ Fase actual: <span x-text="currentPhase?.name || 'Por iniciar'"></span></span>
                        <h1>Hola, <span x-text="user.name.split(' ')[0]"></span> 👋</h1>
                        <p>Acumulas <strong x-text="totalPoints"></strong> puntos en <strong x-text="predictions.length"></strong> predicciones. ¡A por la quiniela perfecta!</p>
                    </div>

                    <div class="stats">
                        <div class="stat gold">
                            <div class="label">Tus puntos</div>
                            <div class="value gold" x-text="totalPoints"></div>
                            <div class="sub" x-text="averagePoints + ' pts por predicción'"></div>
                        </div>
                        <div class="stat">
                            <div class="label">Predicciones</div>
                            <div class="value green" x-text="predictions.length"></div>
                            <div class="sub" x-text="resolvedPredictions + ' resueltas'"></div>
                        </div>
                        <div class="stat">
                            <div class="label">Exactos</div>
                            <div class="value" x-text="exactHits"></div>
                            <div class="sub">3 pts cada uno</div>
                        </div>
                        <div class="stat">
                            <div class="label">Comunidades</div>
                            <div class="value" x-text="myCommunities.owned.length + myCommunities.joined.length"></div>
                            <div class="sub" x-text="myCommunities.owned.length + ' creadas'"></div>
                        </div>
                    </div>

                    <div class="grid cols-3">
                        <div class="card green" style="grid-column: span 2">
                            <h2><span class="dot"></span>Próximos partidos</h2>
                            <template x-for="m in upcomingMatches" :key="m.id">
                                <div style="padding:12px 0; border-bottom: 1px solid var(--gris-700); display: flex; justify-content: space-between; align-items: center; gap: 12px">
                                    <div style="display: flex; align-items: center; gap: 10px; flex: 1; min-width: 0">
                                        <img class="flag-mini" :src="flagSrc(m.home_code)" :alt="m.home_code" loading="lazy">
                                        <div style="min-width: 0">
                                            <div style="font-size: 14px; font-weight: 600">
                                                <span x-text="m.home_team"></span>
                                                <span style="color: var(--gris-600); font-weight: 400"> vs </span>
                                                <span x-text="m.away_team"></span>
                                            </div>
                                            <div style="font-size: 11px; color: var(--gris-300)" x-text="fmt(m.kick_off_at) + ' · ' + (m.venue || 'Sede TBD')"></div>
                                        </div>
                                        <img class="flag-mini" :src="flagSrc(m.away_code)" :alt="m.away_code" loading="lazy">
                                    </div>
                                    <button class="btn primary sm" @click="go('matches')">Predecir</button>
                                </div>
                            </template>
                            <template x-if="upcomingMatches.length === 0">
                                <div class="empty"><div class="icon">⚽</div>Sin partidos próximos</div>
                            </template>
                        </div>

                        <div class="card">
                            <h2><span class="dot"></span>Cómo se puntúa</h2>
                            <div style="display: flex; flex-direction: column; gap: 12px; margin-top: 6px">
                                <div style="display: flex; gap: 12px; align-items: center; padding: 10px; background: rgba(46,213,115,.08); border-radius: 10px; border-left: 3px solid var(--verde-claro)">
                                    <div style="font-family: 'Bebas Neue'; font-size: 28px; color: var(--verde-claro)">3</div>
                                    <div style="font-size: 12px"><b>Resultado exacto</b><br><span style="color: var(--gris-300)">Aciertas el marcador</span></div>
                                </div>
                                <div style="display: flex; gap: 12px; align-items: center; padding: 10px; background: rgba(255,214,10,.08); border-radius: 10px; border-left: 3px solid var(--amarillo)">
                                    <div style="font-family: 'Bebas Neue'; font-size: 28px; color: var(--amarillo)">1</div>
                                    <div style="font-size: 12px"><b>Solo ganador</b><br><span style="color: var(--gris-300)">Aciertas quién gana</span></div>
                                </div>
                                <div style="display: flex; gap: 12px; align-items: center; padding: 10px; background: rgba(239,68,68,.08); border-radius: 10px; border-left: 3px solid var(--rojo)">
                                    <div style="font-family: 'Bebas Neue'; font-size: 28px; color: #ff7a7a">0</div>
                                    <div style="font-size: 12px"><b>Fallaste</b><br><span style="color: var(--gris-300)">Predice mejor el próximo</span></div>
                                </div>
                            </div>
                            <p style="margin-top: 16px; font-size: 11px; color: var(--gris-300); text-align: center">Predice hasta el día anterior al partido</p>
                        </div>
                    </div>
                </div>
            </template>

            <!-- ===== MATCHES ===== -->
            <template x-if="route==='matches'">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 10px">
                        <h1 style="font-family: 'Bebas Neue'; font-size: 34px; letter-spacing: 1.5px">PARTIDOS</h1>
                        <div class="row">
                            <select class="input" x-model="phaseFilter" style="width: auto; min-width: 200px">
                                <option value="">Todas las fases</option>
                                <template x-for="p in phases" :key="p.id">
                                    <option :value="p.code" x-text="p.name"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                    <div class="grid cols-2">
                        <template x-for="m in filteredMatches" :key="m.id">
                            <div class="match" :class="m.status === 'played' ? 'played' : ''">
                                <div class="meta">
                                    <span x-text="m.phase?.name"></span>
                                    <span x-text="fmt(m.kick_off_at)"></span>
                                </div>
                                <div class="teams">
                                    <div class="team">
                                        <img class="flag-img" :src="flagSrc(m.home_code)" :alt="m.home_code" loading="lazy">
                                        <span class="name" x-text="m.home_team"></span>
                                        <span class="code" x-text="m.home_code"></span>
                                    </div>
                                    <div class="score">
                                        <template x-if="m.status === 'played'">
                                            <div class="real"><span x-text="m.home_score"></span> <span style="color: var(--gris-600)">-</span> <span x-text="m.away_score"></span></div>
                                        </template>
                                        <template x-if="m.status !== 'played'">
                                            <div class="vs">VS</div>
                                        </template>
                                    </div>
                                    <div class="team">
                                        <img class="flag-img" :src="flagSrc(m.away_code)" :alt="m.away_code" loading="lazy">
                                        <span class="name" x-text="m.away_team"></span>
                                        <span class="code" x-text="m.away_code"></span>
                                    </div>
                                </div>
                                <div class="venue" x-text="m.venue"></div>
                                <template x-if="existingPrediction(m)">
                                    <div class="your-pred">
                                        TU PREDICCIÓN: <span x-text="existingPrediction(m).home_score + ' - ' + existingPrediction(m).away_score"></span>
                                        <template x-if="existingPrediction(m).points !== null">
                                            <span class="badge" :class="existingPrediction(m).points === 3 ? 'green' : (existingPrediction(m).points === 1 ? 'gold' : 'red')" style="margin-left: 8px" x-text="existingPrediction(m).points + ' pts'"></span>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="canPredict(m) && currentPhase && m.phase_id === currentPhase.id">
                                    <div class="pred-inputs">
                                        <input class="input" type="number" min="0" max="50" placeholder="0" x-model.number="draft(m.id).home_score">
                                        <span class="vs">-</span>
                                        <input class="input" type="number" min="0" max="50" placeholder="0" x-model.number="draft(m.id).away_score">
                                        <button class="btn gold sm" @click="savePrediction(m)">
                                            <span x-text="existingPrediction(m) ? 'Cambiar' : 'Predecir'"></span>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                    <template x-if="filteredMatches.length === 0">
                        <div class="card empty"><div class="icon">⚽</div>No hay partidos disponibles</div>
                    </template>
                </div>
            </template>

            <!-- ===== PREDICTIONS ===== -->
            <template x-if="route==='predictions'">
                <div>
                    <h1 style="font-family: 'Bebas Neue'; font-size: 34px; letter-spacing: 1.5px; margin-bottom: 18px">MIS QUINIELAS</h1>
                    <div class="card" style="padding: 0; overflow: hidden">
                        <table>
                            <thead><tr><th>Fase</th><th>Partido</th><th style="text-align:center">Tu predicción</th><th style="text-align:center">Resultado</th><th style="text-align:center">Puntos</th></tr></thead>
                            <tbody>
                                <template x-for="p in predictions" :key="p.id">
                                    <tr>
                                        <td><span class="badge gray" x-text="p.match?.phase?.name"></span></td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 8px">
                                                <img class="flag-tiny" :src="flagSrc(p.match?.home_code)" :alt="p.match?.home_code" loading="lazy">
                                                <strong x-text="p.match?.home_team"></strong>
                                                <span style="color: var(--gris-600)">vs</span>
                                                <strong x-text="p.match?.away_team"></strong>
                                                <img class="flag-tiny" :src="flagSrc(p.match?.away_code)" :alt="p.match?.away_code" loading="lazy">
                                            </div>
                                        </td>
                                        <td style="text-align:center; font-weight: 700; font-family: 'Bebas Neue'; font-size: 20px; color: var(--amarillo)">
                                            <span x-text="p.home_score"></span> - <span x-text="p.away_score"></span>
                                        </td>
                                        <td style="text-align:center; font-family: 'Bebas Neue'; font-size: 20px">
                                            <template x-if="p.match?.status === 'played'">
                                                <span style="color: var(--verde-claro)" x-text="p.match.home_score + ' - ' + p.match.away_score"></span>
                                            </template>
                                            <template x-if="p.match?.status !== 'played'">
                                                <span style="color: var(--gris-600); font-family: 'Inter'; font-size: 12px">pendiente</span>
                                            </template>
                                        </td>
                                        <td style="text-align:center">
                                            <template x-if="p.points !== null && p.points !== undefined">
                                                <span class="badge" :class="p.points === 3 ? 'green' : (p.points === 1 ? 'gold' : 'red')" x-text="p.points + ' pts'"></span>
                                            </template>
                                            <template x-if="p.points === null || p.points === undefined">
                                                <span style="color: var(--gris-600)">—</span>
                                            </template>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                        <template x-if="predictions.length === 0">
                            <div class="empty"><div class="icon">📊</div>Aún no has hecho ninguna predicción</div>
                        </template>
                    </div>
                </div>
            </template>

            <!-- ===== COMMUNITIES ===== -->
            <template x-if="route==='communities'">
                <div>
                    <h1 style="font-family: 'Bebas Neue'; font-size: 34px; letter-spacing: 1.5px; margin-bottom: 18px">COMUNIDADES</h1>
                    <div class="grid cols-2">
                        <div class="card green">
                            <h2><span class="dot"></span>Crear comunidad</h2>
                            <form @submit.prevent="createCommunity()">
                                <div class="field"><label class="label">Nombre</label><input class="input" type="text" x-model="newCommunity.name" required placeholder="Mi liga de amigos"></div>
                                <div class="field"><label class="label">Descripción</label><input class="input" type="text" x-model="newCommunity.description" placeholder="Para el grupo de WhatsApp"></div>
                                <button class="btn primary">Crear comunidad</button>
                            </form>
                        </div>
                        <div class="card">
                            <h2><span class="dot"></span>Unirme con código</h2>
                            <form @submit.prevent="joinByCode()">
                                <div class="field"><label class="label">Código de comunidad</label><input class="input" type="text" x-model="joinCode" placeholder="AB12CD34" required style="text-transform: uppercase; letter-spacing: 3px; font-family: 'Courier New'; font-size: 16px; text-align: center"></div>
                                <button class="btn gold">Solicitar unirme</button>
                            </form>
                        </div>
                    </div>

                    <h2 style="margin: 32px 0 14px; font-family: 'Bebas Neue'; font-size: 24px; letter-spacing: 1.2px">MIS COMUNIDADES</h2>
                    <div class="grid cols-2">
                        <template x-for="c in myCommunities.owned" :key="'o-'+c.id">
                            <div class="card green">
                                <h2><span class="dot"></span><span x-text="c.name"></span> <span class="badge green">CREADOR</span></h2>
                                <p style="font-size: 13px; color: var(--gris-300); margin-bottom: 14px">
                                    <span x-text="c.description || ''"></span>
                                </p>
                                <div style="margin-bottom: 14px">
                                    <span style="font-size: 11px; color: var(--gris-300); text-transform: uppercase; letter-spacing: 1px">Código de invitación</span>
                                    <div style="margin-top: 6px"><code style="font-size: 16px; letter-spacing: 2px" x-text="c.code"></code> <button class="btn ghost sm" @click="copy(c.code)" style="margin-left: 6px">📋 Copiar</button></div>
                                </div>
                                <p style="font-size: 12px; color: var(--gris-300); margin-bottom: 14px"><span x-text="(c.accepted_members_count || 0) + ' miembros'"></span></p>
                                <div class="row">
                                    <button class="btn primary sm" @click="viewRanking(c.id)">🏆 Ranking</button>
                                    <button class="btn ghost sm" @click="viewRequests(c.id)">📋 Solicitudes</button>
                                </div>
                            </div>
                        </template>
                        <template x-for="c in myCommunities.joined" :key="'j-'+c.id">
                            <div class="card">
                                <h2><span class="dot"></span><span x-text="c.name"></span> <span class="badge gold">MIEMBRO</span></h2>
                                <p style="font-size: 13px; color: var(--gris-300); margin-bottom: 14px" x-text="c.description || ''"></p>
                                <p style="font-size: 12px; color: var(--gris-300); margin-bottom: 14px">Código: <code x-text="c.code"></code> · <span x-text="(c.accepted_members_count || 0) + ' miembros'"></span></p>
                                <button class="btn ghost sm" @click="viewRanking(c.id)">🏆 Ver ranking</button>
                            </div>
                        </template>
                        <template x-if="myCommunities.owned.length + myCommunities.joined.length === 0">
                            <div class="card empty" style="grid-column: span 2"><div class="icon">👥</div>Aún no estás en ninguna comunidad. Crea una o únete con un código.</div>
                        </template>
                    </div>
                </div>
            </template>

            <!-- ===== ADMIN ===== -->
            <template x-if="route==='admin' && user.is_admin">
                <div>
                    <h1 style="font-family: 'Bebas Neue'; font-size: 34px; letter-spacing: 1.5px; margin-bottom: 18px">PANEL ADMIN</h1>
                    <div class="grid cols-2">
                        <div class="card green">
                            <h2><span class="dot"></span>Fases del torneo</h2>
                            <template x-for="p in phases" :key="p.id">
                                <div style="display:flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid var(--gris-700)">
                                    <div>
                                        <strong x-text="p.name"></strong>
                                        <template x-if="p.is_current"><span class="badge green" style="margin-left:8px">ACTUAL</span></template>
                                    </div>
                                    <button class="btn ghost sm" @click="activatePhase(p.code)" x-show="!p.is_current">Activar</button>
                                </div>
                            </template>
                        </div>

                        <div class="card">
                            <h2><span class="dot"></span>Importar partidos</h2>
                            <p style="font-size: 13px; color: var(--gris-300); margin-bottom: 14px">Sube un fichero CSV o JSON con los partidos.</p>
                            <form @submit.prevent="uploadMatches()">
                                <div class="field">
                                    <input class="input" type="file" accept=".csv,.json" @change="uploadFile = $event.target.files[0]">
                                </div>
                                <button class="btn gold">Importar</button>
                            </form>
                            <details style="margin-top:18px">
                                <summary style="cursor:pointer; font-size:12px; color:var(--gris-300); user-select: none">📄 Ver formato CSV esperado</summary>
                                <pre style="background:var(--gris-900); padding:12px; margin-top:10px; border-radius:10px; font-size:11px; overflow:auto; color: var(--verde-claro)">phase_code,home_team,away_team,home_code,away_code,kick_off_at,venue,home_score,away_score
GROUP,España,Polonia,ESP,POL,2026-06-13 18:00:00,MetLife Stadium,,
GROUP,Argentina,Arabia Saudí,ARG,KSA,2026-06-13 21:00:00,AT&T Stadium,,</pre>
                            </details>
                        </div>
                    </div>

                    <h2 style="margin: 32px 0 14px; font-family: 'Bebas Neue'; font-size: 24px; letter-spacing: 1.2px">ACTUALIZAR RESULTADOS</h2>
                    <div class="grid cols-2">
                        <template x-for="m in pendingMatches.slice(0, 20)" :key="m.id">
                            <div class="match">
                                <div class="meta">
                                    <span x-text="m.phase?.name"></span>
                                    <span x-text="fmt(m.kick_off_at)"></span>
                                </div>
                                <div class="teams">
                                    <div class="team">
                                        <img class="flag-img" :src="flagSrc(m.home_code)" :alt="m.home_code" loading="lazy">
                                        <span class="name" x-text="m.home_team"></span>
                                        <span class="code" x-text="m.home_code"></span>
                                    </div>
                                    <div class="score"><div class="vs">VS</div></div>
                                    <div class="team">
                                        <img class="flag-img" :src="flagSrc(m.away_code)" :alt="m.away_code" loading="lazy">
                                        <span class="name" x-text="m.away_team"></span>
                                        <span class="code" x-text="m.away_code"></span>
                                    </div>
                                </div>
                                <div class="pred-inputs">
                                    <input class="input" type="number" min="0" max="50" x-model.number="resultDraftRef(m.id).home_score">
                                    <span class="vs">-</span>
                                    <input class="input" type="number" min="0" max="50" x-model.number="resultDraftRef(m.id).away_score">
                                    <button class="btn primary sm" @click="saveResult(m)">Guardar</button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <!-- ===== MODAL RANKING ===== -->
            <template x-if="rankingModal">
                <div class="modal-bg" @click.self="rankingModal=null">
                    <div class="card modal">
                        <h2><span class="dot"></span>🏆 Ranking — <span x-text="rankingModal.community.name"></span></h2>

                        <template x-if="rankingModal.ranking.length >= 3">
                            <div class="podium">
                                <div class="place second">
                                    <div class="medal">🥈</div>
                                    <div class="name" x-text="rankingModal.ranking[1]?.name"></div>
                                    <div class="pts"><span x-text="rankingModal.ranking[1]?.total_points"></span> pts</div>
                                </div>
                                <div class="place first">
                                    <div class="medal">🥇</div>
                                    <div class="name" x-text="rankingModal.ranking[0]?.name"></div>
                                    <div class="pts"><span x-text="rankingModal.ranking[0]?.total_points"></span> pts</div>
                                </div>
                                <div class="place third">
                                    <div class="medal">🥉</div>
                                    <div class="name" x-text="rankingModal.ranking[2]?.name"></div>
                                    <div class="pts"><span x-text="rankingModal.ranking[2]?.total_points"></span> pts</div>
                                </div>
                            </div>
                        </template>

                        <table>
                            <thead><tr><th>Pos</th><th>Usuario</th><th style="text-align:center">Predicciones</th><th style="text-align:right">Puntos</th></tr></thead>
                            <tbody>
                                <template x-for="(r, i) in rankingModal.ranking" :key="r.user_id">
                                    <tr>
                                        <td class="pos" :class="i===0 ? 'first' : (i===1 ? 'second' : (i===2 ? 'third' : ''))" x-text="(i+1) + 'º'"></td>
                                        <td x-text="r.name"></td>
                                        <td style="text-align:center" x-text="r.predictions_count"></td>
                                        <td style="text-align:right"><span class="badge green" x-text="r.total_points + ' pts'"></span></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                        <template x-if="rankingModal.ranking.length === 0">
                            <div class="empty">Sin participantes con puntos aún</div>
                        </template>
                        <div style="margin-top: 18px; text-align: right">
                            <button class="btn ghost" @click="rankingModal=null">Cerrar</button>
                        </div>
                    </div>
                </div>
            </template>

            <!-- ===== MODAL REQUESTS ===== -->
            <template x-if="requestsModal">
                <div class="modal-bg" @click.self="requestsModal=null">
                    <div class="card modal">
                        <h2><span class="dot"></span>Solicitudes pendientes</h2>
                        <template x-for="r in requestsModal.list" :key="r.id">
                            <div style="display:flex; justify-content: space-between; padding:14px 0; border-bottom: 1px solid var(--gris-700); align-items: center">
                                <div>
                                    <div style="font-weight: 700" x-text="r.name"></div>
                                    <div style="font-size:11px; color: var(--gris-300)" x-text="r.email"></div>
                                </div>
                                <button class="btn primary sm" @click="acceptRequest(requestsModal.communityId, r.id)">Aceptar</button>
                            </div>
                        </template>
                        <template x-if="requestsModal.list.length === 0">
                            <div class="empty"><div class="icon">📭</div>Sin solicitudes pendientes</div>
                        </template>
                        <div style="margin-top: 18px; text-align: right">
                            <button class="btn ghost" @click="requestsModal=null">Cerrar</button>
                        </div>
                    </div>
                </div>
            </template>

        </div>
    </div>
</template>

<script>
// Mapeo de codigo FIFA (3 letras) a ISO 3166-1 alpha-2 (para flagcdn.com)
const FIFA_TO_ISO = {
    MEX:'mx', RSA:'za', KOR:'kr', CZE:'cz',
    CAN:'ca', BIH:'ba', QAT:'qa', SUI:'ch',
    BRA:'br', MAR:'ma', HAI:'ht', SCO:'gb-sct',
    USA:'us', PAR:'py', AUS:'au', TUR:'tr',
    GER:'de', CUW:'cw', CIV:'ci', ECU:'ec',
    NED:'nl', JPN:'jp', SWE:'se', TUN:'tn',
    BEL:'be', EGY:'eg', IRN:'ir', NZL:'nz',
    ESP:'es', CPV:'cv', KSA:'sa', URU:'uy',
    FRA:'fr', SEN:'sn', IRQ:'iq', NOR:'no',
    ARG:'ar', ALG:'dz', AUT:'at', JOR:'jo',
    POR:'pt', COD:'cd', UZB:'uz', COL:'co',
    ENG:'gb-eng', CRO:'hr', GHA:'gh', PAN:'pa',
    // Otros codigos que pueden aparecer
    POL:'pl', DEN:'dk', NGA:'ng', JAM:'jm', ITA:'it',
};

function app() {
    return {
        // Estado
        user: null,
        token: localStorage.getItem('mundial_token') || null,
        route: 'dashboard',
        authTab: 'login',
        loading: false,
        toast: null,

        // Forms
        loginForm: { email: '', password: '' },
        registerForm: { name: '', email: '', password: '' },
        newCommunity: { name: '', description: '' },
        joinCode: '',
        uploadFile: null,
        phaseFilter: '',

        // Datos
        matches: [],
        currentPhase: null,
        phases: [],
        predictions: [],
        myCommunities: { owned: [], joined: [] },
        rankingModal: null,
        requestsModal: null,
        predDraft: {},
        resultDraft: {},
        totalPoints: 0,

        // ===== Computed =====
        get upcomingMatches() {
            return this.matches.filter(m => m.status !== 'played').slice(0, 5);
        },
        get pendingMatches() {
            return this.matches.filter(m => m.status !== 'played');
        },
        get filteredMatches() {
            if (!this.phaseFilter) return this.matches;
            return this.matches.filter(m => m.phase?.code === this.phaseFilter);
        },
        get resolvedPredictions() {
            return this.predictions.filter(p => p.points !== null && p.points !== undefined).length;
        },
        get exactHits() {
            return this.predictions.filter(p => p.points === 3).length;
        },
        get averagePoints() {
            if (!this.resolvedPredictions) return '0.0';
            return (this.totalPoints / this.resolvedPredictions).toFixed(1);
        },

        // ===== Init =====
        async init() {
            if (this.token) {
                await this.fetchMe();
                if (this.user) await this.loadAll();
            }
        },

        // ===== Helpers =====
        flagSrc(code) {
            if (!code) return '';
            const iso = FIFA_TO_ISO[code.toUpperCase()];
            if (!iso) return '';
            return `https://flagcdn.com/w80/${iso}.png`;
        },
        flagSrc2x(code) {
            if (!code) return '';
            const iso = FIFA_TO_ISO[code.toUpperCase()];
            if (!iso) return '';
            return `https://flagcdn.com/w160/${iso}.png 2x`;
        },
        notify(msg, kind = 'ok') {
            this.toast = { message: msg, kind };
            setTimeout(() => { this.toast = null; }, 3500);
        },
        async copy(text) {
            try {
                await navigator.clipboard.writeText(text);
                this.notify('Código copiado al portapapeles');
            } catch (e) { this.notify('No se pudo copiar', 'error'); }
        },
        fmt(dt) {
            if (!dt) return '';
            const d = new Date(dt);
            return d.toLocaleDateString('es-ES', { day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' });
        },
        canPredict(m) {
            if (m.status === 'played') return false;
            const today = new Date(); today.setHours(0,0,0,0);
            const koDay = new Date(m.kick_off_at); koDay.setHours(0,0,0,0);
            return koDay > today;
        },
        existingPrediction(m) {
            return this.predictions.find(p => p.match_id === m.id);
        },
        draft(id) {
            if (!this.predDraft[id]) {
                const ex = this.predictions.find(p => p.match_id === id);
                this.predDraft[id] = { home_score: ex?.home_score ?? '', away_score: ex?.away_score ?? '' };
            }
            return this.predDraft[id];
        },
        resultDraftRef(id) {
            if (!this.resultDraft[id]) this.resultDraft[id] = { home_score: '', away_score: '' };
            return this.resultDraft[id];
        },
        async api(method, path, body = null, isForm = false) {
            const opts = { method, headers: { 'Accept': 'application/json' } };
            if (this.token) opts.headers['Authorization'] = 'Bearer ' + this.token;
            if (body) {
                if (isForm) { opts.body = body; }
                else { opts.headers['Content-Type'] = 'application/json'; opts.body = JSON.stringify(body); }
            }
            const res = await fetch('/api' + path, opts);
            const json = await res.json().catch(() => ({ success: false, message: 'Respuesta no válida' }));
            return { ok: res.ok, json };
        },
        go(r) { this.route = r; },

        // ===== Auth =====
        async login() {
            this.loading = true;
            const { ok, json } = await this.api('POST', '/login', this.loginForm);
            this.loading = false;
            if (!ok) return this.notify(json.message, 'error');
            this.token = json.data.token;
            localStorage.setItem('mundial_token', this.token);
            this.user = json.data.user;
            this.notify('Bienvenido ' + this.user.name.split(' ')[0]);
            await this.loadAll();
        },
        async register() {
            this.loading = true;
            const { ok, json } = await this.api('POST', '/register', this.registerForm);
            this.loading = false;
            if (!ok) return this.notify(json.message, 'error');
            this.token = json.data.token;
            localStorage.setItem('mundial_token', this.token);
            this.user = json.data.user;
            this.notify(json.message);
            await this.loadAll();
        },
        async fetchMe() {
            const { ok, json } = await this.api('GET', '/me');
            if (ok) {
                this.user = json.data.user;
                this.totalPoints = json.data.total_points;
            } else {
                localStorage.removeItem('mundial_token');
                this.token = null;
            }
        },
        async logout() {
            await this.api('POST', '/logout');
            this.token = null; this.user = null;
            this.predDraft = {}; this.resultDraft = {};
            localStorage.removeItem('mundial_token');
        },

        // ===== Carga global =====
        async loadAll() {
            await Promise.all([
                this.loadMatches(),
                this.loadPredictions(),
                this.loadCommunities(),
                this.loadPhases(),
            ]);
            await this.fetchMe();
        },
        async loadMatches() {
            const { json } = await this.api('GET', '/matches');
            this.matches = json.data || [];
        },
        async loadPredictions() {
            const { json } = await this.api('GET', '/predictions');
            this.predictions = json.data || [];
            // Reset draft para reflejar las predicciones reales
            this.predDraft = {};
        },
        async loadCommunities() {
            const { json } = await this.api('GET', '/communities');
            this.myCommunities = json.data || { owned: [], joined: [] };
        },
        async loadPhases() {
            const { json } = await this.api('GET', '/phases');
            if (json.data) {
                this.phases = json.data;
                this.currentPhase = this.phases.find(p => p.is_current) || null;
            }
        },

        // ===== Predicciones =====
        async savePrediction(match) {
            const draft = this.draft(match.id);
            if (draft.home_score === '' || draft.away_score === '') return this.notify('Introduce ambos goles', 'error');
            const existing = this.predictions.find(p => p.match_id === match.id);
            let res;
            if (existing) {
                res = await this.api('PATCH', '/predictions/' + existing.id, {
                    home_score: Number(draft.home_score),
                    away_score: Number(draft.away_score),
                });
            } else {
                res = await this.api('POST', '/predictions', {
                    match_id: match.id,
                    home_score: Number(draft.home_score),
                    away_score: Number(draft.away_score),
                });
            }
            if (!res.ok) return this.notify(res.json.message, 'error');
            this.notify('Predicción guardada ✓');
            await this.loadPredictions();
        },

        // ===== Comunidades =====
        async createCommunity() {
            const { ok, json } = await this.api('POST', '/communities', this.newCommunity);
            if (!ok) return this.notify(json.message, 'error');
            this.notify('Comunidad creada · Código: ' + json.data.code);
            this.newCommunity = { name: '', description: '' };
            await this.loadCommunities();
        },
        async joinByCode() {
            const code = this.joinCode.trim().toUpperCase();
            if (!code) return;
            const { ok, json } = await this.api('POST', '/communities/' + code + '/join');
            if (!ok) return this.notify(json.message, 'error');
            this.notify(json.message);
            this.joinCode = '';
        },
        async viewRanking(communityId) {
            const { ok, json } = await this.api('GET', '/communities/' + communityId + '/ranking');
            if (!ok) return this.notify(json.message, 'error');
            this.rankingModal = json.data;
        },
        async viewRequests(communityId) {
            const { ok, json } = await this.api('GET', '/communities/' + communityId + '/requests');
            if (!ok) return this.notify(json.message, 'error');
            this.requestsModal = { communityId, list: json.data };
        },
        async acceptRequest(communityId, userId) {
            const { ok, json } = await this.api('POST', '/communities/' + communityId + '/requests/' + userId + '/accept');
            if (!ok) return this.notify(json.message, 'error');
            this.notify('Solicitud aceptada');
            this.requestsModal = null;
            await this.loadCommunities();
        },

        // ===== Admin =====
        async activatePhase(code) {
            const { ok, json } = await this.api('POST', '/admin/phases/' + code + '/activate');
            if (!ok) return this.notify(json.message, 'error');
            this.notify('Fase activada: ' + json.data.name);
            await this.loadPhases();
        },
        async uploadMatches() {
            if (!this.uploadFile) return this.notify('Selecciona un fichero', 'error');
            const fd = new FormData();
            fd.append('file', this.uploadFile);
            const { ok, json } = await this.api('POST', '/admin/matches/import', fd, true);
            if (!ok) return this.notify(json.message, 'error');
            this.notify('Importados: ' + json.data.created + ' nuevos, ' + json.data.updated + ' actualizados');
            this.uploadFile = null;
            await this.loadMatches();
        },
        async saveResult(match) {
            const draft = this.resultDraftRef(match.id);
            if (draft.home_score == null || draft.away_score == null || draft.home_score === '' || draft.away_score === '') return this.notify('Introduce ambos goles', 'error');
            const { ok, json } = await this.api('PATCH', '/admin/matches/' + match.id + '/result', {
                home_score: Number(draft.home_score),
                away_score: Number(draft.away_score),
            });
            if (!ok) return this.notify(json.message, 'error');
            this.notify('Resultado guardado · Puntos recalculados');
            await this.loadAll();
        },
    };
}
</script>
</body>
</html>
