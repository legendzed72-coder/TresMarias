<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        
        return view('admin.users.index', [
            'users' => $users,
            'totalUsers' => User::count(),
            'adminUsers' => User::where('role', 'admin')->count(),
            'staffUsers' => User::where('role', 'staff')->count(),
            'customerUsers' => User::where('role', 'customer')->count(),
        ]);
    }

    public function create()
    {
        // Just return the index view, user creation is done via modal
        return redirect()->route('admin.users.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', Password::defaults()],
            'role' => ['required', 'in:admin,staff,customer'],
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['email_verified_at'] = now();

        User::create($validated);

        return response()->json(['message' => 'User created successfully'], 201);
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:admin,staff,customer'],
            'password' => ['nullable', Password::defaults()],
        ]);

        if ($validated['password'] ?? null) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json(['message' => 'User updated successfully']);
    }

    public function destroy(User $user)
    {
        if ($user->id === \Illuminate\Support\Facades\Auth::id()) {
            return response()->json(['message' => 'Cannot delete your own account'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
