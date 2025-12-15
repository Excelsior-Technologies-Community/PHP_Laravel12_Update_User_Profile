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
