<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request)
    { 
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        try {

            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $mypass = $request->password;
            $user->password = app('hash')->make($mypass);

            $user->save();
            return response()->json(['user' => $user, 'message' => 'User Created'], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => 'User Registration Failed!'], 405);
        }


    }


    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function index()
    {
        return response()->json(['data' => User::get(['id','name','email'])], 201);
    }
}
