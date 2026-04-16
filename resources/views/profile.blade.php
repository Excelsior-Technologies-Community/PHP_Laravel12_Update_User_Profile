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

                    {{-- SUCCESS --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- ERRORS --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    @php
                        $user = auth()->user();
                        $fields = [$user->name,$user->email,$user->phone,$user->city,$user->avatar];
                        $filled = collect($fields)->filter(fn($f)=>!empty($f))->count();
                        $percent = ($filled / count($fields)) * 100;

                        if ($percent < 40) $color = 'bg-danger';
                        elseif ($percent < 80) $color = 'bg-warning';
                        else $color = 'bg-success';
                    @endphp

                    {{--  PROFILE COMPLETION --}}
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

                        {{-- LEFT SIDE (PROFILE + PREVIEW) --}}
                        <div class="col-md-4 text-center border-end">

                            {{-- AVATAR --}}
                            <img id="previewImage"
                                 src="{{ $user->avatar ? asset('avatars/'.$user->avatar) : asset('images/default.png') }}"
                                 class="rounded-circle shadow mb-3"
                                 width="130" height="130">

                            <h5 id="previewName">{{ $user->name }}</h5>
                            <p class="text-muted" id="previewEmail">{{ $user->email }}</p>

                            <input type="file" name="avatar" form="profileForm" class="form-control mb-3">

                            {{-- DELETE --}}
                            @if($user->avatar)
                                <form action="{{ route('user.avatar.delete') }}" method="POST">
                                    @csrf
                                    <button class="btn btn-outline-danger btn-sm">
                                        Remove Avatar
                                    </button>
                                </form>
                            @endif

                        </div>

                        {{-- RIGHT SIDE (FORM) --}}
                        <div class="col-md-8">

                            <form id="profileForm" action="{{ route('user.profile.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label>Name</label>
                                        <input type="text" name="name"
                                               value="{{ old('name',$user->name) }}"
                                               class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label>Email</label>
                                        <input type="email" name="email"
                                               value="{{ old('email',$user->email) }}"
                                               class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label>Phone</label>
                                        <input type="text" name="phone"
                                               value="{{ old('phone',$user->phone) }}"
                                               class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label>City</label>
                                        <input type="text" name="city"
                                               value="{{ old('city',$user->city) }}"
                                               class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label>Password</label>
                                        <input type="password" name="password"
                                               class="form-control"
                                               placeholder="Leave blank if unchanged">
                                    </div>

                                    <div class="col-md-6">
                                        <label>Confirm Password</label>
                                        <input type="password" name="confirm_password"
                                               class="form-control">
                                    </div>

                                </div>

                                <div class="text-end mt-4">
                                    <button class="btn btn-primary px-4">
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

{{--  LIVE PREVIEW SCRIPT --}}
<script>
document.querySelector('input[name="name"]').addEventListener('input', function() {
    document.getElementById('previewName').innerText = this.value;
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