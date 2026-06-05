@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            {{-- Profile Completion Card --}}
            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="position-relative">
                                <div class="progress-ring" style="width: 80px; height: 80px;">
                                    <svg width="80" height="80">
                                        <circle cx="40" cy="40" r="35" fill="none" stroke="#e9ecef" stroke-width="5"/>
                                        <circle cx="40" cy="40" r="35" fill="none" stroke="#0d6efd" 
                                                stroke-width="5" stroke-dasharray="219.9" 
                                                stroke-dashoffset="{{ 219.9 - (219.9 * $profileCompletion / 100) }}"
                                                transform="rotate(-90 40 40)"/>
                                    </svg>
                                    <div class="position-absolute top-50 start-50 translate-middle">
                                        <span class="fw-bold fs-5">{{ $profileCompletion }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">Profile Completion</h5>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" 
                                     style="width: {{ $profileCompletion }}%" 
                                     aria-valuenow="{{ $profileCompletion }}" 
                                     aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <small class="text-muted">
                                @if($profileCompletion < 100)
                                    Complete your profile for better experience
                                @else
                                    🎉 Perfect! Your profile is complete
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Profile Card --}}
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center rounded-top-4">
                    <h4 class="mb-0">
                        <i class="bi bi-person-circle me-2"></i>Update Profile
                    </h4>
                </div>

                <div class="card-body p-4">

                    {{-- Success Message --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Error Message --}}
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li><i class="bi bi-x-circle-fill me-2"></i>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Email Verification Reminder --}}
                    @if(auth()->user()->email_verified_at === null)
                        <div class="alert alert-warning mb-4">
                            <i class="bi bi-envelope-exclamation-fill me-2"></i>
                            Your email address is not verified. 
                            <a href="{{ route('verification.notice') }}" class="alert-link">Click here to verify</a>
                        </div>
                    @endif

                    <form action="{{ route('user.profile.store') }}" method="POST" enctype="multipart/form-data" id="profileForm">
                        @csrf

                        {{-- Avatar Section --}}
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                <img src="{{ auth()->user()->avatar ? asset('avatars/'.auth()->user()->avatar) : asset('images/default.png') }}"
                                     class="rounded-circle shadow"
                                     width="130" height="130"
                                     id="avatarPreview"
                                     style="object-fit: cover;">
                                
                                @if(auth()->user()->avatar)
                                    <button type="button" class="btn btn-danger btn-sm position-absolute bottom-0 end-0 rounded-circle" 
                                            id="deleteAvatarBtn" style="width: 32px; height: 32px;">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                @endif
                            </div>

                            <div class="mt-3">
                                <label class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-cloud-upload"></i> Change Avatar
                                    <input type="file" name="avatar" class="d-none" id="avatarInput" accept="image/*">
                                </label>
                                <small class="text-muted d-block mt-1">Max size: 2MB (JPG, PNG, GIF)</small>
                            </div>
                        </div>

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person-fill me-1"></i>Full Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" 
                                       class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-envelope-fill me-1"></i>Email Address <span class="text-danger">*</span>
                                </label>
                                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" 
                                       class="form-control @error('email') is-invalid @enderror" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-telephone-fill me-1"></i>Phone Number
                                </label>
                                <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}" 
                                       class="form-control" placeholder="+1234567890">
                                <small class="text-muted">Enter with country code</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-geo-alt-fill me-1"></i>City
                                </label>
                                <input type="text" name="city" value="{{ old('city', auth()->user()->city) }}" 
                                       class="form-control" placeholder="Your city">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-lock-fill me-1"></i>New Password
                                </label>
                                <input type="password" name="password" class="form-control" 
                                       placeholder="Leave blank if unchanged" id="passwordInput">
                                <div class="form-check mt-2">
                                    <input type="checkbox" class="form-check-input" id="showPassword">
                                    <label class="form-check-label text-muted small">Show password</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-lock-fill me-1"></i>Confirm Password
                                </label>
                                <input type="password" name="confirm_password" class="form-control" 
                                       placeholder="Confirm new password">
                            </div>

                        </div>

                        <div class="text-center mt-4">
                            <button class="btn btn-primary px-5 py-2" id="saveBtn">
                                <i class="bi bi-save2 me-2"></i>Save Changes
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4 py-2 ms-2">
                                <i class="bi bi-house me-1"></i>Cancel
                            </a>
                        </div>

                    </form>
                </div>
            </div>

            {{-- Account Info Card --}}
            <div class="card shadow-lg border-0 rounded-4 mt-4">
                <div class="card-body p-3 bg-light">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <small class="text-muted">Member Since</small>
                            <p class="fw-semibold mb-0">{{ auth()->user()->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Last Updated</small>
                            <p class="fw-semibold mb-0">{{ auth()->user()->updated_at->diffForHumans() }}</p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Email Status</small>
                            <p class="fw-semibold mb-0">
                                @if(auth()->user()->email_verified_at)
                                    <span class="text-success"><i class="bi bi-check-circle-fill"></i> Verified</span>
                                @else
                                    <span class="text-warning"><i class="bi bi-clock-history"></i> Pending</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Avatar preview
    const avatarInput = document.getElementById('avatarInput');
    const avatarPreview = document.getElementById('avatarPreview');
    
    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarPreview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Delete avatar AJAX
    const deleteBtn = document.getElementById('deleteAvatarBtn');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete your profile picture?')) {
                fetch('{{ route("user.avatar.delete") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        avatarPreview.src = '{{ asset("images/default.png") }}';
                        deleteBtn.remove();
                        // Show success message
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success alert-dismissible fade show';
                        alertDiv.innerHTML = data.message + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
                        document.querySelector('.card-body').insertBefore(alertDiv, document.querySelector('form'));
                        setTimeout(() => alertDiv.remove(), 3000);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    }
    
    // Show/hide password
    const showPasswordCheckbox = document.getElementById('showPassword');
    const passwordInput = document.getElementById('passwordInput');
    const confirmPasswordInput = document.querySelector('input[name="confirm_password"]');
    
    if (showPasswordCheckbox) {
        showPasswordCheckbox.addEventListener('change', function() {
            const type = this.checked ? 'text' : 'password';
            if (passwordInput) passwordInput.type = type;
            if (confirmPasswordInput) confirmPasswordInput.type = type;
        });
    }
    
    // Form submit loading state
    const form = document.getElementById('profileForm');
    const saveBtn = document.getElementById('saveBtn');
    
    if (form) {
        form.addEventListener('submit', function() {
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Saving...';
        });
    }
    
});
</script>
@endpush

@push('styles')
<style>
    .progress-ring circle:last-child {
        transition: stroke-dashoffset 0.5s ease-in-out;
    }
    #avatarPreview {
        transition: transform 0.3s ease;
    }
    #avatarPreview:hover {
        transform: scale(1.05);
    }
</style>
@endpush

@endsection