<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $query = Page::query();

        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }

        $pages = $query->orderBy('section', 'asc')
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'section' => 'required|in:company,information',
            'content' => 'required|string',
            'order' => 'nullable|integer',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $slug = $request->filled('slug')
            ? Str::slug($request->slug)
            : Str::slug($request->title);

        // Ensure unique slug
        $baseSlug = $slug;
        $counter = 1;
        while (Page::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        Page::create([
            'title' => $request->title,
            'slug' => $slug,
            'section' => $request->section,
            'content' => $request->content,
            'order' => $request->order ?? 0,
            'status' => $request->has('status'),
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'section' => 'required|in:company,information',
            'content' => 'required|string',
            'order' => 'nullable|integer',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $slug = Str::slug($request->slug);

        $page->update([
            'title' => $request->title,
            'slug' => $slug,
            'section' => $request->section,
            'content' => $request->content,
            'order' => $request->order ?? 0,
            'status' => $request->has('status'),
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.pages.index')->with('success', 'Page deleted successfully.');
    }

    public function toggle(Page $page)
    {
        $page->status = !$page->status;
        $page->save();

        return back()->with('success', 'Page status updated.');
    }
}
