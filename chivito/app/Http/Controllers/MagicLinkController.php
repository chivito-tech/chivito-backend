<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class MagicLinkController extends Controller
{
    public function login(Request $request)
    {
        if (!$request->hasValidSignature()) {
            return response()->json(['error' => 'Invalid or expired link'], 401);
        }

        $userId = $request->query('user');
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $token = $user->createToken('magic_link')->plainTextToken;
        $frontendUrl = rtrim(env('APP_FRONTEND_URL', 'http://localhost:3000'), '/');
        $redirectUrl = $frontendUrl . '/magic?token=' . urlencode($token);

        return redirect()->away($redirectUrl);
    }
}
