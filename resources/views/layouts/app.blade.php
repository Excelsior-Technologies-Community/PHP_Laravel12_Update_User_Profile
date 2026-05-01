<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        body.dark-mode { background-color: #121212 !important; color: #ffffff !important; }
        .dark-mode .navbar { background-color: #0d6efd !important; }
        .dark-mode .card { background-color: #1e1e1e; color: white; border: 1px solid #333; }
        .dark-mode .dropdown-menu { background-color: #1e1e1e; border-color: #333; }
        .dark-mode .dropdown-item { color: white; }
        .dark-mode .dropdown-item:hover { background-color: #333; }
        .dark-mode .form-control { background-color: #2b2b2b; color: white; border-color: #444; }
        .dark-mode .list-group-item { background-color: #1e1e1e; color: white; border-color: #333; }
        .nav-avatar { width: 30px; height: 30px; object-fit: cover; border-radius: 50%; border: 1px solid rgba(255,255,255,0.5); }
    </style>
</head>

<body class="bg-light">
<div id="app">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="bi bi-person-circle me-1"></i>
                {{ config('app.name', 'Laravel') }}
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.profile') }}">
                                <i class="bi bi-person-lines-fill"></i> Profile
                            </a>
                        </li>
                    @endauth
                </ul>

                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item me-2">
                        <button id="darkModeToggle" class="btn btn-sm btn-outline-light border-0">
                            <i class="bi bi-moon-stars-fill"></i>
                        </button>
                    </li>

                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-person-plus"></i> Register
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle fw-semibold d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('avatars/'.Auth::user()->avatar) }}" class="nav-avatar me-2">
                                @else
                                    <i class="bi bi-person-circle me-2"></i>
                                @endif
                                {{ Auth::user()->name }}
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.profile') }}">
                                        <i class="bi bi-person me-2"></i> My Profile
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-5">
        <div class="container">
            @yield('content')
        </div>
    </main>
</div>

<script>
    const toggleBtn = document.getElementById('darkModeToggle');
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-mode');
    }

    toggleBtn.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        localStorage.setItem('theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light');
    });

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Done!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2500,
            toast: true,
            position: 'top-end'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "{{ session('error') }}",
            toast: true,
            position: 'top-end',
            timer: 3000
        });
    @endif
</script>
</body>
</html>