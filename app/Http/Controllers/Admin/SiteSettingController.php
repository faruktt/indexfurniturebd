<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $inputs = $request->except(['_token', 'logo', 'favicon']);

        foreach ($inputs as $key => $value) {
            SiteSetting::set($key, $value);
        }

        // Upload logo: direct to public/uploads, store filename only
        if ($request->hasFile('logo')) {
            $oldLogo = SiteSetting::get('logo');
            if ($oldLogo && $oldLogo !== 'otobi_logo.png' && file_exists(public_path('uploads/' . $oldLogo))) {
                @unlink(public_path('uploads/' . $oldLogo));
            }
            $file = $request->file('logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            SiteSetting::set('logo', $filename);
        }

        // Upload favicon: direct to public/uploads, store filename only
        if ($request->hasFile('favicon')) {
            $oldFavicon = SiteSetting::get('favicon');
            if ($oldFavicon && file_exists(public_path('uploads/' . $oldFavicon))) {
                @unlink(public_path('uploads/' . $oldFavicon));
            }
            $file = $request->file('favicon');
            $filename = 'favicon_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            SiteSetting::set('favicon', $filename);
        }

        return back()->with('success', 'Site settings updated successfully.');
    }
}
