<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::withCount('products')->get();
        return view('admin.attributes.colors', compact('colors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:colors,name',
            'code' => 'nullable|string|max:50',
        ]);

        Color::create($request->only('name', 'code'));

        return redirect()->route('admin.colors.index')->with('success', 'Color created successfully.');
    }

    public function update(Request $request, Color $color)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:colors,name,' . $color->id,
            'code' => 'nullable|string|max:50',
        ]);

        $color->update($request->only('name', 'code'));

        return redirect()->route('admin.colors.index')->with('success', 'Color updated successfully.');
    }

    public function destroy(Color $color)
    {
        $color->delete();
        return redirect()->route('admin.colors.index')->with('success', 'Color deleted.');
    }
}
