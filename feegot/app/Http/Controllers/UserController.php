<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function homepage()
    {
        $user = Auth::user();
        $children = $user->students;

        return view('homepage', compact('user', 'children'));
    }

    public function index()
    {
        $user = Auth::user();
        $users = User::all();
        return view('user.index', compact('users','user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|integer|in:0,1,2',
            'phone_number' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
        ]);

        try {
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => $request->role,
            ];

            if ($request->role == 2) {
                $userData['phone_number'] = $request->phone_number;
                $userData['address'] = $request->address;
            }

            User::create($userData);

            return redirect('/users')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            return redirect('/users')->with('error', 'An error occurred while creating the user: ' . $e->getMessage());
        }
    }

    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id); // Find the user by ID

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'role' => 'required|integer|in:0,1,2',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        try {
            $userData = $request->only('name', 'email', 'role'); // Include role in userData

            if ($request->role == 2) { // Only for Guardian role
                $userData['phone_number'] = $request->phone_number;
                $userData['address'] = $request->address;
            }

            if ($request->filled('password')) {
                $userData['password'] = bcrypt($request->password);
            }

            $user->update($userData);

            return redirect()->route('manage-users')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('manage-users')->with('error', 'An error occurred while updating the user: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id); // Use findOrFail to handle user not found

            $user->delete();
            return redirect()->route('manage-users')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('manage-users')->with('error', 'An error occurred while deleting the user: ' . $e->getMessage());
        }
    }
}
