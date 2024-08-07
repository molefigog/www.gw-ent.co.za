<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Music;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\GenreStoreRequest;
use App\Http\Requests\GenreUpdateRequest;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {


        $search = $request->get('search', '');

        $genres = Genre::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('genres.index', compact('genres', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {


        return view('genres.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GenreStoreRequest $request): RedirectResponse
    {


        $validated = $request->validated();
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imageName = $imageFile->getClientOriginalName();
            $validated['image'] = $imageFile->storeAs('public', $imageName);
        }

        $genre = Genre::create($validated);

        return redirect()
            ->route('genres.edit', $genre)
            ->withSuccess(__('created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Genre $genre): View
    {


        return view('genres.show', compact('genre'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Genre $genre): View
    {


        return view('genres.edit', compact('genre'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        GenreUpdateRequest $request,
        Genre $genre
    ): RedirectResponse {


        $validated = $request->validated();
        if ($request->hasFile('image')) {
            if ($genre->image) {
                Storage::delete($genre->image);
            }

          
            $imageFile = $request->file('image');
            $imageName = $imageFile->getClientOriginalName();
            $validated['image'] = $imageFile->storeAs('public', $imageName);
        }

        $genre->update($validated);

        return redirect()
            ->route('genres.edit', $genre)
            ->withSuccess(__('saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Genre $genre): RedirectResponse
    {


        if ($genre->image) {
            Storage::delete($genre->image);
        }

        $genre->delete();

        return redirect()
            ->route('genres.index')
            ->withSuccess(__('removed'));
    }
}
