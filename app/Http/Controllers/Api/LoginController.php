<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // validate request
        $request->validate([
            'email' => 'required|email',
            'password'=> 'required',
        ]);

        // get user by request email
        $user = User::where('email', $request->email)->first();

        // check if user exists and password is correct
        if(!$user || !Hash::check($request->password, $user->password))
            return response()->json([
                'message' => 'The provided credentials are incorrect.'
            ], 401);

        // create token
        $token = $user->createToken('auth_token')->plainTextToken;

        // return response json
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
