<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtiene todos los usuarios
        $users = User::all();
        return response()->json($users);  // Devuelve la lista en formato JSON
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'DNI' => 'required|string|max:255|unique:users',
            'number' => 'required|string|max:20',
            'password' => 'required|string|min:8',
            'adress' => 'required|string',
            'last_buy' => 'required|string',
            'fav_food' => 'nullable|string',
            'fav_drink' => 'nullable|string',
        ]);
    
        // Crear un nuevo usuario
        $user = User::create($validated);
    
        // Responder con el usuario creado y el código de estado 201
        return response()->json($user, 201);  // El código 201 indica que el recurso fue creado con éxito
    }
    

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json($user);  // Devuelve el usuario encontrado en formato JSON
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Validación de los datos recibidos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'DNI' => 'required|string|unique:users,DNI,' . $user->id,
            'number' => 'required|string',
            'password' => 'nullable|string|min:8',
            'adress' => 'required|string',
            'last_buy' => 'nullable|string',
            'fav_food' => 'nullable|string',
            'fav_drink' => 'nullable|string',
        ]);

        // Actualizar el usuario
        $user->update($validated);

        return response()->json($user);  // Devuelve el usuario actualizado
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Elimina el usuario
        $user->delete();

        return response()->json(null, 204);  // Retorna una respuesta vacía con el código de estado 204 (sin contenido)
    }
    public function searchByName($name)
    {
        // Busca el usuario con el nombre exacto
        $user = User::where('name', $name)->first();
    
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
    
        return response()->json($user);
    }
}
