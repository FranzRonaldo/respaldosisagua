<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        // Obtenemos todos los usuarios y los pasamos a la vista
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        // Buscamos al usuario por su id
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        // Validamos los datos enviados por el formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Buscamos al usuario por su id
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;

        // Si se envió una nueva contraseña, la actualizamos
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        // Redirigimos con un mensaje de éxito
        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        // Buscamos al usuario por su id y lo eliminamos
        User::destroy($id);

        // Redirigimos con un mensaje de éxito
        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente');
    }
}
