<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    /**
     * Get all users with their roles.
     *
     * @return JsonResponse
     */
    public function getUsersWithRoles(): JsonResponse
    {
        // Recupera gli utenti con i relativi ruoli usando eager loading
        $users = User::with('roles')->get();

        // Formatta i dati per la risposta se necessario
        $formattedUsers = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('role')
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $formattedUsers
        ]);
    }
}
