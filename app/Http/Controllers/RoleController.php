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
        $users = User::with('roles')->get();

        $formattedUsers = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('role'),
                'created_at' => $user->created_at->format('Y-m-d H:i:s')
            ];
        })->sortByDesc('created_at')->values()->all();

        return response()->json([
            'status' => 'success',
            'data' => $formattedUsers
        ]);
    }

    public function updateRole(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string'
        ]);

        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        $userRole = $user->roles()->first();

        if (!$userRole) {
            return response()->json([
                'status' => 'error',
                'message' => 'Role not found for the user'
            ], 404);
        }

        $userRole->update([
            'role' => $request->role
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Role updated successfully'
        ]);
    }
}
