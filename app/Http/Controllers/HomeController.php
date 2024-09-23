<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AdoptionRequest;
use App\Models\AnimalProfile;
use App\Models\AnimalAbuseReport;
use App\Models\Meeting;
use Carbon\Carbon;


class HomeController extends Controller
{
    /**
     * Display the appropriate homepage based on user type.
     */
    public function userHomepage()
    {
        $user = Auth::user();

        if ($user->usertype === 'user') {
            // Fetch animal profiles
            $animalProfiles = AnimalProfile::where('is_adopted', false)
                ->orderBy('created_at', 'desc')
                ->paginate(1000);

            // Fetch user notifications
            $notifications = $user->notifications;

            // Pass the variables to the view
            return view('user.home', compact('animalProfiles', 'notifications'));
        } else {
            $totalAnimals = AnimalProfile::where('is_adopted', false)->count();
            $pendingAdoptions = AdoptionRequest::whereIn('status', ['pending', 'approved', 'Verifying'])->count();
            $pendingAbuses = AnimalAbuseReport::where('status', 'pending')->count();
            $upcomingMeetings = Meeting::where('meeting_date', '>', Carbon::now())->count();

            $newAdoptionRequests = AdoptionRequest::where('status', 'pending')->count();

            // unread message
            //  $unreadMessages = Message::where('read', false)->count();
        
            $upcomingMeetings = Meeting::where('meeting_date', '>', Carbon::now())->count();

            $recentAdoptions = AdoptionRequest::with('animal') // Assuming there is a relationship to animals
            ->latest()->take(3)->get();

            // Fetching the latest 5 animal abuse reports
            $recentAbuseReports = AnimalAbuseReport::latest()->take(3)->get();

            // Fetching the latest 5 added animals
            $recentAnimals = AnimalProfile::latest()->take(3)->get();

            // Fetching the latest 5 messages
            // $recentMessages = Message::latest()->take(5)->get();
            $adoptionData = AdoptionRequest::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                                    ->where('status', 'complete') // Assuming you track the status of the request
                                    ->groupBy('year', 'month')
                                    ->orderBy('year', 'asc')
                                    ->orderBy('month', 'asc')
                                    ->get();
    
            // Process the data for the chart (e.g., Jan -> Dec)
            $adoptionRate = [];
            $months = [];
            foreach ($adoptionData as $data) {
                $adoptionRate[] = $data->count;
                $months[] = Carbon::create()->month($data->month)->format('F'); // Month name
            }

            $monthss = [
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ];
    
            // Initialize arrays for intake and adoption data
            $intakeData = array_fill(0, 12, 0); // Start with 0 for each month
            $adoptionData = array_fill(0, 12, 0); // Start with 0 for each month
    
            // Loop through the months to get intake and adoption counts
            foreach ($months as $index => $month) {
                // Get month number (1-12)
                $monthNumber = $index + 1;
    
                // Fetch intake count for the month
                $intakeData[$index] = AnimalProfile::whereMonth('created_at', $monthNumber)->count();
    
                // Fetch adoption count for the month
                $adoptionData[$index] = AdoptionRequest::whereMonth('created_at', $monthNumber)->count();
            }


            return view('admin.home', compact('totalAnimals', 'pendingAdoptions', 
            'pendingAbuses', 'upcomingMeetings', 'newAdoptionRequests', 'upcomingMeetings',
            'recentAdoptions', 'recentAbuseReports', 'recentAnimals', 'adoptionRate', 'months',
            'monthss', 'intakeData', 'adoptionData'));
        }
    }

    
    /**
     * Show a specific animal profile.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = Auth::user();
        $notifications = $user->notifications;

        // Fetch the animal profile by ID
        $animal = AnimalProfile::findOrFail($id);

        // Pass the animal profile and notifications to the view
        return view('user.animalProfile', compact('animal', 'notifications'));
    }

    public function profile()
    {
        $user = auth()->user();
        $adoptionRequests = AdoptionRequest::where('user_id', $user->id)->get();
        $abuseReports = AnimalAbuseReport::where('user_id', $user->id)->get();

        return view('user.Profile', compact('user', 'adoptionRequests', 'abuseReports'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $animalProfiles = AnimalProfile::where('name', 'LIKE', "%{$query}%")
        ->orWhere('species', 'LIKE', "%{$query}%")
        ->paginate(15);
    

        return view('user.home', compact('animalProfiles'));
    }


}
