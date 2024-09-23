<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Pusher\Pusher;

class PusherAuthController extends Controller
{
    /**
     * Authorize a user to join a private channel.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function auth(Request $request)
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get the channel name and socket ID from the request
        $channel = $request->input('channel_name');
        $socketId = $request->input('socket_id');

        // Authorize the user to join the channel
        $auth = Broadcast::auth($channel, $socketId);

        return response()->json($auth);
    }
}
