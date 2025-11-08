<?php

namespace App\Http\Controllers;

use App\Http\controllers\Api\trait\apiTrait;
use App\Http\Resources\ApiResorce;
use App\Models\admin;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    use apiTrait;

        public function register(Request $request)
    {
           $validated=Validator::make($request->all(),[
            "name"=>"required|min:3",
            "email"=>"required|max:255|unique:admins,email",
            'password' => 'required|string|min:6',
            "age"=>"required|max:100",
            "gender"=>"required|in:male,female"
        ]);
        if($validated->fails()){
            return $this->validated_fails($validated);
        }

        $user = admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            "age"=>$request->age,
            "gender"=>$request->gender
        ]);

        try {
            $token = JWTAuth::fromUser($user);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        return response()->json([
            'token' => $token,
            'admin' => new ApiResorce($user),
        ], 201);

    }

        public function login(Request $request)
    {
            $validated=Validator::make($request->all(),[

            "email"=>"required|email|exists:admins,email",
            "password"=>"required|min:6",
        ]);


        if($validated->fails()){
            return $this->ApiResponse(null,422,$validated->errors());
        }

        $credentials = $request->only('email', 'password');

        try {
            if (!$token = auth("admin")->attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        return response()->json([
            'token' => $token,
            'expires_in' => auth('admin')->factory()->getTTL() /60 ."-hours",
        ]);
    }



        public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to logout, please try again'], 500);
        }

        return response()->json(['message' => 'Successfully logged out']);
    }


    public function getUser()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            return response()->json(new ApiResorce($user));
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to fetch user profile'], 500);
        }
    }



    public function updateUser(Request $request)
    {
        try {
            $user = Auth::user();
            $user->update($request->only(['name', 'email']));
            return response()->json($user);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to update user'], 500);
        }
    }
}
