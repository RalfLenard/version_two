<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Middleware;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AnimalProfileController;
use App\Http\Controllers\AnimalAbuseReportController;
use App\Http\Controllers\AdoptionRequestController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PusherAuthController;
use App\Http\Controllers\UserManagingController;

use App\Events\AnimalProfileCreated;

// Route for the admin homepage

Route::get('/profile', [HomeController::class, 'profile'])->middleware('auth')->name('profile.show');


// User homepage
Route::get('/home', [HomeController::class, 'userHomepage'])->name('user.home')->middleware('auth');

Route::get('/animals/search', [HomeController::class, 'search'])->name('animals.search')->middleware('auth');;


// Animal profile uploading
// Route to store a newly created animal profile (User)
Route::post('/animal-profiles/store', [AnimalProfileController::class, 'store'])->name('animal-profiles.store');

// Route to list all animal profiles (Admin)
Route::get('/animal-profiles', [AnimalProfileController::class, 'list'])->name('admin.animal-profile-list')->middleware('admin');

// Admin routes for animal profile management
Route::middleware(['admin'])->group(function () {
    // Delete animal and update
    Route::post('/update-animal/{id}', [AnimalProfileController::class, 'update']);
    Route::post('/delete-animal/{id}', [AnimalProfileController::class, 'destroy']);
});

// Animal view profile
Route::get('/animals/{id}', [HomeController::class, 'show'])->name('animals.show');

// Adoption routes
// User adoption request
Route::get('/adopt/{animalprofile}/request', [AdoptionRequestController::class, 'showAdoptionForm'])->name('adopt.show');
Route::post('/adopt/{id}/request', [AdoptionRequestController::class, 'submitAdoptionRequest'])->name('adoption.submit');

// Admin adoption request management
Route::middleware(['admin'])->group(function () {
    Route::get('/adoption-requests', [AdoptionRequestController::class, 'submitted'])->name('admin.adoption.requests');
    Route::post('/adoption-request/{id}/approve', [AdoptionRequestController::class, 'approveAdoption'])->name('admin.adoption.approve');
    Route::post('/adoption/{id}/reject', [AdoptionRequestController::class, 'rejectAdoption'])->name('admin.adoption.reject');
    Route::get('/rejected-Form', [AdoptionRequestController::class, 'rejectedForm']);
    Route::post('/adoption/requests/{id}/verify', [AdoptionRequestController::class, 'setToVerifying'])->name('admin.adoption.verify');
    Route::post('/adoption/requests/{id}/complete', [AdoptionRequestController::class, 'complete'])->name('admin.adoption.complete');
    
});

// Animal abuse report routes
// User report creation
Route::get('/report/abuse', [AnimalAbuseReportController::class, 'create'])->name('report.abuses.form');
Route::post('/report/abuse', [AnimalAbuseReportController::class, 'store'])->name('report.abuses.submit');

// Admin report management
Route::middleware(['admin'])->group(function () {
    Route::get('/reports', [AnimalAbuseReportController::class, 'index'])->name('admin.abuses.requests');
    Route::post('/reports/{id}/verify', [AnimalAbuseReportController::class, 'setToVerifying'])->name('admin.abuses.verify');
    Route::post('/reports/{id}/approve', [AnimalAbuseReportController::class, 'approveAbuse'])->name('admin.abuses.approve');
    Route::post('/reject/{id}', [AnimalAbuseReportController::class, 'rejectAbuse'])->name('admin.abuses.reject');
    Route::get('/rejected', [AnimalAbuseReportController::class, 'rejectedForm'])->name('admin.abuses.rejected');
    Route::post('/reports/{id}/complete', [AnimalAbuseReportController::class, 'complete'])->name('admin.abuses.complete');


});

// Meeting routes (Admin)
Route::middleware(['admin'])->group(function () {
    Route::get('/approved-requests', [MeetingController::class, 'showApprovedAdoptionRequests'])->name('admin.approved.requests');
    Route::get('/schedule-meeting/{id}', [MeetingController::class, 'showScheduleMeetingForm'])->name('admin.schedule.meeting.form');
    Route::post('/schedule-meeting', [MeetingController::class, 'scheduleMeeting'])->name('admin.schedule.meeting');
    Route::get('/appointments', [MeetingController::class, 'viewAppointmentList'])->name('admin.appointments.list');
    Route::get('/appointments/by-date', [MeetingController::class, 'getAppointmentsByDate'])->name('admin.appointments.byDate');
    Route::get('/appointments/all', [MeetingController::class, 'getAllAppointments'])->name('admin.appointments.all');
    Route::post('/meeting/update', [MeetingController::class, 'update'])->name('admin.meeting.update');
});

// User managing routes (Admin)
Route::middleware(['admin'])->group(function () {
    Route::get('/users', [UserManagingController::class, 'index'])->name('users.index');
    Route::post('/users/{id}/make-admin', [UserManagingController::class, 'makeAdmin'])->name('users.makeAdmin');
    Route::delete('/users/{id}', [UserManagingController::class, 'destroy'])->name('users.destroy');
});

// User notification
Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

// Message routes
// Admin messaging
Route::middleware(['admin'])->group(function () {
    Route::get('/admin-messenger', [MessageController::class, 'adminMessage'])->name('admin.Message');
    Route::get('/messenger/{id}', [MessageController::class, 'chatWithAdmin']);
});

// User messaging
Route::get('/user-messenger', [MessageController::class, 'userMessage'])->name('user.Message');
Route::get('/messenger/{id}', [MessageController::class, 'chatWithUser']);

// Video call routes
Route::post("/createMeeting", [MeetingController::class, 'createMeeting'])->name("createMeeting");
Route::post("/validateMeeting", [MeetingController::class, 'validateMeeting'])->name("validateMeeting");

// Meeting view for admin
Route::middleware(['admin'])->get("/meeting/{meetingId}", function($meetingId) {
    $METERED_DOMAIN = env('METERED_DOMAIN');
    $userName = auth()->user()->name; // Get the authenticated user's name

    return view('admin.VideoCallMeeting', [
        'METERED_DOMAIN' => $METERED_DOMAIN,
        'MEETING_ID' => $meetingId,
        'USER_NAME' => $userName // Pass the user's name to the view
    ]);
});

// Meeting view for user
Route::middleware(['auth'])->get("/meeting/{meetingId}", function($meetingId) {
    $METERED_DOMAIN = env('METERED_DOMAIN');
    $userName = auth()->user()->name; // Get the authenticated user's name

    return view('user.VideoCallMeeting', [
        'METERED_DOMAIN' => $METERED_DOMAIN,
        'MEETING_ID' => $meetingId,
        'USER_NAME' => $userName // Pass the user's name to the view
    ]);
});

// Admin video call view
Route::middleware(['admin'])->get('/Admin-Video-call', function () {
    return view('admin.VideoCall');
});

// User video call view
Route::middleware(['auth'])->get('/User-Video-call', function () {
    return view('user.VideoC');
})->name('user.video-call');

// Welcome page
Route::get('/', function () {
    return view('welcome');
});




