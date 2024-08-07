<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\SettingStoreRequest;
use App\Http\Requests\SettingUpdateRequest;


class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {


        $search = $request->get('search', '');

        $settings = Setting::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('settings.index', compact('settings', 'search'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {

        return view('settings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SettingStoreRequest $request): RedirectResponse
    {


        // $validated = $request->validated();
        // if ($request->hasFile('image')) {
        //     $validated['image'] = $request->file('image')->store('public');
        // }

        // if ($request->hasFile('logo')) {
        //     $validated['logo'] = $request->file('logo')->store('public');
        // }

        // if ($request->hasFile('favicon')) {
        //     $validated['favicon'] = $request->file('favicon')->store('public');
        // }

        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->storeAs('public', $request->file('image')->getClientOriginalName());
        }

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->storeAs('public', $request->file('logo')->getClientOriginalName());
        }

        if ($request->hasFile('favicon')) {
            $validated['favicon'] = $request->file('favicon')->storeAs('public', $request->file('favicon')->getClientOriginalName());
        }


        $setting = Setting::create($validated);

        return redirect()
            ->route('settings.edit', $setting)
            ->withSuccess(__('created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Setting $setting): View
    {


        return view('settings.show', compact('setting'));
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Setting $setting): View
    {


        return view('settings.edit', compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        SettingUpdateRequest $request,
        Setting $setting
    ): RedirectResponse {


        $validated = $request->validated();
        if ($request->hasFile('image')) {
            if ($setting->image) {
                Storage::delete($setting->image);
            }

            $validated['image'] = $request->file('image')->store('public');
        }

        if ($request->hasFile('logo')) {
            if ($setting->logo) {
                Storage::delete($setting->logo);
            }

            $validated['logo'] = $request->file('logo')->store('public');
        }

        if ($request->hasFile('favicon')) {
            if ($setting->favicon) {
                Storage::delete($setting->favicon);
            }

            $validated['favicon'] = $request->file('favicon')->store('public');
        }

        $setting->update($validated);

        return redirect()
            ->route('settings.edit', $setting)
            ->withSuccess(__('saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        Setting $setting
    ): RedirectResponse {

        if ($setting->image) {
            Storage::delete($setting->image);
        }

        if ($setting->logo) {
            Storage::delete($setting->logo);
        }

        if ($setting->favicon) {
            Storage::delete($setting->favicon);
        }

        $setting->delete();

        return redirect()
            ->route('settings.index')
            ->withSuccess(__('removed'));
    }
}
