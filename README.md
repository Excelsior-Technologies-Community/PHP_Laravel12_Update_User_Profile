PHP_Laravel12_Update_User_Profile
---
A beginner-friendly Laravel 12 project to update user profile including avatar, phone, city, and password. Users can view and update their information securely with authentication.

Project Overview
---
This project demonstrates:
---
User registration and login using Laravel authentication.

Update user profile including:
---
Name

Email

Avatar upload

Phone number

City

Password change (optional)

Display current profile information in the form.

Show success and error messages on update.

Profile link in navbar for logged-in users.

Step‑by‑Step Installation
---
Step 1 — Create New Laravel Project

Open terminal and run:
```
composer create-project laravel/laravel PHP_Laravel12_Update_User_Profile "12.*"
cd PHP_Laravel12_Update_User_Profile
```
This creates a fresh Laravel 12 project.


Step:2 Setup Database

Open .env file and update your MySQL credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE= laravel12_update_user_profile
DB_USERNAME=root
DB_PASSWORD=
```
Save file. Then run:
```
php artisan migrate
```
This creates users, password_resets, failed_jobs, personal_access_tokens tables.


Step:3 Install Auth (UI Scaffold)

Install UI scaffolding for login/register pages:

```
composer require laravel/ui
php artisan ui bootstrap --auth
npm install
npm run build
```
This generates:

Login, register, dashboard views.

Auth routes in routes/web.php.



Step:4 Add Profile Fields in Users Table

Create migration:
```
php artisan make:migration add_new_fields_to_users_table

```
Edit it:
```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable(); // User avatar image
            $table->string('phone')->nullable();// User phone number
            $table->string('city')->nullable();// User city
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar','phone','city']);
        });
    }
};

```

Now migrate:
```
php artisan migrate
```

Database now has additional fields for user profile.


Step:5 Update User Model

Open app/Models/User.php and change $fillable so these fields can be updated by mass assignment:
```
 protected $fillable = [
    'name',
    'email',
    'password',
    'avatar',
    'phone',
    'city',
];
```

This allows form data saving.

Step:6 Create Routes

Open routes/web.php and add:
```
use App\Http\Controllers\ProfileController;

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('user.profile');
    Route::post('/profile', [ProfileController::class, 'store'])->name('user.profile.store');
});
```

Only authenticated users can access the profile page.



Step:7 Create Controller

Generate controller:
```
php artisan make:controller ProfileController
```

Edit app/Http/Controllers/ProfileController.php:
```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure only logged-in users can access
    }

    // Show profile page
    public function index()
    {
        return view('profile');
    }

    // Update profile
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'confirm_password' => 'required_with:password|same:password',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg',
            'phone' => 'nullable',
            'city' => 'nullable',
        ]);

        $user = auth()->user();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $fileName = time().'.'.$request->avatar->extension();
            $request->avatar->move(public_path('avatars'), $fileName);
            $user->avatar = $fileName;
        }

        // Update other fields
        $user->name  = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->city  = $request->city;

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect('/home')->with('success','Profile updated successfully.');
    }
}
```
Explanation:
 Validate inputs
 Handle avatar upload
 Only change password if entered
 Update current user
 Typical Laravel update pattern.


Step:8 Create Blade View

Create new file resources/views/profile.blade.php:
```

@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center rounded-top-4">
                    <h4 class="mb-0">Update Profile</h4>
                </div>

                <div class="card-body p-4">

                    {{-- Success Message --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('user.profile.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Avatar --}}
                        <div class="text-center mb-4">
                            <img src="{{ auth()->user()->avatar ? asset('avatars/'.auth()->user()->avatar) : asset('images/default.png') }}"
                                 class="rounded-circle shadow"
                                 width="120" height="120">

                            <div class="mt-3">
                                <input type="file" name="avatar" class="form-control">
                            </div>
                        </div>

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">City</label>
                                <input type="text" name="city" value="{{ old('city', auth()->user()->city) }}" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Leave blank if unchanged">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control">
                            </div>

                        </div>

                        <div class="text-center mt-4">
                            <button class="btn btn-primary px-5">
                                <i class="fa fa-save me-1"></i> Save Changes
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
```
This form shows current values and uploads new ones


Step:9 Add Profile Link in Navbar

Edit resources/views/layouts/app.blade.php to include:
```
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Vite -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="bg-light">
<div id="app">

    <!-- NAVBAR START -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">

            <!-- Logo / App Name -->
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="bi bi-person-circle me-1"></i>
                {{ config('app.name', 'Laravel') }}
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <!-- LEFT MENU -->
                <ul class="navbar-nav me-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.profile') }}">
                                <i class="bi bi-person-lines-fill"></i> Profile
                            </a>
                        </li>
                    @endauth
                </ul>

                <!-- RIGHT MENU -->
                <ul class="navbar-nav ms-auto">
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
                            <a class="nav-link dropdown-toggle fw-semibold" href="#" role="button"
                               data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                                {{ Auth::user()->name }}
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.profile') }}">
                                        <i class="bi bi-person"></i> My Profile
                                    </a>
                                </li>

                                <li><hr class="dropdown-divider"></li>

                                <li>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right"></i> Logout
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
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <main class="py-5">
        <div class="container">
            @yield('content')
        </div>
    </main>

</div>
</body>
</html>
```

Step 10 — Edit Welcome & Home Pages

Welcome Page: resources/views/welcome.blade.php
```
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


```

Home Page: resources/views/home.blade.php

```
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                   @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif


                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

Step:11 Run Your Application
```
php artisan serve
```
Open in browser:
```
http://localhost:8000
```
Now you can see this type Output:





Main Page:

<img width="1911" height="958" alt="Screenshot 2025-12-15 114336" src="https://github.com/user-attachments/assets/09ea984b-bb1d-4c93-81ea-5bc9af75a015" />

Register Page:

<img width="1919" height="963" alt="Screenshot 2025-12-15 113122" src="https://github.com/user-attachments/assets/8b05ea30-00cf-44cc-a262-b8f73f4f50f2" />

Login Page:

<img width="1908" height="961" alt="Screenshot 2025-12-15 113531" src="https://github.com/user-attachments/assets/7d30308d-998d-4a25-90c5-0e15278f51e5" />

Profile Page:

<img width="1894" height="961" alt="Screenshot 2025-12-15 113437" src="https://github.com/user-attachments/assets/9a449f3b-eb77-430d-b9ab-42a26aa1ab59" />

after click on save button:

<img width="1918" height="972" alt="Screenshot 2025-12-15 113256" src="https://github.com/user-attachments/assets/f4b78a88-2fcf-46fb-96f2-014d79a0f528" />



Project Folder Structure
```
PHP_Laravel12_Update_User_Profile/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/                 # Default Laravel Auth controllers
│   │   │   └── ProfileController.php # Handles user profile view & update
│   │   └── Middleware/               # Middleware like auth
│   ├── Models/
│   │   └── User.php                  # User model, updated $fillable for profile fields
│   └── Providers/
├── bootstrap/
│   └── app.php
├── config/
│   └── *.php                          # Config files
├── database/
│   ├── migrations/
│   │   ├── 2014_10_12_000000_create_users_table.php
│   │   └── xxxx_xx_xx_xxxxxx_add_new_fields_to_users_table.php # Avatar, phone, city
│   └── seeders/
├── node_modules/
├── public/
│   ├── avatars/                        # Uploaded user avatars
│   ├── images/
│   │   └── default.png                 # Default avatar image
│   └── index.php
├── resources/
│   ├── js/
│   ├── sass/
│   └── views/
│       ├── auth/                        # Login, Register, Forgot Password views
│       ├── layouts/
│       │   └── app.blade.php            # Main layout with navbar
│       ├── home.blade.php               # Dashboard after login
│       ├── profile.blade.php            # Update user profile form
│       └── welcome.blade.php            # Landing page
├── routes/
│   └── web.php                          # Web routes including profile
├── storage/
│   └── logs/
├── tests/
├── vendor/
├── .env                                 # Database & environment settings
├── composer.json
├── package.json
├── vite.config.js
└── artisan


