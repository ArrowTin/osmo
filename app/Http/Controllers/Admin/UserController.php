<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return User::select(['id','name', 'username', 'created_at'])->latest()->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:4'
        ]);
        return User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password)
        ]);
    }

    public function reset(Request $request,User $user)
    {
        $request->validate([
            'password' => 'required|min:4'
        ]);

        $user->update([
            'password' => $request->filled('password') ? bcrypt($request->password) : $user->password
        ]);

        return response()->json($user,201);

    }

    public function show(User $user)
    {
        return $user->only(['id', 'name','username', 'created_at']);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username,'.$user->id,
            'password' => 'sometimes|min:4'
        ]);
        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'password' => $request->filled('password') ? bcrypt($request->password) : $user->password
        ]);
        return $user;
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->noContent();
    }
}
