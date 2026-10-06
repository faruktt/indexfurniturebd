<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show($slug)
    {
        $page = Page::where('slug', $slug)->where('status', true)->firstOrFail();

        // Also fetch related pages from the same section for the sidebar navigation
        $relatedPages = Page::where('section', $page->section)
            ->where('status', true)
            ->orderBy('order', 'asc')
            ->get();

        return view('frontend.pages.show', compact('page', 'relatedPages'));
    }
}
