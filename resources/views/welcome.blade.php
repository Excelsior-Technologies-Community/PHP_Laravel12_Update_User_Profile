<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow">
                <div class="card-body text-center">

                    <h1 class="mb-3">Welcome to Laravel</h1>
                    <p class="text-muted">Laravel User Profile Update Project</p>

                    <hr>

                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('user.profile') }}" class="btn btn-primary">
                                Go to Profile
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-success me-2">
                                Login
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-outline-primary">
                                    Register
                                </a>
                            @endif
                        @endauth
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
