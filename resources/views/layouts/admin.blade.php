<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .admin-nav { background: #333; color: white; padding: 10px; margin-bottom: 20px; }
        .admin-nav a { color: white; text-decoration: none; margin-right: 15px; }
        .admin-nav a:hover { text-decoration: underline; }
        .alert { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <nav class="admin-nav">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.roles.index') }}">Rollen</a>
        <a href="{{ route('admin.users.index') }}">Gebruikers</a>
        <a href="{{ route('dashboard') }}">Terug naar hoofddashboard</a>
    </nav>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    @yield('content')
</body>
</html>
