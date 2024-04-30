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

        //Le code d'état HTTP 201 signifie "Created".
        return response()->json(['message' => 'Utilisateur enregistré avec succès', 'user' => $user], 201);
       
    }

   // @param  \Illuminate\Http\Request  $request
    //@return \Illuminate\Http\Response

    // la méthode attempt() fournie par le système d'authentification Laravel (Auth)
     //pour tenter de connecter l'utilisateur avec les informations d'identification fournies. 
    
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // Ce token est utilisé pour authentifier les futures requêtes de l'utilisateur.
            $token = $user->createToken('authToken')->plainTextToken;

            //Dans le protocole HTTP, le code d'état 200 signifie "OK".

            return response()->json(['message' => 'Login successful', 'token' => $token], 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function index()
    {
        return User::all();
    }

    public function show($id)
    {
        return User::findOrFail($id);
    }

    public function store(Request $request)
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

    return response()->json(['message' => 'Utilisateur créé avec succès', 'user' => $user], 201);
}

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'required|string|min:8',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $user->update([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'password' => bcrypt($request->input('password')),
    ]);

    return response()->json(['message' => 'Utilisateur mis à jour avec succès', 'user' => $user], 200);
}

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json([], 204);
    }

}
