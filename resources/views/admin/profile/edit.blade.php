@extends('layouts.admin')

@section('title', 'Account')

@section('content')

<div class="page-header-bar">
    <div>
        <h1>Account</h1>
        <p class="text-muted mb-0 small">Your admin login, display name, and profile photo.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">Update Profile</div>
            <div class="card-body">
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    @if($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label" for="profile_avatar">Profile photo</label>
                        <input type="file" name="avatar" id="profile_avatar" class="form-control" accept="image/jpeg,image/png,image/webp">
                        <div class="form-text">JPEG, PNG or WebP, up to 2&nbsp;MB. Choosing a file shows a preview below.</div>
                        @if($user->avatar)
                            <div class="form-check mt-2">
                                <input type="checkbox" name="remove_avatar" value="1" class="form-check-input" id="remove_avatar" @checked(old('remove_avatar'))>
                                <label class="form-check-label" for="remove_avatar">Remove current photo</label>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address *</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <hr class="my-4">
                    <h6 class="fw-700 mb-3">Change Password</h6>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Profile
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body text-center p-4">
                @if($user->avatar)
                    <div style="width:80px;height:80px;border-radius:50%;margin:0 auto 16px;overflow:hidden;border:2px solid #e2e8f0;background:#f8fafc;">
                        <img src="{{ asset('storage/'.$user->avatar) }}" alt="" style="width:100%;height:100%;object-fit:cover;display:block;">
                    </div>
                @else
                    <div style="width:80px;height:80px;background:linear-gradient(135deg,#2563eb,#60a5fa);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2rem;color:#fff;font-weight:800;margin:0 auto 16px;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <h5 class="fw-700 mb-1">{{ $user->name }}</h5>
                <p class="text-muted mb-0">{{ $user->email }}</p>
                <div class="mt-3">
                    <span class="badge" style="background:#dbeafe;color:#2563eb;padding:6px 14px;">Administrator</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
