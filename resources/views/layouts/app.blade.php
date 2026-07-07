<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php($faviconVersion = file_exists(public_path('favicon.png')) ? filemtime(public_path('favicon.png')) : time())
    <title>@yield('title', 'King Lotus International')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v={{ $faviconVersion }}">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}?v={{ $faviconVersion }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}?v={{ $faviconVersion }}">
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}?v={{ $faviconVersion }}">
    <meta name="theme-color" content="#0c505d">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="KLI">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Playfair+Display:wght@400;500;600;700&display=swap');

        :root {
            color-scheme: light;
            --page-bg: #dbe7ee;
            --text-primary: #10212c;
            --text-soft: rgba(16, 33, 44, 0.74);
            --glass-bg: rgba(240, 246, 250, 0.48);
            --glass-border: rgba(255, 255, 255, 0.38);
            --button-bg: #1f1f1f;
            --button-hover: #111111;
            --shadow-soft: 0 24px 80px rgba(27, 63, 89, 0.18);
            --font-primary: "Fraunces", "Times New Roman", serif;
            --font-secondary: "Playfair Display", "Times New Roman", serif;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
        }

        @supports (overflow: clip) {
            html,
            body {
                overflow-x: clip;
            }
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: var(--font-secondary);
            background: var(--page-bg);
            color: var(--text-primary);
        }

        body.site-loading {
            overflow: hidden;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .site-loader {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: grid;
            place-items: center;
            padding: 24px;
            background:
                radial-gradient(circle at top left, rgba(255, 255, 255, 0.92), transparent 34%),
                radial-gradient(circle at top right, rgba(248, 251, 255, 0.9), transparent 30%),
                linear-gradient(180deg, #f8fbff 0%, #f3f7fb 34%, #edf2f7 100%);
            opacity: 1;
            visibility: visible;
            transition: opacity 0.45s ease, visibility 0.45s ease;
        }

        .site-loader.is-hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .site-loader__shell {
            display: grid;
            justify-items: center;
            width: min(56vw, 160px);
        }

        .site-loader__animation {
            width: min(56vw, 118px);
            aspect-ratio: 1;
            filter: drop-shadow(0 18px 38px rgba(47, 111, 219, 0.15));
        }

        @media (prefers-reduced-motion: reduce) {
            .site-loader {
                transition: none;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="site-loading">
    <div class="site-loader" id="site-loader" aria-hidden="true">
        <div class="site-loader__shell">
            <div class="site-loader__animation" id="site-loader-animation"></div>
        </div>
    </div>

    @yield('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.12.2/lottie.min.js" defer></script>
    <script>
        (() => {
            const startedAt = Date.now();

            const hideLoader = () => {
                const loader = document.getElementById('site-loader');

                if (!loader || loader.classList.contains('is-hidden')) {
                    document.body.classList.remove('site-loading');
                    return;
                }

                const elapsed = Date.now() - startedAt;
                const remaining = Math.max(0, 140 - elapsed);

                window.setTimeout(() => {
                    loader.classList.add('is-hidden');
                    document.body.classList.remove('site-loading');
                }, remaining);
            };

            document.addEventListener('DOMContentLoaded', () => {
                const container = document.getElementById('site-loader-animation');

                if (container && window.lottie) {
                    const loaderAnimation = window.lottie.loadAnimation({
                        container,
                        renderer: 'svg',
                        loop: true,
                        autoplay: true,
                        path: "{{ asset('animations/loading-animation.json') }}",
                    });

                    loaderAnimation.setSpeed(1.9);
                }
            });

            window.addEventListener('load', hideLoader);
            window.setTimeout(hideLoader, 700);
        })();
    </script>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register("{{ asset('sw.js') }}");
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
