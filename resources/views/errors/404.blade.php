<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Page Not Found | {{ config('app.name', 'Track Citations') }}</title>
    <link rel="icon" href="{{ asset('images/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('fonts/inter/inter.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/tabler-icons.min.css') }}">
    <style>
        :root { color-scheme: light; font-family: Inter, ui-sans-serif, system-ui, sans-serif; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; color: #172033; background: #f8fafc; }
        .error-shell { min-height: 100vh; display: grid; place-items: center; padding: 32px 20px; position: relative; overflow: hidden; }
        .error-shell::before { content: ''; position: absolute; inset: 0; background: radial-gradient(circle at 12% 12%, rgba(99,102,241,.15), transparent 34%), radial-gradient(circle at 88% 88%, rgba(124,58,237,.13), transparent 35%); }
        .error-card { width: min(720px, 100%); position: relative; padding: 52px 44px; text-align: center; background: rgba(255,255,255,.94); border: 1px solid #e2e8f0; border-radius: 24px; box-shadow: 0 30px 80px -35px rgba(30,41,59,.42); }
        .brand { display: inline-flex; align-items: center; justify-content: center; margin-bottom: 28px; }
        .brand img { width: auto; height: 64px; object-fit: contain; }
        .code { margin: 0; font-size: clamp(78px, 17vw, 138px); line-height: .85; font-weight: 900; letter-spacing: -.08em; background: linear-gradient(135deg,#4f46e5,#7c3aed); -webkit-background-clip: text; background-clip: text; color: transparent; }
        h1 { margin: 24px 0 12px; font-size: clamp(26px, 5vw, 38px); letter-spacing: -.035em; }
        p { max-width: 520px; margin: 0 auto; color: #64748b; font-size: 16px; line-height: 1.7; }
        .actions { display: flex; justify-content: center; gap: 12px; margin-top: 30px; flex-wrap: wrap; }
        .button { min-height: 48px; display: inline-flex; align-items: center; justify-content: center; gap: 9px; padding: 12px 22px; border-radius: 12px; text-decoration: none; font-size: 14px; font-weight: 700; transition: transform .18s ease, box-shadow .18s ease; }
        .button:hover { transform: translateY(-2px); }
        .button-primary { color: #fff; background: linear-gradient(135deg,#4f46e5,#7c3aed); box-shadow: 0 12px 24px -10px rgba(79,70,229,.7); }
        .button-secondary { color: #334155; background: #fff; border: 1px solid #dbe3ee; }
        .hint { margin-top: 26px; color: #94a3b8; font-size: 12px; }
        @media (max-width: 560px) { .error-card { padding: 38px 22px; border-radius: 18px; } .brand img { height: 52px; } .actions { flex-direction: column; } .button { width: 100%; } }
    </style>
</head>
<body>
@php
    $user = auth()->user();
    $dashboardRoute = $user ? $user->portalRoutePrefix().'.dashboard' : null;
    $primaryUrl = $user && Route::has($dashboardRoute) ? route($dashboardRoute) : url('/');
    $primaryLabel = $user ? 'Back to Dashboard' : 'Back to Home';
@endphp
<main class="error-shell">
    <section class="error-card" aria-labelledby="error-title">
        <a class="brand" href="{{ $primaryUrl }}" aria-label="{{ config('app.name') }}">
            <img src="{{ asset('images/logo-dark.png') }}" alt="{{ config('app.name') }}">
        </a>
        <div class="code" aria-hidden="true">404</div>
        <h1 id="error-title">Page not found</h1>
        <p>The page you requested may have been moved, deleted, or the address may be incorrect.</p>
        <div class="actions">
            <a class="button button-primary" href="{{ $primaryUrl }}">
                <i class="ti {{ $user ? 'ti-dashboard' : 'ti-home' }}" aria-hidden="true"></i>
                {{ $primaryLabel }}
            </a>
            <a class="button button-secondary" href="javascript:history.back()">
                <i class="ti ti-arrow-left" aria-hidden="true"></i>
                Go Back
            </a>
        </div>
        <div class="hint">Error 404 &middot; The requested URL could not be found</div>
    </section>
</main>
</body>
</html>