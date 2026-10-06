<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with(['permissions', 'admins'])->get();
        $admins = Admin::with('roles')->get();
        $permissions = Permission::all()->groupBy('group_name');

        return view('admin.roles.index', compact('roles', 'admins', 'permissions'));
    }

    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        if ($request->filled('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return back()->with('success', 'Role created successfully.');
    }

    public function updateRole(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
        ]);

        $role->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        $role->permissions()->sync($request->permissions ?? []);

        return back()->with('success', 'Role updated successfully.');
    }

    public function destroyRole(Role $role)
    {
        if ($role->slug === 'super-admin') {
            return back()->with('error', 'Cannot delete Super Admin role.');
        }

        $role->delete();
        return back()->with('success', 'Role deleted.');
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'phone' => 'nullable|string',
            'password' => 'required|string|min:6',
            'roles' => 'required|array',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'status' => true,
        ];

        // Direct upload to public/uploads
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $data['avatar'] = $filename;
        }

        $admin = Admin::create($data);
        $admin->roles()->sync($request->roles);

        return back()->with('success', 'Admin user created successfully.');
    }

    public function updateAdmin(Request $request, Admin $admin)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'phone' => 'nullable|string',
            'password' => 'nullable|string|min:6',
            'roles' => 'required|array',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => ($admin->id === 1 || in_array($admin->email, ['admin@indexfurniture.com', 'admin@otobi.com'])) ? true : $request->has('status'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            if ($admin->avatar && file_exists(public_path('uploads/' . $admin->avatar))) {
                @unlink(public_path('uploads/' . $admin->avatar));
            }

            $file = $request->file('avatar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $data['avatar'] = $filename;
        }

        $admin->update($data);
        $admin->roles()->sync($request->roles);

        return back()->with('success', 'Admin user updated successfully.');
    }

    public function destroyAdmin(Admin $admin)
    {
        if ($admin->id === 1 || in_array($admin->email, ['admin@indexfurniture.com', 'admin@otobi.com'])) {
            return back()->with('error', 'Cannot delete primary Super Admin.');
        }

        if ($admin->avatar && file_exists(public_path('uploads/' . $admin->avatar))) {
            @unlink(public_path('uploads/' . $admin->avatar));
        }

        $admin->delete();
        return back()->with('success', 'Admin user deleted.');
    }
}
