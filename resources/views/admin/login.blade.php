<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Sign in &middot; Portfolio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
</head>
<body class="login-page">
    <div class="login-card">
        <span class="brand-mark">{{ \App\Models\Setting::get('logo_initials', 'BH') }}</span>

        <h1>Portfolio admin</h1>
        <p class="muted">Sign in to edit your site content.</p>

        @if (session('status'))
            <p class="flash">{{ session('status') }}</p>
        @endif

        @if ($errors->any())
            <p class="flash error">{{ $errors->first() }}</p>
        @endif

        <form method="POST" action="{{ route('admin.login.attempt') }}">
            @csrf

            <label class="field">
                <span>Email</span>
                <input type="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
            </label>

            <label class="field">
                <span>Password</span>
                <input type="password" name="password" autocomplete="current-password" required>
            </label>

            <label class="field check">
                <input type="checkbox" name="remember" value="1">
                <span>Keep me signed in</span>
            </label>

            <button type="submit" class="btn btn-primary">Sign in</button>
        </form>

        <p class="login-foot"><a href="{{ route('home') }}">&larr; Back to the website</a></p>
    </div>
</body>
</html>
