@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0 rounded-4">
                <div class="card-header bg-white fw-bold py-3">{{ __('Dashboard') }}</div>

                <div class="card-body text-center p-5">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="mb-4">
                        <img src="{{ auth()->user()->avatar ? asset('avatars/'.auth()->user()->avatar) : 'https://cdn-icons-png.flaticon.com/512/149/149071.png' }}" 
                             class="rounded-circle shadow" 
                             style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #0d6efd;">
                    </div>

                    <h3 class="fw-bold">Welcome, {{ auth()->user()->name }}!</h3>
                    <p class="text-muted mb-4">{{ auth()->user()->email }}</p>

                    <hr>

                    <div class="mt-4">
                        <p class="text-success fw-bold">
                            <i class="bi bi-patch-check-fill"></i> {{ __('You are logged in!') }}
                        </p>
                        
                        <div class="d-flex justify-content-center gap-2 mt-3">
                            <a href="{{ route('user.profile') }}" class="btn btn-primary px-4">
                                <i class="bi bi-person-gear"></i> Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection