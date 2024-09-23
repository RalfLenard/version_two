<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdoptionRequest;
use App\Models\AnimalAbuseReport;
use App\Models\Meeting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


use Illuminate\Support\Facades\Http;



use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Events\MeetingScheduled;
use App\Events\UpdateMeetingScheduled;

use App\Notifications\Meetings;
use App\Notifications\UpdateMeetings;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;


class MeetingController extends Controller
{
        // Show the list of all approved adoption requests
    public function showApprovedAdoptionRequests()
    {
        // Retrieve all approved adoption requests with related animal data
        // Exclude those that already have a scheduled meeting
        $approvedRequests = AdoptionRequest::with('animalProfile')
            ->where('approved', true)
            ->whereDoesntHave('meeting') // Exclude requests with a scheduled meeting
            ->get();

        $approvedRequestss = AnimalAbuseReport::where('approved', true)
            ->whereDoesntHave('meeting') // Exclude requests with a scheduled meeting
            ->get();


        // Assuming you need the logged-in user ID for each request (if applicable)
        $userId = auth()->id(); // Get the ID of the currently authenticated user

        // Return the view with approved requests and user ID
        return view('admin.Meeting', compact('approvedRequests', 'userId', 'approvedRequestss'));
    }


        

        // Handle the scheduling of the meeting
    public function scheduleMeeting(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'adoption_request_id' => 'required|exists:adoption_requests,id',
            'user_id' => 'required|exists:users,id',
            'meeting_date' => 'required|date_format:Y-m-d h:i A',
        ]);

        // Convert the date from 12-hour format to 24-hour format for MySQL
        $meetingDate = Carbon::createFromFormat('Y-m-d h:i A', $request->input('meeting_date'))->format('Y-m-d H:i:s');



        // Create a new meeting
        $meeting = new Meeting();
        $meeting->adoption_request_id = $request->input('adoption_request_id');
        $meeting->user_id = $request->input('user_id');
        $meeting->meeting_date = $meetingDate;
        $meeting->status = 'Scheduled';



        try {
        $meeting->save();
        // Trigger the MeetingScheduled event
        event(new MeetingScheduled($meeting));
    

        // Check if the meeting has an associated user and notify them
        if ($meeting->user) {
            // Notify the user
            $meeting->user->notify(new Meetings($meeting));
        } else {
            Log::error('User not found for meeting ID: ' . $meeting->id);
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }


        // Redirect back with success message
        return redirect()->route('admin.approved.requests')->with('success', 'Meeting scheduled successfully.');
    } catch (\Exception $e) {
        // Log the error and return an error message
        Log::error('Error scheduling meeting: ' . $e->getMessage());
        return redirect()->back()->withErrors('An error occurred while scheduling the meeting.');
    }

    }

        // View the list of all appointments
        public function viewAppointmentList()
    {
        // Retrieve all scheduled appointments with related adoption request data
        $appointments = Meeting::with(['adoptionRequest.user', 'adoptionRequest.animalProfile'])
                            ->where('meeting_date', '>', now()) // Only get future appointments
                            ->orderBy('meeting_date', 'asc')
                            ->get();

        $appointmentss = Meeting::with('animalAbuseReport.user')
            ->where('meeting_date', '>', now()) // Only get future appointments
            ->orderBy('meeting_date', 'asc')
            ->get();

        // Return the view with the appointments
        return view('admin.AppointmentList', compact('appointments', 'appointmentss'));
    }


        // Method to fetch appointments by date
        public function getAppointmentsByDate(Request $request)
        {
            // Validate the incoming date
            $date = $request->query('date');

            // Fetch meetings scheduled for the selected date
            $appointments = Meeting::whereDate('meeting_date', $date)
                                ->with(['adoptionRequest.user', 'adoptionRequest.animalProfile'])
                                ->get();

            // Return the appointments as JSON
            return response()->json($appointments);
        }

        public function getAllAppointments()
        {
            $appointments = Meeting::with(['adoptionRequest.user', 'adoptionRequest.animalProfile'])
                                    ->orderBy('meeting_date', 'asc')
                                    ->get();
                                    
            return response()->json($appointments);
        }

    public function update(Request $request)
    {
        // Validate incoming request
        $validatedData = $request->validate([
            'meeting_id' => 'required|exists:meetings,id',
            'meeting_date' => 'required|date_format:Y-m-d\TH:i',
        ]);

        // Find the meeting by ID
        $meeting = Meeting::find($validatedData['meeting_id']);

        if (!$meeting) {
            // Return failure response if meeting not found
            return response()->json(['success' => false, 'message' => 'Meeting not found.'], 404);
        }

        // Update the meeting details
        $meeting->meeting_date = $validatedData['meeting_date'];
        $meeting->save();

        // Fire an event to notify about the update
        event(new UpdateMeetingScheduled($meeting));

        // Send notification
        $user = $meeting->user; // Assuming 'user' is the relationship method
        if ($user) {
            $user->notify(new UpdateMeetings($meeting));
        } else {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        // Return success response
        return response()->json(['success' => true, 'message' => 'Meeting updated successfully.']);
    }


    public function createMeeting(Request $request) {
        
        $METERED_DOMAIN = env('METERED_DOMAIN');
        $METERED_SECRET_KEY = env('METERED_SECRET_KEY');
    

        // Contain the logic to create a new meeting
        $response = Http::post("https://{$METERED_DOMAIN}/api/v1/room?secretKey={$METERED_SECRET_KEY}", [
            'autoJoin' => true
        ]);

        $roomName = $response->json("roomName");
        
        return redirect("/meeting/{$roomName}"); // We will update this soon.
    }

  
    
    public function validateMeeting(Request $request)
    {
        // Retrieve environment variables for Metered API
        $METERED_DOMAIN = env('METERED_DOMAIN');
        $METERED_SECRET_KEY = env('METERED_SECRET_KEY');
    
        // Get the meeting ID from the form request
        $meetingId = $request->input('meetingId');
    
        // Send the request to the Metered API to check if the meeting exists
        $response = Http::get("https://{$METERED_DOMAIN}/api/v1/room/{$meetingId}", [
            'secretKey' => $METERED_SECRET_KEY
        ]);
    
        // Check if the response status is 200 (success)
        if ($response->successful()) {
            // Extract roomName from the response
            $roomName = $response->json('roomName');
            
            // If roomName exists, redirect to the meeting page
            if ($roomName) {
                return redirect("/meeting/{$roomName}");
            }
        }
    
        // If the meeting ID is invalid or the request failed, redirect back with an error
        return redirect()->back()->withErrors(['meetingId' => 'Invalid Meeting ID. Please try again.']);
    }
    




}
