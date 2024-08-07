<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\OwnerStoreRequest;
use App\Http\Requests\OwnerUpdateRequest;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;

class OwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
     

        $search = $request->get('search', '');

        $owners = Owner::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('owners.index', compact('owners', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
       

        return view('owners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OwnerStoreRequest $request): RedirectResponse
    {
        

        $validated = $request->validated();

        $owner = Owner::create($validated);

        return redirect()
            ->route('owners.edit', $owner)
            ->withSuccess(__('created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Owner $owner): View
    {
        
        return view('owners.show', compact('owner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Owner $owner): View
    {
        
        return view('owners.edit', compact('owner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        OwnerUpdateRequest $request,
        Owner $owner
    ): RedirectResponse {
       
        $validated = $request->validated();

        $owner->update($validated);

        return redirect()
            ->route('owners.edit', $owner)
            ->withSuccess(__('saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Owner $owner): RedirectResponse
    {
        

        $owner->delete();

        return redirect()
            ->route('owners.index')
            ->withSuccess(__('removed'));
    }
    public function runScheduledTasks()
    {
        Artisan::call('app:reset');
        return 'Monthly Downloads Scheduled tasks executed.';
    }

    public function wipeOut()
    {
        Artisan::call('expired:items');
        return 'Reset Scheduled tasks executed.';
    }

    public function siteMap()
    {
        Artisan::call('generate:sitemap');
        return 'Site Map Generated';
    }


    public function monthlyEmail()
    {
        // Run the Artisan command
        Artisan::call('email:monthly-schedule');

        return 'Email Sent';
    }
    
}