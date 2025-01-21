<?php

namespace App\Http\Controllers;

use App\Models\Vehicles;
use App\Models\Setting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $vehicles = Vehicles::all();
        $setting = Setting::first();
        $favicon = $setting ? $setting->favicon : null;
        $name = $setting ? $setting->name : null;
        $color = $setting ? $setting->primary_color : null;
        $theme = $setting ? $setting->theme : null;
        return view('welcome', compact('vehicles', 'favicon', 'name', 'color', 'theme'));
    }
}
