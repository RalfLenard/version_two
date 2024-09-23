<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>

        @vite([ 'resources/js/app.js'])

    </head>
    <body class="antialiased">
        
    <div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Join Meeting</h3>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('validateMeeting') }}">
                        @csrf
                        <!-- Meeting ID Input -->
                        <div class="form-group">
                            <label for="meetingId">Meeting ID</label>
                            <input type="text" name="meetingId" id="meetingId" class="form-control" placeholder="Enter Meeting ID" required>
                            <!-- Display validation error if meeting does not exist -->
                            @if ($errors->has('meetingId'))
                            <small class="form-text text-danger">{{ $errors->first('meetingId') }}</small>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">Join Meeting</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
    </body>
</html>


