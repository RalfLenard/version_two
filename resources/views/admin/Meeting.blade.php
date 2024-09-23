<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - Approved Requests</title>
    <!-- BOOTSTRAP STYLES -->
    <link href="admin/assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES -->
    <link href="admin/assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLES -->
    <link href="admin/assets/css/custom.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
</head>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">Binary Admin</a> 
            </div>
            <div style="color: white; padding: 15px 50px; float: right; font-size: 16px;">
                @if(Route::has('login'))
                    @auth
                        <x-app-layout></x-app-layout>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-success btn-sm">Register</a>
                    @endauth
                @endif
            </div>
        </nav>   
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="text-center">
                        <img src="admin/assets/img/find_user.png" class="user-image img-responsive"/>
                    </li>
                    <li><a href="{{url('home')}}"> Dashboard</a></li>
                    <li><a href="{{ url('users') }}">Users List</a></li>
                    <li><a href="{{ url('animal-profiles') }}">Animal List</a></li>   
                    <li><a href="{{ url('adoption-requests') }}">Adoption Request</a></li>   
                    <li><a href="{{ url('reports') }}">Animal Report</a></li> 
                    <li><a class="active-menu" href="{{ url('approved-requests') }}">Set Meeting</a></li>
                    <li><a href="{{ url('admin-messenger') }}">Messages</a></li>
                    <li><a href="{{ url('appointments') }}">Meeting Scheduled</a></li>
                    <li><a href="{{ url('rejected-Form') }}">Rejected Form</a></li>
                    <li><a href="{{ url('rejected') }}">Rejected Report Form</a></li>
                </ul>
            </div>
        </nav>  
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="container">
                    <h2 class="mt-4">Approved Adoption Requests</h2>
                    <table class="table table-striped table-bordered mt-3">
                        <thead>
                            <tr>
                                <th>Adopter Name</th>
                                <th>Animal Name</th>
                                <th>Set Meeting</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($approvedRequests as $request)
                                <tr>
                                    <td>{{ $request->user->name ?? 'N/A' }}</td>
                                    <td>{{ $request->animalProfile->name ?? 'N/A' }}</td>
                                    <td>
                                        <form action="{{ route('admin.schedule.meeting') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="adoption_request_id" value="{{ $request->id }}">
                                            <input type="hidden" name="user_id" value="{{ $request->user_id }}">
                                            <input type="text" name="meeting_date" id="meeting_date_{{ $request->id }}" class="form-control datetimepicker" required>
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-success">Schedule Meeting</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No approved adoption requests found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <h2 class="mt-4">Approved Report Requests</h2>
                    <table class="table table-striped table-bordered mt-3">
                        <thead>
                            <tr>
                                <th>Reporter Name</th>
                                <th>Set Meeting</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($approvedRequestss as $requests)
                                <tr>
                                    <td>{{ $requests->user->name ?? 'N/A' }}</td>
                                    <td>
                                        <form action="{{ route('admin.schedule.meeting') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="adoption_request_id" value="{{ $requests->id }}">
                                            <input type="hidden" name="user_id" value="{{ $requests->user_id }}">
                                            <input type="text" name="meeting_date" id="meeting_date_{{ $requests->id }}" class="form-control datetimepicker" required>
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-success">Schedule Meeting</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No approved report requests found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="admin/assets/js/jquery-1.10.2.js"></script>
    <script src="admin/assets/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Initialize Flatpickr for all datetimepicker inputs
        document.addEventListener('DOMContentLoaded', function () {
            flatpickr('.datetimepicker', {
                enableTime: true,
                dateFormat: "Y-m-d h:i K", // Format with AM/PM
                time_24hr: false
            });
        });
    </script>
</body>
</html>
