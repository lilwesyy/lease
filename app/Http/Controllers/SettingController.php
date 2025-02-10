<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        $favicon = $setting ? $setting->favicon : null;
        $name = $setting ? $setting->name : null;
        $color = $setting ? $setting->primary_color : null;
        $theme = $setting ? $setting->theme : null;
        $lang = $setting ? $setting->lang : null;

        return view('settings.index', compact('favicon', 'name', 'color', 'theme', 'lang'));
    }

    public function update(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'primary_color' => 'required|regex:/^#[A-Fa-f0-9]{6}$/',
            'theme' => 'required|string|in:dark,white',
            'lang' => 'required|string|in:en,it,ro', // Added validation for language
        ]);

        // Handle file upload
        if ($request->hasFile('favicon')) {
            $favicon = $request->file('favicon');
            $faviconName = time() . '.' . $favicon->getClientOriginalExtension();
            $favicon->move(public_path('favicons'), $faviconName);
        } else {
            // Use the existing favicon if no new file is uploaded
            $faviconName = Setting::first()->favicon;
        }

        // Save the settings (this is just an example, adjust according to your needs)
        $settings = Setting::first();
        $settings->name = $request->input('name');
        $settings->favicon = $faviconName;
        $settings->primary_color = $request->input('primary_color');
        $settings->theme = $request->input('theme');
        $settings->lang = $request->input('lang'); // Added saving logic for language
        $settings->save();

        // Redirect back with a success message
        return redirect()->route('settings.index')->with('notification', ['type' => 'success', 'message' => 'Settings updated successfully!']);
    }
}
