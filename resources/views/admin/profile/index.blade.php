@extends('admin.layouts.app')

@section('title', 'Admin Profile Settings')
@section('page_title', 'Admin Profile & Security')

@push('styles')
<style>
    .profile-grid {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 24px;
        align-items: start;
    }

    .profile-card {
        background: #ffffff;
        border: 1px solid var(--admin-card-border);
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
    }

    .profile-avatar-wrap {
        position: relative;
        width: 100px;
        height: 100px;
        margin: 0 auto 16px;
    }

    .profile-avatar-img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .profile-status-dot {
        position: absolute;
        bottom: 4px;
        right: 4px;
        width: 16px;
        height: 16px;
        background: #16a34a;
        border: 2px solid #ffffff;
        border-radius: 50%;
    }

    .profile-name {
        font-size: 17px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 2px;
    }

    .profile-email {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 12px;
    }

    .profile-roles-wrap {
        display: flex;
        justify-content: center;
        gap: 6px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }

    .profile-detail-list {
        text-align: left;
        border-top: 1px solid #f1f5f9;
        padding-top: 14px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        font-size: 12px;
    }

    .profile-detail-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #64748b;
    }

    .profile-detail-item strong {
        color: #0f172a;
    }

    .form-section-title {
        font-size: 13.5px;
        font-weight: 700;
        color: #0f172a;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        padding-bottom: 8px;
        border-bottom: 1px solid #f1f5f9;
    }

    .avatar-preview-box {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-top: 8px;
        padding: 12px;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 8px;
    }

    .avatar-preview-box img {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #ffffff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    @media (max-width: 900px) {
        .profile-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

    <div class="profile-grid">
        <!-- Left: Profile Summary Card -->
        <div class="profile-card">
            <div class="profile-avatar-wrap">
                <img src="{{ $admin->avatar_url }}" alt="{{ $admin->name }}" class="profile-avatar-img" id="currentAvatarImg">
                <span class="profile-status-dot" title="Active"></span>
            </div>

            <h3 class="profile-name">{{ $admin->name }}</h3>
            <div class="profile-email">{{ $admin->email }}</div>

            <div class="profile-roles-wrap">
                @forelse($admin->roles as $role)
                    <span class="badge badge-info" style="font-size: 11px;">
                        <i class="fas fa-shield-alt" style="font-size: 9px; margin-right: 4px;"></i> {{ $role->name }}
                    </span>
                @empty
                    <span class="badge badge-secondary">Admin</span>
                @endforelse
            </div>

            <div class="profile-detail-list">
                <div class="profile-detail-item">
                    <span><i class="fas fa-phone" style="width: 16px; color: #64748b;"></i> Phone</span>
                    <strong>{{ $admin->phone ?? 'Not set' }}</strong>
                </div>
                <div class="profile-detail-item">
                    <span><i class="fas fa-user-check" style="width: 16px; color: #64748b;"></i> Status</span>
                    <span class="badge badge-success">{{ $admin->status ? 'Active' : 'Inactive' }}</span>
                </div>
                <div class="profile-detail-item">
                    <span><i class="far fa-calendar-alt" style="width: 16px; color: #64748b;"></i> Joined</span>
                    <strong>{{ $admin->created_at ? $admin->created_at->format('M d, Y') : 'N/A' }}</strong>
                </div>
            </div>

            <div style="margin-top: 20px; padding-top: 14px; border-top: 1px solid #f1f5f9; text-align: left;">
                <label for="avatarInput" class="btn btn-outline btn-sm" style="width: 100%; justify-content: center; cursor: pointer;">
                    <i class="fas fa-camera"></i> Change Photo
                </label>
            </div>
        </div>

        <!-- Right: Profile Edit Form -->
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-edit" style="color: var(--admin-primary); margin-right: 6px;"></i> Edit Admin Profile
                </h3>
                <span style="font-size: 11.5px; color: #64748b;">Update your name, email, credentials and avatar</span>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Basic Information Section -->
                    <div class="form-section-title">
                        <i class="fas fa-id-card" style="color: #0284c7;"></i> General Information
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 18px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" value="{{ old('name', $admin->name) }}" required class="form-control" placeholder="Enter your full name">
                            @error('name')
                                <span style="color: #ef4444; font-size: 11px;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email" value="{{ old('email', $admin->email) }}" required class="form-control" placeholder="Enter admin email address">
                            @error('email')
                                <span style="color: #ef4444; font-size: 11px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $admin->phone) }}" class="form-control" placeholder="e.g. 01700000000">
                            @error('phone')
                                <span style="color: #ef4444; font-size: 11px;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Profile Avatar Image</label>
                            <input type="file" id="avatarInput" name="avatar" class="form-control" accept="image/*" onchange="previewProfileImage(this)">
                            @error('avatar')
                                <span style="color: #ef4444; font-size: 11px;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Live Image Preview (hidden by default until file chosen) -->
                    <div id="avatarPreviewContainer" class="avatar-preview-box" style="display: none; margin-bottom: 24px;">
                        <img id="avatarPreviewImg" src="#" alt="New Avatar Preview">
                        <div>
                            <div style="font-weight: 700; font-size: 12px; color: #0f172a;">New Photo Selected</div>
                            <div style="font-size: 11px; color: #64748b;">This will replace your current avatar when you save.</div>
                        </div>
                    </div>

                    <!-- Security & Password Section -->
                    <div class="form-section-title">
                        <i class="fas fa-lock" style="color: #d97706;"></i> Security & Password (Optional)
                    </div>
                    <p style="font-size: 11.5px; color: #64748b; margin-top: -8px; margin-bottom: 14px;">
                        Leave password fields blank if you do not wish to change your current password.
                    </p>

                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 24px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control" placeholder="Enter current password">
                            @error('current_password')
                                <span style="color: #ef4444; font-size: 11px;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control" placeholder="At least 6 characters">
                            @error('password')
                                <span style="color: #ef4444; font-size: 11px;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Re-type new password">
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 12px; padding-top: 14px; border-top: 1px solid #f1f5f9;">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary" style="padding: 9px 22px;">
                            <i class="fas fa-save"></i> Save Profile Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    function previewProfileImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.getElementById('avatarPreviewImg');
                const container = document.getElementById('avatarPreviewContainer');
                const currentAvatar = document.getElementById('currentAvatarImg');

                previewImg.src = e.target.result;
                container.style.display = 'flex';
                if (currentAvatar) {
                    currentAvatar.src = e.target.result;
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
