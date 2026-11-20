<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        Gate::authorize('manage-tags');
        return view('admin.tags.index', [
            'tags' => Tag::withCount('posts')->orderBy('name')->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('manage-tags');
        return view('admin.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request): RedirectResponse
    {
        Gate::authorize('manage-tags');
        Tag::create($request->validated());
        return redirect()->route('admin.tags.index')->with('success', 'Tag créé.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag): View
    {
        $posts = $tag->posts()->published()->with('user', 'category', 'tags')->latest('published_at')->paginate(4);
        return view('tags.show', compact('tag', 'posts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag): View
    {
        Gate::authorize('manage-tags');
        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag): RedirectResponse
    {
        Gate::authorize('manage-tags');
        $tag->update($request->validated());
        return redirect()->route('admin.tags.index')->with('success', 'Tag modifié.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag): RedirectResponse
    {
        Gate::authorize('manage-tags');
        $tag->delete();
        return redirect()->route('admin.tags.index')->with('success', 'Tag supprimé.');
    }
}
