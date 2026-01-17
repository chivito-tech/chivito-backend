<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Mail\WelcomeMagicLink;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{

    public function getAllCustomers()
    {
        $customer = User::all()->toArray();
        if (empty($customer)) {
            Log::error('User attempt to get empty list of customers');
            return response()->json(['error' => 'The list of customers is empty'], 400);
        }

        Log::info('User requested list of customers and got it');
        return $customer;
    }
    public function create(Request $request)
    {
        $validatedRequest = Validator::make($request->all(), [
            'first_name' => "required|string",
            'last_name' => "required|string",
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'phone_number' => "nullable|string|max:19",
            'photo' => 'nullable|string',
        ]);

        if ($validatedRequest->fails()) {
            return response()->json(['errors' => $validatedRequest->errors()], 422);
        }

        $data = $validatedRequest->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        // Create token
        $token = $user->createToken('auth_token')->plainTextToken;

        $magicUrl = URL::temporarySignedRoute(
            'magic-login',
            now()->addMinutes(60),
            ['user' => $user->id]
        );

        $frontendUrl = rtrim(env('APP_FRONTEND_URL', 'http://localhost:3000'), '/');
        $redirectUrl = $frontendUrl . '/magic?link=' . urlencode($magicUrl);

        Mail::to($user->email)->send(
            new WelcomeMagicLink($redirectUrl, $user->first_name)
        );

        return response()->json([
            'status' => true,
            'data' => $user,
            'token' => $token
        ]);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $validated = $request->validate([
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone_number' => ['nullable', 'string', 'max:19'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('profiles', 'public');
            $validated['photo'] = Storage::url($path);
        } else {
            unset($validated['photo']);
        }

        $user->update($validated);

        return response()->json($user);
    }

    public function destroySelf(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        $user->delete();

        return response()->json(['message' => 'Account deleted'], 200);
    }
}
