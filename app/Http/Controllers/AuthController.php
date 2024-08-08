<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use Validator;

use App\Models\User;

class AuthController extends Controller
{
    public function _construct()
    {
        $this->middleware('auth:api', ['except' => 'login', 'register']);
    }

    public function register(Request $request)
    {
        $validator = \Validator::make($request->all(),[
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);
        if($validator->fails())
        {
            return response()->json([
                'errors' => true,
                'data' => $validator->errors(),
            ], 400);
        }
        
        $user = User::create(array_merge(
            $validator->validate(),
            ['password'=>bcrypt($request->password)]
        ));
        return  response()->json([
            'message' => 'User Registered Successfully!',
            'user' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        $validator = \Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);
        if($validator->fails())
        {
            return response()->json([
                'errors' => true,
                'data' => $validator->errors(),
            ], 422);
        }
        
        if(!$token=auth()->attempt($validator->validated()))
        {
            return response()->json([
                'error'=>'Unauthorized',
            ], 401);
        }
        return $this->createNewToken($token);
    }

    public function createNewToken($token)
    {
        return response()->json([
            'user'=>auth()->user(),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL()*60,
        ]);
    }

    public function profile()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();
        return response()->json([
            'message'=>'User Logged Out',
        ]);
    }
    
}
