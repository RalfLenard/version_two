<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AnimalProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Events\AnimalProfileCreated;
use App\Events\AnimalProfileUpdated;
use App\Events\AnimalProfileDeleted;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AnimalProfileController extends Controller
{
    
   public function store(Request $request)
    {
        // Validate request data
        $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'profile_picture' => 'required|image',
            'age' => 'required|integer',
            'description' => 'nullable|string',
            'medical_records' => 'required|string|max:1000',
        ]);

        // Store profile picture and create the AnimalProfile
        $profilePicturePath = $request->file('profile_picture')->store('animal_profiles', 'public');

        $animal = AnimalProfile::create([
            'name' => $request->input('name'),
            'species' => $request->input('species'),
            // Store the relative path (without 'public/') for the Storage facade to generate the correct URL
            'profile_picture' => $profilePicturePath,
            'age' => $request->input('age'),
            'description' => $request->input('description'),
            'medical_records' => $request->medical_records,
        ]);

        // Dispatch the event
        event(new AnimalProfileCreated($animal));

        // Redirect back with success message
        return redirect()->back()->with('success', 'Animal profile created successfully.');
    }

    /**
     * Display a listing of animal profiles.
     */
    public function list()
    {
    $animalProfiles = AnimalProfile::where('is_adopted', false)  // Filter non-adopted animals
        ->latest('created_at')                                   // Order by 'created_at' in descending order
        ->paginate(1000);
        // Pass the profiles to the view
        return view('admin.AnimalProfileList', compact('animalProfiles'));
    }


   public function destroy($id)
{
    $animal = AnimalProfile::find($id);

    if ($animal) {
        $animal->delete();

        // Broadcast the event after deletion with the animal ID
        event(new AnimalProfileDeleted($id));

        return redirect()->back()->with('success', 'Animal profile deleted successfully.');
    }

    return redirect()->back()->with('error', 'Animal profile not found.');
}




        // update
     public function update(Request $request, $id)
    {
        $animal = AnimalProfile::find($id);

        // Update fields
        $animal->name = $request->input('name');
        $animal->species = $request->input('species');
        $animal->description = $request->input('description');
        $animal->age = $request->input('age');
        $animal->medical_records = $request->input('medical_records');

        // Handle profile picture update if provided
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($animal->profile_picture && Storage::disk('public')->exists($animal->profile_picture)) {
                Storage::disk('public')->delete($animal->profile_picture);
            }

            // Store new profile picture
            $profilePicturePath = $request->file('profile_picture')->store('animal_profiles', 'public');
            $animal->profile_picture = $profilePicturePath;
        }

        $animal->save();

        // Trigger event for real-time updates
        event(new AnimalProfileUpdated($animal));

        return redirect()->back()->with('success', 'Animal profile updated successfully.');
    }

   

}
