<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken; // Genera il token

            return response()->json([
                'user' => $user,
                'token' => $token, // Deve essere presente qui
            ]);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function getUser(Request $request)
    {
        $user = $request->user();
        $user->load('roles');

        return response()->json([
            'user' => $user,
            'roles' => $user->roles
        ]);
    }

    public function deleteUser(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        // Controllo dei permessi (opzionale, ma consigliato)
        // if ($request->user()->cannot('delete', $user)) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Unauthorized'
        //     ], 403);
        // }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully'
        ]);
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string',
        ]);
    
        $defaultPassword = 'password123';
    
        // Create the user in the 'users' table
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($defaultPassword),
        ]);
    
        // Insert a new row into the 'roles' table with user_id and role
        DB::table('roles')->insert([
            'user_id' => $user->id,
            'role' => $request->role,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    
        return response()->json([
            'status' => 'success',
            'user' => $user
        ], 201);
    }
}
