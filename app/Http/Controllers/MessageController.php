<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Import the User model

class MessageController extends Controller
{
    public function adminMessage(Request $request)
    {
        // Fetch authenticated admin user ID
        $id = auth()->user()->id;
        $messengerColor = '#000';  // Default messenger color
        $dark_mode = 'light';      // Default theme mode

        // Check if admin is chatting with a specific user
        if ($request->has('chat_user')) {
            $chatUserId = $request->input('chat_user');
            $user = User::find($chatUserId);

            if ($user) {
                // Pass the chat user data to the admin.Message view
                return view('admin.Message', compact('user', 'id', 'messengerColor', 'dark_mode'));
            }
        }

        // If not chatting, return to the admin message view
        return view('admin.Message', compact('id', 'messengerColor', 'dark_mode'));
    }

    public function userMessage(Request $request)
    {
        // Fetch authenticated user ID
        $id = auth()->user()->id;
        $messengerColor = '#000';  // Default messenger color
        $dark_mode = 'light';      // Default theme mode

        // Check if the user is chatting with someone
        if ($request->has('chat_user')) {
            $chatUserId = $request->input('chat_user');
            $user = User::find($chatUserId);

            if ($user) {
                // Pass the chat user data to the user.Message view
                return view('user.Message', compact('user', 'id', 'messengerColor', 'dark_mode'));
            }
        }

        // If not chatting, return to the user message view
        return view('user.Message', compact('id', 'messengerColor', 'dark_mode'));
    }

    public function chatWithUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect('/messenger')->with('error', 'User not found.');
        }

        // Redirect back to the user message view with chat context
        return redirect()->route('user.Message', ['chat_user' => $id]);
    }

    public function chatWithAdmin($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect('/admin-messenger')->with('error', 'Admin not found.');
        }

        // Redirect back to the admin message view with chat context
        return redirect()->route('admin.Message', ['chat_user' => $id]);
    }

    
}
