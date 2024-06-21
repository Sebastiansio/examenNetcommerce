<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //devuelve todos los usuarios
        $users = User::all();
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Validacion de los datos que llegan
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email'=> 'required|email|unique:users,email',
            'password'=> 'required|string|min:8'
        ]);

        
        // Crear el nuevo usuario
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);


        return response()->json($user,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //devuelve un usuario especifico
        $user = User::find($id);

        if($user){
            return response()->json($user);
        }else{
            return response()->json(['message'=> ''],404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        if ($user) {
            // Validar los datos de entrada
            $validatedData = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:users,email,' . $id,
                'password' => 'nullable|string|min:8|confirmed',
            ]);

            // Actualizar el usuario
            $user->update([
                'name' => $validatedData['name'] ?? $user->name,
                'email' => $validatedData['email'] ?? $user->email,
                'password' => isset($validatedData['password']) ? Hash::make($validatedData['password']) : $user->password,
            ]);

            return response()->json($user);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['message' => 'User deleted']);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }
    
}
