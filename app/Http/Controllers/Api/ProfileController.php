<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile information.
     *
     */
    public function show(Request $request)
    {
        // return response json
        return response()->json([
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     *
     */
    public function update(Request $request)
    {
        // get current user
        $user = $request->user();

        // validate request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        // update user data
        $user->update($validatedData);

        // return response json
        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        // remove token user
        $request->user()->currentAccessToken()->delete();

        // return response json
        return response()->json(['message' => 'Logged out successfully']);
    }
}
