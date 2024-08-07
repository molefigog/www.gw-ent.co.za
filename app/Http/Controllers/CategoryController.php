<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {


        $search = $request->get('search', '');

        $categories = Category::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('categories.index', compact('categories', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {

        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreRequest $request): RedirectResponse
    {


        $validated = $request->validated();

        $category = Category::create($validated);

        return redirect()
            ->route('categories.edit', $category)
            ->withSuccess(__('created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Category $category): View
    {


        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Category $category): View
    {

        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        CategoryUpdateRequest $request,
        Category $category
    ): RedirectResponse {

        $validated = $request->validated();

        $category->update($validated);

        return redirect()
            ->route('categories.edit', $category)
            ->withSuccess(__('saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        Category $category
    ): RedirectResponse {


        $category->delete();

        return redirect()
            ->route('categories.index')
            ->withSuccess(__('removed'));
    }
}
