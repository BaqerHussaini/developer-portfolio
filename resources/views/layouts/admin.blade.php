<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="admin-heartbeat" content="{{ route('admin.heartbeat') }}">
    <title>@yield('title', 'Admin') &middot; Portfolio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @include('partials.theme-init', ['themeKey' => 'portfolio-admin-theme'])
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    @include('partials.favicon', ['logoInitials' => \App\Models\Setting::get('logo_initials', 'BH')])
</head>
<body>
<div class="shell">
    <aside class="sidebar" id="sidebar">
        <button class="sidebar-close" id="sidebarClose" type="button" aria-label="Close navigation">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.4 5 5 6.4 10.6 12 5 17.6 6.4 19 12 13.4 17.6 19 19 17.6 13.4 12 19 6.4 17.6 5 12 10.6 6.4 5Z"/></svg>
        </button>

        <a class="brand" href="{{ route('admin.dashboard') }}">
            <span class="brand-mark">{{ \App\Models\Setting::get('logo_initials', 'BH') }}</span>
            <span class="brand-text">Portfolio CMS</span>
        </a>

        <nav class="side-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="ico"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 3h8v8H3V3Zm10 0h8v8h-8V3ZM3 13h8v8H3v-8Zm10 0h8v8h-8v-8Z"/></svg></span> Dashboard
            </a>
            <a href="{{ route('admin.settings.edit') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <span class="ico"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h9v2H4V6Zm11 0h5v2h-5V6ZM4 11h4v2H4v-2Zm6 0h10v2H10v-2ZM4 16h12v2H4v-2Zm14 0h2v2h-2v-2Z"/></svg></span> Site content
            </a>
            <a href="{{ route('admin.projects.index') }}" class="{{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                <span class="ico"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 5a1 1 0 0 1 1-1h5l2 2h8a1 1 0 0 1 1 1v11a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V5Z"/></svg></span> Projects
            </a>
            <a href="{{ route('admin.experiences.index') }}" class="{{ request()->routeIs('admin.experiences.*') ? 'active' : '' }}">
                <span class="ico"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 4h6a2 2 0 0 1 2 2v2h3a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1h3V6a2 2 0 0 1 2-2Zm0 4h6V6H9v2Z"/></svg></span> Experience
            </a>
            <a href="{{ route('admin.skills.index') }}" class="{{ request()->routeIs('admin.skills.*') ? 'active' : '' }}">
                <span class="ico"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20Zm-1.2 14.2-4-4 1.4-1.4 2.6 2.6 5.6-5.6 1.4 1.2-7 7.2Z"/></svg></span> Skills
            </a>
            <a href="{{ route('admin.stats.index') }}" class="{{ request()->routeIs('admin.stats.*') ? 'active' : '' }}">
                <span class="ico"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 20V10h4v10H4Zm6 0V4h4v16h-4Zm6 0v-7h4v7h-4Z"/></svg></span> Hero stats
            </a>
            <a href="{{ route('admin.messages.index') }}" class="{{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                <span class="ico"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Zm8 7.2 8-5V6l-8 5-8-5v1.2l8 5Z"/></svg></span> Messages
                @if ($unreadCount ?? \App\Models\Message::inbox()->unread()->count())
                    <span class="pill">{{ \App\Models\Message::inbox()->unread()->count() }}</span>
                @endif
            </a>
            <a href="{{ route('admin.account.edit') }}" class="{{ request()->routeIs('admin.account.*') ? 'active' : '' }}">
                <span class="ico"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10Zm0 2c-4.4 0-8 2.2-8 5v1h16v-1c0-2.8-3.6-5-8-5Z"/></svg></span> Account
            </a>
        </nav>

        <div class="side-foot">
            <a class="side-link" href="{{ route('home') }}" target="_blank">View site &nearr;</a>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="side-link danger">Sign out</button>
            </form>
        </div>
    </aside>

    <div class="sidebar-scrim" id="sidebarScrim" hidden></div>

    <div class="main">
        <header class="topbar">
            <button class="menu-btn" id="menuBtn" type="button" aria-label="Toggle navigation"
                    aria-expanded="false" aria-controls="sidebar">&#9776;</button>
            <h1>@yield('title', 'Dashboard')</h1>
            <div class="topbar-actions">
                <button class="theme-toggle" id="adminThemeToggle" type="button" aria-label="Switch colour theme">
                    <svg class="icon-sun" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 17a5 5 0 1 0 0-10 5 5 0 0 0 0 10Zm0-13a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0V5a1 1 0 0 1 1-1Zm0 14a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0v-1a1 1 0 0 1 1-1ZM3 12a1 1 0 0 1 1-1h1a1 1 0 1 1 0 2H4a1 1 0 0 1-1-1Zm16 0a1 1 0 0 1 1-1h1a1 1 0 1 1 0 2h-1a1 1 0 0 1-1-1ZM5.6 5.6a1 1 0 0 1 1.4 0l.7.7a1 1 0 0 1-1.4 1.4l-.7-.7a1 1 0 0 1 0-1.4Zm10.7 10.7a1 1 0 0 1 1.4 0l.7.7a1 1 0 0 1-1.4 1.4l-.7-.7a1 1 0 0 1 0-1.4Zm2.1-10.7a1 1 0 0 1 0 1.4l-.7.7a1 1 0 1 1-1.4-1.4l.7-.7a1 1 0 0 1 1.4 0ZM7.7 16.3a1 1 0 0 1 0 1.4l-.7.7a1 1 0 0 1-1.4-1.4l.7-.7a1 1 0 1 1 1.4 0Z"/></svg>
                    <svg class="icon-moon" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 13.2A8.5 8.5 0 1 1 10.8 3a7 7 0 0 0 10.2 10.2Z"/></svg>
                </button>
            </div>
        </header>

        <div class="content">
            @include('admin.partials.flash')

            @yield('content')
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/admin.js') }}"></script>
</body>
</html>
