<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Solution;
use Illuminate\Http\Request;

class SolutionController extends Controller
{
    public function index()
    {
        $solutions = Solution::with('category')->orderBy('order', 'asc')->get();
        return view('admin.solutions.index', compact('solutions'));
    }

    public function create()
    {
        $categories = Category::where('status', true)->orderBy('name', 'asc')->get();
        return view('admin.solutions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'category_id' => 'nullable|exists:categories,id',
            'custom_url' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
        ]);

        $data = [
            'name' => $request->name,
            'category_id' => $request->category_id,
            'custom_url' => $request->custom_url,
            'order' => $request->order ?? 0,
            'status' => $request->has('status'),
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $data['image'] = $filename;
        }

        Solution::create($data);

        return redirect()->route('admin.solutions.index')->with('success', 'Solution card created successfully.');
    }

    public function edit(Solution $solution)
    {
        $categories = Category::where('status', true)->orderBy('name', 'asc')->get();
        return view('admin.solutions.edit', compact('solution', 'categories'));
    }

    public function update(Request $request, Solution $solution)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'category_id' => 'nullable|exists:categories,id',
            'custom_url' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
        ]);

        $data = [
            'name' => $request->name,
            'category_id' => $request->category_id,
            'custom_url' => $request->custom_url,
            'order' => $request->order ?? 0,
            'status' => $request->has('status'),
        ];

        if ($request->hasFile('image')) {
            if ($solution->image && !str_starts_with($solution->image, 'otobi_solution_') && file_exists(public_path('uploads/' . $solution->image))) {
                @unlink(public_path('uploads/' . $solution->image));
            }

            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $data['image'] = $filename;
        }

        $solution->update($data);

        return redirect()->route('admin.solutions.index')->with('success', 'Solution card updated successfully.');
    }

    public function destroy(Solution $solution)
    {
        if ($solution->image && !str_starts_with($solution->image, 'otobi_solution_') && file_exists(public_path('uploads/' . $solution->image))) {
            @unlink(public_path('uploads/' . $solution->image));
        }

        $solution->delete();

        return redirect()->route('admin.solutions.index')->with('success', 'Solution card deleted successfully.');
    }

    public function toggle(Solution $solution)
    {
        $solution->status = !$solution->status;
        $solution->save();

        return back()->with('success', 'Solution status updated.');
    }
}
