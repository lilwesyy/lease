<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class DashboardController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        $favicon = $setting ? $setting->favicon : null;
        $name = $setting ? $setting->name : null;
        $color = $setting ? $setting->primary_color : null;
        $theme = $setting ? $setting->theme : null;

        return view('dashboard', compact('favicon', 'name', 'color', 'theme'));
    }
}
