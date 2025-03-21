<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DamageImageController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5000'
        ]);

        $file = $request->file('image');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        // Store in the public disk under damage-images
        $path = $file->storeAs('damage-images', $filename, 'public');

        return response()->json([
            'url' => Storage::url($path),
            'filename' => $filename
        ]);
    }
}
