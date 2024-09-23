<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnimalAbuseReport;
use Illuminate\Support\Facades\Auth;
use App\Events\AnimalAbuseReportSubmitted;


use App\Notifications\AbuseVerify;
use App\Notifications\AbuseApprove;
use App\Notifications\AbuseReject;

use App\Events\AbusesVerify;
use App\Events\AbusesApprove;
use App\Events\AbusesReject;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AnimalAbuseReportController extends Controller
{
    public function create()
    {
    
        return view('user.AnimalAbuseReporting');
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'nullable|string',
            'photos1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photos2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photos3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photos4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photos5' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'videos1' => 'nullable|mimetypes:video/mp4,video/avi,video/mpeg|max:20480',
            'videos2' => 'nullable|mimetypes:video/mp4,video/avi,video/mpeg|max:20480',
            'videos3' => 'nullable|mimetypes:video/mp4,video/avi,video/mpeg|max:20480',
        ]);

        $photos = [];
        $videos = [];

        foreach (['photos1', 'photos2', 'photos3', 'photos4', 'photos5'] as $photo) {
            if ($request->hasFile($photo)) {
                $photos[$photo] = $request->file($photo)->store('photos', 'public');
            }
        }

        foreach (['videos1', 'videos2', 'videos3'] as $video) {
            if ($request->hasFile($video)) {
                $videos[$video] = $request->file($video)->store('videos', 'public');
            }
        }

        try {
            $report = AnimalAbuseReport::create([
                'user_id' => Auth::id(),
                'description' => $request->description,
                'photos1' => $photos['photos1'] ?? null,
                'photos2' => $photos['photos2'] ?? null,
                'photos3' => $photos['photos3'] ?? null,
                'photos4' => $photos['photos4'] ?? null,
                'photos5' => $photos['photos5'] ?? null,
                'videos1' => $videos['videos1'] ?? null,
                'videos2' => $videos['videos2'] ?? null,
                'videos3' => $videos['videos3'] ?? null,
                'status' => 'pending',
            ]);

            // Dispatch the event
            event(new AnimalAbuseReportSubmitted($report));

            return redirect()->route('report.abuses.form')->with('success', 'Report submitted successfully.');
        } catch (\Exception $e) {
            \Log::error('Animal Abuse Report Submission Failed: ' . $e->getMessage());
            return redirect()->route('report.abuses.form')->with('error', 'Failed to submit the report.');
        }
    }
    

    public function index()
    {
        $abuses = AnimalAbuseReport::where('approved', false)
                    ->where('status', '!=', 'Rejected') // Exclude rejected requests
                    ->orderBy('created_at', 'desc')
                    ->paginate(1000); 

        return view('admin.AnimalAbuseReporting', compact('abuses'));
    }






    public function setToVerifying(Request $request, $id)
    {
        $abuses = AnimalAbuseReport::findOrFail($id);
        $abuses->status = 'Verifying';
        $abuses->save();

        event(new AbusesVerify($abuses));

        $abuses->user->notify(new AbuseVerify($abuses));

        return redirect()->route('admin.abuses.requests', ['id' => $id])
                         ->with('success', 'The abuse report is now under verification.');
    }

    public function rejectAbuse(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $abuses = AnimalAbuseReport::findOrFail($id);
        $abuses->status = 'Rejected';
        $abuses->approved = false; 
        $abuses->reason = $request->input('reason');
        $abuses->admin_id = auth()->user()->id;
        $abuses->save();

        $abuses->user->notify(new AbuseReject($abuses));

        return redirect()->route('admin.abuses.requests')
                         ->with('success', 'Abuse report rejected successfully.');
    }

    public function rejectedForm()
    {
        $rejectedRequests = AnimalAbuseReport::with('admin')
            ->where('status', 'Rejected')
            ->orderBy('created_at', 'desc')
            ->paginate(1000);

        return view('admin.RejectedAbuseReport', compact('rejectedRequests'));
    }

    public function approveAbuse($id)
    {
        $abuses = AnimalAbuseReport::findOrFail($id);
        $abuses->status = 'Approved';
        $abuses->approved = true;
        $abuses->save();

        event(new AbusesVerify($abuses));
        
        $abuses->user->notify(new AbuseApprove($abuses));
       

        return redirect()->route('admin.abuses.requests')
                         ->with('success', 'Abuse report approved successfully.');
    }

    public function complete(Request $request, $id)
    {
        // Fetch the appointment using findOrFail to ensure a single instance is fetched
        $appointments = AnimalAbuseReport::findOrFail($id); 

        // Update the status
        $appointments->status = 'complete';
        $appointments->save();

        // Redirect with success message
        return redirect()->route('admin.abuses.requests', ['id' => $id])
            ->with('success', 'Adoption request is now under verification.');
    }

    
}
