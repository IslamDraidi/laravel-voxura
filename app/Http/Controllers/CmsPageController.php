<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CmsPageController extends Controller
{
    public function index()
    {
        $pages = CmsPage::orderBy('sort_order')->orderBy('id')->get();

        return view('admin.cms.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.cms.pages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:cms_pages,slug',
            'content' => 'nullable|string',
            'status' => 'required|in:active,draft',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        CmsPage::create([
            'title' => $request->title,
            'slug' => $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->title),
            'content' => $request->content,
            'status' => $request->status,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Page created successfully.']);
        }

        return redirect()->route('admin.cms.pages.index')->with('success', 'Page created successfully.');
    }

    public function edit(CmsPage $page)
    {
        return view('admin.cms.pages.edit', compact('page'));
    }

    public function update(Request $request, CmsPage $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:cms_pages,slug,'.$page->id,
            'content' => 'nullable|string',
            'status' => 'required|in:active,draft',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $page->update([
            'title' => $request->title,
            'slug' => $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->title),
            'content' => $request->content,
            'status' => $request->status,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => '"'.$page->title.'" updated successfully.']);
        }

        return redirect()->route('admin.cms.pages.index')->with('success', '"'.$page->title.'" updated successfully.');
    }

    public function destroy(CmsPage $page)
    {
        $page->delete();

        return back()->with('success', '"'.$page->title.'" deleted.');
    }

    // Public-facing view
    public function show(string $slug)
    {
        $page = CmsPage::where('slug', $slug)->where('status', 'active')->firstOrFail();

        return view('pages.show', compact('page'));
    }

    // Admin preview — no status filter
    public function preview(CmsPage $page)
    {
        return view('pages.show', compact('page'));
    }
}
