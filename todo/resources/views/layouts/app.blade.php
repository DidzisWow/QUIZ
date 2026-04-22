<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quiz')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    @yield('styles')
</head>
<body>

    <nav>
        <a href="{{ route('quiz.index') }}" class="nav-brand">Quiz<span>Forge</span></a>

        <div class="nav-links">
            @auth
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.index') }}" class="btn btn-ghost nav-admin"> Admin</a>
                @endif
                <a href="{{ route('history') }}" class="btn btn-ghost"> History</a>
                <span class="nav-user">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" style="display:inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            @endauth

            @guest
                <a href="{{ route('login') }}"    class="btn btn-ghost">Log in</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Sign up</a>
            @endguest
        </div>
    </nav>

    <main>
        @if(session('success'))
            <div class="flash flash-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="flash flash-error">{{ $errors->first() }}</div>
        @endif

        @yield('content')
    </main>

</body>
</html>