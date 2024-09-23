<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdoptionRequest;
use App\Models\AnimalProfile;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Events\AdoptionRequestSubmitted;
use App\Events\AdoptionRequestRejected;
use App\Events\AdoptionRequestApproved;
use App\Events\AnimalAdopted;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Events\AdoptionRequestVerify;

use App\Notifications\AdoptionRequestVerifying;
use App\Notifications\AdoptionRequestReject;
use App\Notifications\AdoptionRequestApprove;

use Illuminate\Support\Facades\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;




class AdoptionRequestController extends Controller
{
    /**
     * Display the adoption form for a specific animal.
     */
    public function showAdoptionForm(AnimalProfile $animalprofile)
    {

        $user = auth()->user();
         $notifications = $user->notifications;
        return view('user.AdoptionRequestForm', ['animalprofile' => $animalprofile], compact('notifications'));
    }

    /**
     * Handle the submission of an adoption request.
     */
    public function submitAdoptionRequest(Request $request, $id)
    {
        // Find the animal profile
        $animalprofile = AnimalProfile::findOrFail($id);

        // Validate the request data
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string',
            'phone_number' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
            'question1' => 'required|string|max:1000',
            'question2' => 'required|string|max:1000',
            'question3' => 'required|string|max:1000',
            'valid_id' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'valid_id_with_owner' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Store the uploaded files
        $validIdPath = $request->file('valid_id')->store('valid_ids', 'public');
        $validIdWithOwnerPath = $request->file('valid_id_with_owner')->store('valid_ids_with_owners', 'public');

        // Create a new adoption request
        $adoptionRequest = new AdoptionRequest();
        $adoptionRequest->animal_id = $animalprofile->id;
        $adoptionRequest->animal_name = $animalprofile->name;
        $adoptionRequest->first_name = $validatedData['first_name'];
        $adoptionRequest->last_name = $validatedData['last_name'];
        $adoptionRequest->gender = $validatedData['gender'];
        $adoptionRequest->phone_number = $validatedData['phone_number'];
        $adoptionRequest->address = $validatedData['address'];
        $adoptionRequest->salary = $validatedData['salary'];
        $adoptionRequest->question1 = $validatedData['question1'];
        $adoptionRequest->question2 = $validatedData['question2'];
        $adoptionRequest->question3 = $validatedData['question3'];
        $adoptionRequest->valid_id = $validIdPath;
        $adoptionRequest->valid_id_with_owner = $validIdWithOwnerPath;
        $adoptionRequest->user_id = Auth::id(); // Set the user_id to the currently authenticated user's ID
        $adoptionRequest->status = 'Pending';
        $adoptionRequest->save();


        event(new AdoptionRequestSubmitted($adoptionRequest));

        // Log the success message for debugging
        Log::info('Adoption request submitted successfully for animal ID: ' . $animalprofile->id);

        return redirect()->route('user.home', ['animalprofile' => $animalprofile->id])
                         ->with('success', 'Adoption request submitted.');
    }

    /**
     * Display all submitted adoption requests for admins.
     */
   public function submitted()
    {
        // Fetch only adoption requests that have not been approved and are not rejected
        $adoption = AdoptionRequest::where('approved', false)
                    ->where('status', '!=', 'Rejected') // Exclude rejected requests
                    ->orderBy('created_at', 'desc')
                    ->paginate(1000); // Consider using a reasonable pagination size like 10 or 20

        // Return the view with the filtered adoption requests
        return view('admin.AdoptionRequested', compact('adoption'));
    }





    /**
     * Approve an adoption request.
     */
   public function approveAdoption($id)
    {
        $adoptionRequest = AdoptionRequest::findOrFail($id);
        $animalProfile = AnimalProfile::findOrFail($adoptionRequest->animal_id);

        // Mark the adoption request as approved
        $adoptionRequest->status = 'Approved';
        $adoptionRequest->approved = true; // Mark as approved
        $adoptionRequest->save();

        // Mark the animal as adopted
        $animalProfile->is_adopted = true;
        $animalProfile->save();
        event(new AnimalAdopted($animalProfile));

        event(new AdoptionRequestApproved($adoptionRequest));

        

        $adoptionRequest->user->notify(new AdoptionRequestApprove($adoptionRequest));

        return redirect()->route('admin.adoption.requests')->with('success', 'Adoption request approved.');
    }

    
    public function rejectAdoption(Request $request, $id)
{
    // Validate the incoming request to ensure a reason is provided
    $request->validate([
        'reason' => 'required|string|max:255',
    ]);

    // Find the adoption request
    $adoptionRequest = AdoptionRequest::findOrFail($id);
    
    // Find the related animal profile
    $animalProfile = AnimalProfile::findOrFail($adoptionRequest->animal_id);

    // Mark the adoption request as rejected
    $adoptionRequest->status = 'Rejected';
    $adoptionRequest->approved = false; // Mark as not approved

    // Add the rejection reason
    $adoptionRequest->reason = $request->input('reason');

    // Store the admin who rejected the request
    $adoptionRequest->admin_id = auth()->user()->id; // Assuming you're using Laravel's auth system

    // Save the changes to the adoption request
    $adoptionRequest->save();

    // Mark the animal as available again
    $animalProfile->is_adopted = false;
    $animalProfile->save();

    // Trigger event for rejection
    event(new AdoptionRequestRejected($adoptionRequest));

    // Notify the user about the rejection
    $adoptionRequest->user->notify(new AdoptionRequestReject($adoptionRequest));

    // Redirect back with a success message
    return redirect()->route('admin.adoption.requests')->with('success', 'Adoption request rejected.');
}


public function rejectedForm()
{
    $rejectedRequests = AdoptionRequest::with('admin') // assuming you have a relationship defined
        ->where('status', 'Rejected')
        ->orderBy('created_at', 'desc')
        ->paginate(1000);

    \Log::info($rejectedRequests); // Log the data

    return view('admin.RejectedForm', compact('rejectedRequests'));
}



  public function setToVerifying(Request $request, $id)
    {
        $adoptionRequest = AdoptionRequest::findOrFail($id);
        $adoptionRequest->status = 'Verifying';
        $adoptionRequest->save();

        event(new AdoptionRequestVerify($adoptionRequest));

        // Send the notification to the user
        $adoptionRequest->user->notify(new AdoptionRequestVerifying($adoptionRequest));

        return redirect()->route('admin.adoption.requests', ['id' => $id])
                         ->with('success', 'Adoption request is now under verification.');
    }


    public function complete(Request $request, $id)
    {
        $appointments = AdoptionRequest::findOrFail($id);
        $appointments->status = 'complete';
        $appointments->save();

        return redirect()->route('admin.adoption.requests', ['id' => $id])
        ->with('success', 'Adoption request is now under verification.');
    }

}
