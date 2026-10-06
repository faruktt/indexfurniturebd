@extends('admin.layouts.app')

@section('title', 'Roles & Permissions')
@section('page_title', 'Role-Based Access Control & Staff')

@section('content')
<!-- Top Section: Admins Staff List -->
<div class="card" style="margin-bottom: 30px;">
    <div class="card-header">
        <h3 class="card-title">Admin Staff Users ({{ $admins->count() }})</h3>
        <button type="button" class="btn btn-primary btn-sm" onclick="toggleAdminModal()">
            <i class="fas fa-user-plus"></i> Add New Admin
        </button>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Admin Name</th>
                    <th>Email Address</th>
                    <th>Phone</th>
                    <th>Assigned Roles</th>
                    <th>Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($admins as $adm)
                    <tr>
                        <td style="font-weight: 700; color: #0f172a;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <img src="{{ $adm->avatar_url }}" style="width: 28px; height: 28px; border-radius: 50%;">
                                <span>{{ $adm->name }}</span>
                            </div>
                        </td>
                        <td>{{ $adm->email }}</td>
                        <td>{{ $adm->phone ?? '-' }}</td>
                        <td>
                            @foreach($adm->roles as $r)
                                <span class="badge badge-info">{{ $r->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            @if($adm->status)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; align-items: center; gap: 6px;">
                                <button type="button" class="btn btn-outline btn-sm" style="padding: 3px 8px; font-size: 11px;" 
                                    onclick="openEditAdminModal({{ json_encode($adm->only(['id', 'name', 'email', 'phone', 'status', 'avatar_url'])) }}, {{ json_encode($adm->roles->pluck('id')) }})">
                                    <i class="fas fa-edit"></i> Edit
                                </button>

                                @if($adm->id !== 1 && $adm->email !== 'admin@otobi.com' && $adm->email !== 'admin@indexfurniture.com')
                                    <form action="{{ route('admin.admins.destroy', $adm->id) }}" method="POST" style="display: inline-block; margin: 0;" onsubmit="return confirm('Remove this admin user?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger-sm" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Bottom Section: Roles & Permissions Matrix -->
<div style="display: grid; grid-template-columns: 360px 1fr; gap: 25px;">
    <!-- Create Role -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Create New Role</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Role Name *</label>
                    <input type="text" name="name" required class="form-control" placeholder="e.g. Order Manager, Content Editor">
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="2" class="form-control" placeholder="Brief description of responsibilities"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Permissions</label>
                    <div style="max-height: 220px; overflow-y: auto; border: 1px solid #cbd5e1; border-radius: 4px; padding: 10px; background: #fff;">
                        @foreach($permissions as $group => $perms)
                            <div style="font-weight: 700; font-size: 11px; color: #475569; text-transform: uppercase; margin: 8px 0 4px; border-bottom: 1px solid #f1f5f9;">
                                {{ $group }}
                            </div>
                            @foreach($perms as $perm)
                                <label style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px; font-size: 11.5px; cursor: pointer;">
                                    <input type="checkbox" name="permissions[]" value="{{ $perm->id }}">
                                    <span>{{ $perm->name }}</span>
                                </label>
                            @endforeach
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-plus"></i> Create Role
                </button>
            </form>
        </div>
    </div>

    <!-- Roles List -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Defined Roles ({{ $roles->count() }})</h3>
        </div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Role Name</th>
                        <th>Slug</th>
                        <th>Permissions Count</th>
                        <th>Assigned Admins</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                        <tr>
                            <td style="font-weight: 700;">{{ $role->name }}</td>
                            <td><code>{{ $role->slug }}</code></td>
                            <td>
                                <span class="badge badge-secondary">{{ $role->permissions->count() }} permissions</span>
                            </td>
                            <td>{{ $role->admins->count() }} staff</td>
                            <td style="text-align: right;">
                                @if($role->slug !== 'super-admin')
                                    <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Delete this role?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger-sm">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="badge badge-success">System Root</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal to Add Admin User -->
<div id="adminModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center;">
    <div style="background: #fff; width: 100%; max-width: 500px; border-radius: 8px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
        <div style="padding: 16px 20px; background: #0f172a; color: #fff; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 15px; font-weight: 700;">Create Admin Staff User</h3>
            <button type="button" onclick="toggleAdminModal()" style="background: none; border: none; color: #fff; font-size: 18px; cursor: pointer;">&times;</button>
        </div>
        <form action="{{ route('admin.admins.store') }}" method="POST" enctype="multipart/form-data" style="padding: 20px;">
            @csrf
            <div class="form-group">
                <label class="form-label">Full Name *</label>
                <input type="text" name="name" required class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">Email Address *</label>
                <input type="email" name="email" required class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">Password *</label>
                <input type="password" name="password" required class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">Assign Roles *</label>
                <div style="border: 1px solid #cbd5e1; border-radius: 4px; padding: 10px;">
                    @foreach($roles as $r)
                        <label style="display: flex; align-items: center; gap: 8px; margin-bottom: 5px; font-size: 12px; cursor: pointer;">
                            <input type="checkbox" name="roles[]" value="{{ $r->id }}">
                            <span>{{ $r->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Avatar Photo (Uploads to public/uploads/)</label>
                <input type="file" name="avatar" class="form-control" accept="image/*">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn btn-outline btn-sm" onclick="toggleAdminModal()">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm">Create Admin</button>
            </div>
        </form>
    </div>
</div>
<!-- Modal to Edit Admin User -->
<div id="editAdminModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center;">
    <div style="background: #fff; width: 100%; max-width: 520px; border-radius: 8px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.2); max-height: 90vh; display: flex; flex-direction: column;">
        <div style="padding: 16px 20px; background: #0f172a; color: #fff; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 15px; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-user-edit" style="color: var(--admin-primary);"></i>
                <span>Edit Admin Staff: <span id="editAdminHeaderName"></span></span>
            </h3>
            <button type="button" onclick="closeEditAdminModal()" style="background: none; border: none; color: #fff; font-size: 20px; cursor: pointer; line-height: 1;">&times;</button>
        </div>
        <form id="editAdminForm" method="POST" enctype="multipart/form-data" style="padding: 20px; overflow-y: auto;">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Full Name *</label>
                <input type="text" name="name" id="edit_admin_name" required class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">Email Address *</label>
                <input type="email" name="email" id="edit_admin_email" required class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" id="edit_admin_password" class="form-control" placeholder="Leave blank to keep current password">
                <small style="color: #64748b; font-size: 11px;">Leave blank if not changing (min 6 characters if entered).</small>
            </div>

            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" id="edit_admin_phone" class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">Assign Roles *</label>
                <div style="border: 1px solid #cbd5e1; border-radius: 4px; padding: 10px; max-height: 120px; overflow-y: auto;">
                    @foreach($roles as $r)
                        <label style="display: flex; align-items: center; gap: 8px; margin-bottom: 5px; font-size: 12px; cursor: pointer;">
                            <input type="checkbox" name="roles[]" class="edit-role-checkbox" value="{{ $r->id }}">
                            <span>{{ $r->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 8px; font-size: 12.5px; font-weight: 600; cursor: pointer;">
                    <input type="checkbox" name="status" id="edit_admin_status" value="1">
                    <span>Account Active</span>
                </label>
            </div>

            <div class="form-group">
                <label class="form-label">Avatar Photo</label>
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                    <img id="editAdminAvatarPreview" src="" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 1px solid #cbd5e1;">
                    <input type="file" name="avatar" class="form-control" accept="image/*" onchange="previewEditAdminAvatar(this)">
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 15px; padding-top: 15px; border-top: 1px solid #f1f5f9;">
                <button type="button" class="btn btn-outline btn-sm" onclick="closeEditAdminModal()">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-save"></i> Update Staff
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleAdminModal() {
        const modal = document.getElementById('adminModal');
        modal.style.display = modal.style.display === 'flex' ? 'none' : 'flex';
    }

    function openEditAdminModal(admin, roleIds) {
        const modal = document.getElementById('editAdminModal');
        const form = document.getElementById('editAdminForm');
        
        form.action = "{{ url('admin/admins') }}/" + admin.id;

        document.getElementById('editAdminHeaderName').textContent = admin.name;
        document.getElementById('edit_admin_name').value = admin.name;
        document.getElementById('edit_admin_email').value = admin.email;
        document.getElementById('edit_admin_phone').value = admin.phone || '';
        document.getElementById('edit_admin_password').value = '';
        document.getElementById('edit_admin_status').checked = Boolean(admin.status);
        document.getElementById('editAdminAvatarPreview').src = admin.avatar_url;

        const checkboxes = document.querySelectorAll('.edit-role-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = roleIds.includes(parseInt(cb.value));
        });

        modal.style.display = 'flex';
    }

    function closeEditAdminModal() {
        document.getElementById('editAdminModal').style.display = 'none';
    }

    function previewEditAdminAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('editAdminAvatarPreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
