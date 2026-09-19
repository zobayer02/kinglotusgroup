<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Page Not Found') | King Lotus International</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.7);
            --border: rgba(255, 255, 255, 0.1);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --accent: #38bdf8;
            --accent-glow: rgba(56, 189, 248, 0.15);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at 50% 20%, #1e293b 0%, #0f172a 100%);
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            color: var(--text-main);
            padding: 24px;
        }
        .error-card {
            max-width: 520px;
            width: 100%;
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 28px;
            padding: 48px 36px;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        .error-code {
            font-family: 'Fraunces', serif;
            font-size: 5.5rem;
            font-weight: 700;
            line-height: 1;
            letter-spacing: -0.04em;
            background: linear-gradient(135deg, #38bdf8 0%, #818cf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 12px;
        }
        .error-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 12px;
            letter-spacing: -0.01em;
        }
        .error-desc {
            font-size: 0.95rem;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 32px;
        }
        .error-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 28px;
            border-radius: 999px;
            background: linear-gradient(135deg, #0284c7 0%, #2563eb 100%);
            color: #ffffff;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.92rem;
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.35);
            transition: all 0.2s ease;
        }
        .error-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(37, 99, 235, 0.45);
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-code">@yield('code', '404')</div>
        <h1 class="error-title">@yield('heading', 'Page Not Found')</h1>
        <p class="error-desc">@yield('message', 'The page you are looking for does not exist or has been moved.')</p>
        <a href="{{ url('/') }}" class="error-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
            <span>Return to Homepage</span>
        </a>
    </div>
</body>
</html>
