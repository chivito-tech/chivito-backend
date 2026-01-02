<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


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

        return response()->json([
            'status' => true,
            'data' => $user,
            'token' => $token
        ]);
    }



public function profile(Request $request)
{
    return response()->json([
        'status' => true,
        'data' => $request->user() // returns authenticated user
    ]);
}

}
