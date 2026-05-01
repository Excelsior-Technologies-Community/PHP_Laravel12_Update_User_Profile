@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0">👤 Update Profile</h4>
                </div>

                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    @php
                        $user = auth()->user();
                        $fields = [
                            $user->name, $user->email, $user->phone, $user->city, 
                            $user->avatar, $user->username, $user->bio, 
                            $user->github, $user->linkedin
                        ];
                        $filled = collect($fields)->filter(fn($f) => !empty($f))->count();
                        $percent = ($filled / count($fields)) * 100;

                        if ($percent < 40) $color = 'bg-danger';
                        elseif ($percent < 80) $color = 'bg-warning';
                        else $color = 'bg-success';
                    @endphp

                    <div class="mb-4">
                        <div class="d-flex justify-content-between">
                            <strong>Profile Completion</strong>
                            <span>{{ round($percent) }}%</span>
                        </div>
                        <div class="progress" style="height:12px;">
                            <div class="progress-bar {{ $color }}" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 text-center border-end">
                            <img id="previewImage"
                                 src="{{ $user->avatar ? asset('avatars/'.$user->avatar) : 'https://cdn-icons-png.flaticon.com/512/149/149071.png' }}"
                                 class="rounded-circle shadow mb-3"
                                 width="130" height="130" style="object-fit: cover;">

                            <h5 id="previewName" class="fw-bold">{{ $user->name }}</h5>
                            <p class="text-muted mb-1" id="previewEmail">{{ $user->email }}</p>
                            <p class="small text-primary">@<span id="previewUsername">{{ $user->username ?? 'username' }}</span></p>

                            <input type="file" name="avatar" form="profileForm" class="form-control mb-3">

                            @if($user->avatar)
                                <form action="{{ route('user.avatar.delete') }}" method="POST">
                                    @csrf
                                    <button class="btn btn-outline-danger btn-sm">
                                        Remove Avatar
                                    </button>
                                </form>
                            @endif
                        </div>

                        <div class="col-md-8">
                            <form id="profileForm" action="{{ route('user.profile.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="fw-bold small">Full Name</label>
                                        <input type="text" name="name" value="{{ old('name',$user->name) }}" class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="fw-bold small">Username</label>
                                        <div class="input-group">
                                            <span class="input-group-text">@</span>
                                            <input type="text" name="username" value="{{ old('username',$user->username) }}" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="fw-bold small">Email Address</label>
                                        <input type="email" name="email" value="{{ old('email',$user->email) }}" class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="fw-bold small">Phone Number</label>
                                        <input type="text" name="phone" value="{{ old('phone',$user->phone) }}" class="form-control">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="fw-bold small">Bio</label>
                                        <textarea name="bio" class="form-control" rows="3" placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="fw-bold small">City</label>
                                        <input type="text" name="city" value="{{ old('city',$user->city) }}" class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="fw-bold small"><i class="bi bi-github"></i> GitHub URL</label>
                                        <input type="text" name="github" value="{{ old('github',$user->github) }}" class="form-control" placeholder="https://github.com/username">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="fw-bold small"><i class="bi bi-linkedin"></i> LinkedIn URL</label>
                                        <input type="text" name="linkedin" value="{{ old('linkedin',$user->linkedin) }}" class="form-control" placeholder="https://linkedin.com/in/username">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="fw-bold small">New Password</label>
                                        <input type="password" name="password" class="form-control" placeholder="Leave blank if unchanged">
                                    </div>

                                    <div class="col-md-6 offset-md-6">
                                        <label class="fw-bold small">Confirm Password</label>
                                        <input type="password" name="confirm_password" class="form-control">
                                    </div>
                                </div>

                                <div class="text-end mt-4">
                                    <button class="btn btn-primary px-5 py-2 fw-bold">
                                        💾 Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelector('input[name="name"]').addEventListener('input', function() {
    document.getElementById('previewName').innerText = this.value;
});

document.querySelector('input[name="username"]').addEventListener('input', function() {
    document.getElementById('previewUsername').innerText = this.value;
});

document.querySelector('input[name="email"]').addEventListener('input', function() {
    document.getElementById('previewEmail').innerText = this.value;
});

document.querySelector('input[name="avatar"]').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        document.getElementById('previewImage').src = URL.createObjectURL(file);
    }
});
</script>
@endsection