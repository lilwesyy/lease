<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\App;

class AppController extends Controller
{
    /**
     * Get application settings
     * 
     * @return \Illuminate\Http\JsonResponse
     */
/**
 * Get application settings
 * 
 * @return \Illuminate\Http\JsonResponse
 */
    public function getSettings()
    {
        $app = App::first();
        
        if (!$app) {
            return response()->json(['settings' => ['lang' => 'en']], 200);
        }
        
        // Assicuriamoci che i settings contengano sempre la proprietà lang
        $settings = $app->settings ?? [];
        if (!isset($settings['lang'])) {
            $settings['lang'] = 'en'; // valore predefinito
        }
        
        return response()->json(['settings' => $settings]);
    }
    
    /**
     * Save application settings
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveSettings(Request $request)
    {
        $settings = $request->input('settings');
        
        $app = App::first();
        
        if (!$app) {
            $app = new App();
        }
        
        $app->settings = $settings;
        $app->save();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Settings saved successfully',
            'settings' => $app->settings
        ]);
    }
}