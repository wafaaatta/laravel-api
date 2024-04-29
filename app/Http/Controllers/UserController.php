<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\UserController;

class UserController extends Controller
{
   // @param  \Illuminate\Http\Request  $request
    //@return \Illuminate\Http\Response

    public function register(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8', 
        ]);

       
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
           
        }

       
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')), 
        ]);

        
        return response()->json(['message' => 'Utilisateur enregistré avec succès', 'user' => $user], 201);
       
    }

   // @param  \Illuminate\Http\Request  $request
    //@return \Illuminate\Http\Response
    
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json(['message' => 'Login successful', 'token' => $token], 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}
