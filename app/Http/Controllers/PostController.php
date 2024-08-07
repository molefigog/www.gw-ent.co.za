<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\PostStoreRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\PostUpdateRequest;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {

        $search = $request->get('search', '');

        $posts = Post::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();
        $pageTitle = 'Pages';
        return view('posts.index', compact('posts', 'search', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $category = Category::all();
        return view('posts.create', compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostStoreRequest $request): RedirectResponse
    {
        $category = Category::all();
        $validated = $request->validated();
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('public');
        }

        $post = Post::create($validated);

        return redirect()
            ->route('posts.edit', $post)
            ->withSuccess(__('Created successfully..'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Post $post): View
    {
        $category = Category::all();
        return view('posts.show', compact('post', 'category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Post $post): View
    {
        $category = Category::all();
        return view('posts.edit', compact('post', 'category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        PostUpdateRequest $request,
        Post $post
    ): RedirectResponse {
        $this->authorize('update', $post);

        $validated = $request->validated();
        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::delete($post->image);
            }

            $validated['image'] = $request->file('image')->store('public');
        }

        $post->update($validated);

        return redirect()
            ->route('posts.edit', $post)
            ->withSuccess(__('Edited successfully..'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Post $post): RedirectResponse
    {
    

        if ($post->image) {
            Storage::delete($post->image);
        }

        $post->delete();

        return redirect()
            ->route('posts.index')
            ->withSuccess(__('Deleted successfully..'));
    }
}
