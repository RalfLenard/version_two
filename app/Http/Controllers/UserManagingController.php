<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserManagingController extends Controller
{
    // Display all users
    public function index()
    {
        $users = User::where('usertype', 'user')->get(); // Retrieve users with 'user' type
        return view('admin.UserManaging', compact('users'));
    }
    
    // Make user an admin
    public function makeAdmin($id)
    {
        $user = User::findOrFail($id); // Find the user by ID
        $user->usertype = 'admin'; // Set user type to admin
        $user->save(); // Save the changes

        return redirect()->route('users.index')->with('success', 'User is now an admin.');
    }

    // Delete a user, but prevent deleting an admin
    public function destroy($id)
    {
        $user = User::findOrFail($id); // Find the user by ID
        
        if ($user->usertype === 'admin') {
            return redirect()->route('users.index')->with('error', 'Admin users cannot be deleted.');
        }

        $user->delete(); // Delete the user

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
