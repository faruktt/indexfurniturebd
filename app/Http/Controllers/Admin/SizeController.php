<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::withCount('products')->get();
        return view('admin.attributes.sizes', compact('sizes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sizes,name',
            'code' => 'nullable|string|max:50',
        ]);

        Size::create($request->only('name', 'code'));

        return redirect()->route('admin.sizes.index')->with('success', 'Size created successfully.');
    }

    public function update(Request $request, Size $size)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sizes,name,' . $size->id,
            'code' => 'nullable|string|max:50',
        ]);

        $size->update($request->only('name', 'code'));

        return redirect()->route('admin.sizes.index')->with('success', 'Size updated successfully.');
    }

    public function destroy(Size $size)
    {
        $size->delete();
        return redirect()->route('admin.sizes.index')->with('success', 'Size deleted.');
    }
}
