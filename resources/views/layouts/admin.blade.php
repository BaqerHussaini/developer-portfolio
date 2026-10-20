<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Admin') &middot; Portfolio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
</head>
<body>
<div class="shell">
    <aside class="sidebar" id="sidebar">
        <a class="brand" href="{{ route('admin.dashboard') }}">
            <span class="brand-mark">{{ \App\Models\Setting::get('logo_initials', 'BH') }}</span>
            <span class="brand-text">Portfolio CMS</span>
        </a>

        <nav class="side-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="ico">&#9632;</span> Dashboard
            </a>
            <a href="{{ route('admin.settings.edit') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <span class="ico">&#9881;</span> Site content
            </a>
            <a href="{{ route('admin.projects.index') }}" class="{{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                <span class="ico">&#9635;</span> Projects
            </a>
            <a href="{{ route('admin.experiences.index') }}" class="{{ request()->routeIs('admin.experiences.*') ? 'active' : '' }}">
                <span class="ico">&#128188;</span> Experience
            </a>
            <a href="{{ route('admin.skills.index') }}" class="{{ request()->routeIs('admin.skills.*') ? 'active' : '' }}">
                <span class="ico">&#10003;</span> Skills
            </a>
            <a href="{{ route('admin.stats.index') }}" class="{{ request()->routeIs('admin.stats.*') ? 'active' : '' }}">
                <span class="ico">&#128200;</span> Hero stats
            </a>
            <a href="{{ route('admin.messages.index') }}" class="{{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                <span class="ico">&#9993;</span> Messages
                @if ($unreadCount ?? \App\Models\Message::inbox()->unread()->count())
                    <span class="pill">{{ \App\Models\Message::inbox()->unread()->count() }}</span>
                @endif
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

    <div class="main">
        <header class="topbar">
            <button class="menu-btn" id="menuBtn" type="button" aria-label="Toggle navigation">&#9776;</button>
            <h1>@yield('title', 'Dashboard')</h1>
            <span class="who">{{ auth()->user()?->email }}</span>
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
