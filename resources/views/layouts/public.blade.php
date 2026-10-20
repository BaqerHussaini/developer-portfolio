<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $seoDescription }}">
    <title>@yield('pageTitle', $siteTitle)</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/site.css') }}">
    @include('site.partials.icons', ['logoInitials' => $logoInitials])
</head>
<body>
    <a class="skip-link" href="#main">Skip to content</a>

    <div class="backdrop" aria-hidden="true">
        <span class="orb orb-a"></span>
        <span class="orb orb-b"></span>
        <span class="orb orb-c"></span>
        <span class="grid-lines"></span>
    </div>

    <header class="site-header" id="top">
        <div class="container nav-wrap">
            <a href="{{ route('home') }}" class="logo" aria-label="{{ $siteTitle }} home">
                <span class="logo-mark">{!! $logoInitials !!}</span>
            </a>

            <nav class="main-nav" id="mainNav" aria-label="Main navigation">
                @foreach ([
                    ['route' => 'home', 'label' => 'Home'],
                    ['route' => 'projects', 'label' => 'Projects'],
                    ['route' => 'experience', 'label' => 'Experience'],
                    ['route' => 'about', 'label' => 'About'],
                    ['route' => 'contact', 'label' => 'Contact'],
                ] as $item)
                    <a href="{{ route($item['route']) }}"
                       class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}"
                       @if (request()->routeIs($item['route'])) aria-current="page" @endif><span class="nav-text">{{ $item['label'] }}</span></a>
                @endforeach
                <a href="{{ route('admin.login') }}" class="nav-admin" title="Admin">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 1 3 5v6c0 5.5 3.8 10.7 9 12 5.2-1.3 9-6.5 9-12V5l-9-4Zm0 10.99h7c-.5 4-2.5 7.5-7 8.9V12H5V6.3l7-3.1v8.79Z"/></svg>
                </a>
            </nav>

            <div class="nav-actions">
                <button class="theme-toggle" id="themeToggle" type="button" aria-label="Switch colour theme">
                    <svg class="icon-sun" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 17a5 5 0 1 0 0-10 5 5 0 0 0 0 10Zm0-13a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0V5a1 1 0 0 1 1-1Zm0 14a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0v-1a1 1 0 0 1 1-1ZM3 12a1 1 0 0 1 1-1h1a1 1 0 1 1 0 2H4a1 1 0 0 1-1-1Zm16 0a1 1 0 0 1 1-1h1a1 1 0 1 1 0 2h-1a1 1 0 0 1-1-1ZM5.6 5.6a1 1 0 0 1 1.4 0l.7.7a1 1 0 0 1-1.4 1.4l-.7-.7a1 1 0 0 1 0-1.4Zm10.7 10.7a1 1 0 0 1 1.4 0l.7.7a1 1 0 0 1-1.4 1.4l-.7-.7a1 1 0 0 1 0-1.4Zm2.1-10.7a1 1 0 0 1 0 1.4l-.7.7a1 1 0 1 1-1.4-1.4l.7-.7a1 1 0 0 1 1.4 0ZM7.7 16.3a1 1 0 0 1 0 1.4l-.7.7a1 1 0 0 1-1.4-1.4l.7-.7a1 1 0 1 1 1.4 0Z"/></svg>
                    <svg class="icon-moon" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 13.2A8.5 8.5 0 1 1 10.8 3a7 7 0 0 0 10.2 10.2Z"/></svg>
                </button>
                <button class="nav-toggle" id="navToggle" type="button" aria-label="Toggle menu" aria-expanded="false" aria-controls="mainNav">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </header>

    <main id="main">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container footer-wrap">
            <p>&copy; {{ date('Y') }} {{ $siteTitle }} &middot; {{ $footerNote }}</p>
            <div class="footer-right">
                @foreach ($contact['socials'] as $social)
                    <a href="{{ $social['url'] }}" target="_blank" rel="noreferrer">{{ $social['platform'] }}</a>
                @endforeach
                <a href="#top" class="to-top">Back to top <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 8 4 16h16l-8-8Z"/></svg></a>
            </div>
        </div>
    </footer>

    <script src="{{ asset('assets/js/site.js') }}"></script>
</body>
</html>
