<?php

namespace App\Http\Controllers;

use App\Http\controllers\Api\trait\apiTrait;
use App\Http\Resources\userResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class userAuthController extends Controller
{
    use apiTrait;

    public function register(Request $request){
        $validated = Validator::make($request->all(), [
            'name'     => 'required|string|min:3',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'gender'   => 'required|in:male,female',
            'number'   => 'required|numeric',
            'age'      => 'required|numeric|min:10|max:100',
        ]);

        if($validated->fails()){
            return $this->validated_fails($validated);
        }
        $Vdata= $validated->validated();


        $user=User::create([
            'name'=>$Vdata['name'],
            'email'=>$Vdata['email'],
            'password'=>$Vdata['password'],
            'gender'=>$Vdata['gender'],
            'number'=>$Vdata['number'],
            'age'=>$Vdata['age'],

        ]);

        try {
            $token = JWTAuth::fromUser($user);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        return response()->json([
            'token' => $token,
            'admin' => new userResource($user),
        ], 201);
    }

    public function login(Request $request){
        $validated=validator::make($request->all(),[
            'email'=>'required|email|exists:users,email',
            'password'=>"required|min:6"
        ]);
        if($validated->fails()){
            return $this->validated_fails($validated);
        }
        $credentials=$request->only("email","password");

           try {
            if (!$token = auth()->guard('user')->attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

         return response()->json([
            'token' => $token,
            'expires_in' => auth('user')->factory()->getTTL() /60 ."-hours",
        ]);
    }
}
