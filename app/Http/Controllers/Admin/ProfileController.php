<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profile.index', compact('admin'));
    }

    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('admins', 'email')->ignore($admin->id)],
            'phone' => 'nullable|string|max:30',
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:3072',
        ]);

        // If user wants to change password, optionally check current_password if filled
        if ($request->filled('password')) {
            if ($request->filled('current_password')) {
                if (!Hash::check($request->current_password, $admin->password)) {
                    return back()->withErrors(['current_password' => 'Current password does not match our records.'])->withInput();
                }
            }
            $admin->password = Hash::make($request->password);
        }

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;

        // Avatar Upload
        if ($request->hasFile('avatar')) {
            if ($admin->avatar && file_exists(public_path('uploads/' . $admin->avatar))) {
                @unlink(public_path('uploads/' . $admin->avatar));
            }

            $file = $request->file('avatar');
            $filename = 'admin_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $admin->avatar = $filename;
        }

        $admin->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
