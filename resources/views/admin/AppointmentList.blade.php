<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Free Bootstrap Admin Template : Binary Admin</title>
    <!-- BOOTSTRAP STYLES-->
    <link href="admin/assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="admin/assets/css/font-awesome.css" rel="stylesheet" />
     <!-- MORRIS CHART STYLES-->
    <link href="admin/assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="admin/assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

   <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }
        h2, h4 {
            text-align: center;
        }
        #calendar {
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }

        .highlighted-date {
            background-color: #ffeb3b !important; /* Change this to your preferred color */
            border-radius: 5px;
            color: #000;
        }
        
        /* Modal Styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;

        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

         #calendar {
            margin: 20px auto;
            height: 5in; /* Set calendar height to 5 inches */
            width: 6in; /* Set calendar width to 6 inches */
        }
    </style>

     <!-- jQuery and Pusher -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    <!-- Toastify -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    @include('admin.ScriptAnimalList')


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
                <a class="navbar-brand" href="index.html">Binary admin</a> 
            </div>
  <div style="color: white; padding: 15px 50px 5px 50px; float: right; font-size: 16px;">

                @if(Route::has('login'))

                @auth
                <x-app-layout>
    
                </x-app-layout>
                @else
                
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Login</a>
                <a href="{{ route('register') }}" class="btn btn-success btn-sm">Register</a>

                @endauth

                @endif
              
            
 </div>
        </nav>   
           <!-- /. NAV TOP  -->
                <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                <li class="text-center">
                    <img src="admin/assets/img/find_user.png" class="user-image img-responsive"/>
                    </li>
                
                    
                    <li>
                        <a href="{{url('home')}}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url('users') }}">Users List</a>
                    </li>

                    <li>
                        <a href="{{ url('animal-profiles') }}">Animal List</a>
                    </li>   

                     <li>
                        <a href="{{ url('adoption-requests') }}">Adoption Request</a>
                    </li>   

                    <li>
                        <a href="{{ url('reports') }}">Animal Report</a>
                    </li> 

                    <li>
                        <a href="{{ url('approved-requests') }}">Set Meeting</a>
                    </li>
                    <li>
                        <a href="{{ url('admin-messenger') }}">Messages</a>
                    </li>

                    <li>
                        <a class="active-menu" href="{{ url('appointments') }}">Meeting Scheduled</a>
                    </li>
                    <li>
                        <a href="{{ url('rejected-Form') }}">Rejected Adoption Form</a>
                    </li>

                    <li>
                        <a href="{{ url('rejected') }}">Rejected Report Form</a>
                    </li>

                   
                </ul>
               
            </div>
            
        </nav>  
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
                             
                 <!-- /. ROW  -->
<div class="container">
    <h2 style="color:Red; font-size: 20px;">Scheduled Meetings</h2>

    <div id="calendar"></div> <!-- Calendar placeholder -->

    <!-- Button to show all appointments -->
    <div class="">
        <button id="show-all-appointments" style="margin: 20px; padding: 10px 20px; border: solid, 2px, black;">Show All Meetigs</button>
    </div>

    <div class="mt-4">
        <h4 id="selected-date-heading" style="margin-bottom:10px; font-size:20px;">Meetings for <span id="selected-date">All Dates</span></h4>
        <table class="table table-striped" id="appointments-table">
            <thead>
                <tr>
                    <th>Meeting Date</th>
                    <th>Adopter Name</th>
                    <th>Animal Name</th>
                    <th>Update Schedule</th>
                    <th>Video Call Meeting</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($appointments as $appointment)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($appointment->meeting_date)->format('d-m-Y H:i') }}</td>
                        <td>{{ $appointment->adoptionRequest->user->name ?? 'N/A' }}</td>
                        <td>{{ $appointment->adoptionRequest->animalProfile->name ?? 'N/A' }}</td>
                        <td>
                            <!-- Update button that triggers the modal -->
                            <button class="btn btn-warning update-schedule-btn" data-id="{{ $appointment->id }}" data-date="{{ $appointment->meeting_date }}">Update</button>
                        </td>

                        <td>@include('admin.VideoCall')</td>
                        <td>
                            <form action="{{ route('admin.adoption.complete', ['id' => $appointment->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    Complete Verification
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <br><br><br><br><br>

        <table class="table table-striped" id="appointments-table">
            <thead>
                <tr>
                    <th>Meeting Date</th>
                    <th>Reporter Name</th>
                    
                    <th>Update Schedule</th>
                    <th>Video Call Meeting</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($appointmentss as $appointments)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($appointment->meeting_date)->format('d-m-Y H:i') }}</td>
                        <td>{{ $appointments->animalAbuseReport->user->name ?? 'N/A' }}</td>
                        
                        <td>
                            <!-- Update button that triggers the modal -->
                            <button class="btn btn-warning update-schedule-btn" data-id="{{ $appointments->id }}" data-date="{{ $appointments->meeting_date }}">Update</button>
                        </td>

                        <td>@include('admin.VideoCall')</td>
                        <td>
                            <form action="{{ route('admin.abuses.complete', ['id' => $appointment->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    Complete Verification
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Update Schedule Modal -->
<div id="updateScheduleModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Update Meeting Schedule</h2>
        <form id="updateScheduleForm">
            @csrf
            <input type="hidden" name="meeting_id" id="meeting_id">
            <!-- Ensure this is correctly named --> 
            <div class="form-group">
                <label for="new_meeting_date">New Meeting Date and Time:</label>
                <input type="datetime-local" name="meeting_date" id="new_meeting_date" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Schedule</button>
        </form>
    </div>
</div>




             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
        </div>
     <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="admin/assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="admin/assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="admin/assets/js/jquery.metisMenu.js"></script>
     <!-- MORRIS CHART SCRIPTS -->
     <script src="admin/assets/js/morris/raphael-2.1.0.min.js"></script>
    <script src="admin/assets/js/morris/morris.js"></script>
      <!-- CUSTOM SCRIPTS -->
    <script src="admin/assets/js/custom.js"></script>
    <!-- jQuery (required for FullCalendar AJAX operations) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

 @include('admin.ScriptAppointmentList')
    
   
</body>
</html>
















































