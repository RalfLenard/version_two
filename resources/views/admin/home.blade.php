<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
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

    <!-- pusher link -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.11.1/toastify.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.11.1/toastify.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    @include('admin.ScriptHome')
</head>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">Noah's Ark Admin</a>
            </div>
            <div style="color: white; padding: 15px 50px 5px 50px; float: right; font-size: 16px;">
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
        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="text-center">
                        <img src="admin/assets/img/find_user.png" class="user-image img-responsive"/>
                    </li>
                    <li><a class="active-menu" href="{{url('home')}}"> Dashboard</a></li>
                    <li><a href="{{ url('users') }}">Users List</a></li>
                    <li><a href="{{ url('animal-profiles') }}">Animal List</a></li>   
                    <li><a href="{{ url('adoption-requests') }}">Adoption Requests</a></li>
                    <li><a href="{{ url('reports') }}">Animal Reports</a></li> 
                    <li><a href="{{ url('approved-requests') }}">Set Meeting</a></li>
                    <li><a href="{{ url('admin-messenger') }}">Messages</a></li>
                    <li><a href="{{ url('appointments') }}">Meeting Scheduled</a></li>
                    <li><a href="{{ url('rejected-Form') }}">Rejected Adoption Forms</a></li>
                    <li><a href="{{ url('rejected') }}">Rejected Report Forms</a></li>
                </ul>
            </div>
        </nav>  
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div id="page-inner">
                <!-- Top Row: Statistics Cards -->
                <div class="row">
                    <div class="col-md-2">
                        <div class="panel panel-primary text-center no-boder bg-color-green">
                            <div class="panel-body">
                                <i class="fa fa-paw fa-5x"></i>
                                <h3>{{ $totalAnimals }}</h3> 
                            </div>
                            <div style="color:black;" class="panel-footer">Total Animals</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="panel panel-primary text-center no-boder bg-color-blue">
                            <div class="panel-body">
                                <i class="fa fa-check-square-o fa-5x"></i>
                                <h3>{{ $pendingAdoptions }}</h3> 
                            </div>
                            <div style="color:black;" class="panel-footer">Pending Adoptions</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="panel panel-primary text-center no-boder bg-color-red">
                            <div class="panel-body">
                                <i class="fa fa-envelope-o fa-5x"></i>
                                <h3>5</h3>
                            </div>
                            <div style="color:black;" class="panel-footer">Unread Messages</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="panel panel-primary text-center no-boder bg-color-yellow">
                            <div class="panel-body">
                                <i class="fa fa-exclamation-triangle fa-5x"></i>
                                <h3>{{ $pendingAbuses }}</h3>
                            </div>
                            <div style="color:black;" class="panel-footer">Abuse Reports</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="panel panel-primary text-center no-boder bg-color-purple">
                            <div class="panel-body">
                                <i class="fa fa-calendar fa-5x"></i>
                                <h3>{{ $upcomingMeetings }}</h3>
                            </div>
                            <div style="color:black;" class="panel-footer">Upcoming Meetings</div>
                        </div>
                    </div>
                </div>
                <!-- Middle Section: Recent Activity Feed -->
                <div class="row">
                    <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">Recent Activity</div>
                        <div class="panel-body">
                            <ul class="list-group">
                                @foreach($recentAdoptions as $adoption)
                                    <li class="list-group-item">
                                        Adoption request for {{ $adoption->animal->name }} 
                                        <span class="badge">{{ $adoption->created_at->diffForHumans() }}</span>
                                    </li>
                                @endforeach
                                
                                @foreach($recentAbuseReports as $report)
                                    <li class="list-group-item">
                                        Animal abuse report filed
                                        <span class="badge">{{ $report->created_at->diffForHumans() }}</span>
                                    </li>
                                @endforeach

                                @foreach($recentAnimals as $animal)
                                    <li class="list-group-item">
                                        New animal added: {{ $animal->name }} the {{ $animal->species }}
                                        <span class="badge">{{ $animal->created_at->diffForHumans() }}</span>
                                    </li>
                                @endforeach

                            
                            </ul>
                        </div>
                    </div>
                    </div>
                    <!-- Right Sidebar: Notifications Panel and Quick Actions -->
                    <div class="col-md-4">
                    <div class="panel panel-default">
                            <div class="panel-heading">Notifications</div>
                            <div class="panel-body">
                                <p>{{ $newAdoptionRequests }} New Adoption Requests</p>
                                <p> 1 Unread Messages</p>
                                <p>{{ $upcomingMeetings }} Upcoming Meetings</p>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">Quick Actions</div>
                            <div class="panel-body">
                                <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#uploadModal">Add New Animal</button>
                                <button class="btn btn-primary btn-block">View Pending Adoptions</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Bottom Section: Graphical Reports/Charts -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">Adoption Rate</div>
                            <div class="panel-body">
                                <canvas id="adoption-rate-chart" style="height: 250px;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">Animal Intake vs. Adoptions</div>
                        <div class="panel-body">
                            <canvas id="intake-vs-adoption-chart" style="height: 250px;"></canvas>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <!-- /. PAGE INNER -->
        </div>
        <!-- /. PAGE WRAPPER -->
    </div>
    <!-- /. WRAPPER -->

                                 <!-- Upload Modal -->
                                <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                <h4 class="modal-title">Upload Animal Profile</h4>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ url('animal-profiles/store') }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="name">Name</label>
                                                        <input type="text" class="form-control" name="name" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="species">Species</label>
                                                        <input type="text" class="form-control" name="species" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="description">Description</label>
                                                        <textarea class="form-control" name="description" required></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="age">Age</label>
                                                        <input type="text" class="form-control" name="age" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="medical_records">Medical Records</label>
                                                        <textarea class="form-control" name="medical_records" required></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="profile_picture">Profile Picture</label>
                                                        <input type="file" class="form-control" name="profile_picture" required>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success">Upload</button>
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

    <!-- SCRIPTS -->
    <script src="admin/assets/js/jquery-1.10.2.js"></script>
    <script src="admin/assets/js/bootstrap.min.js"></script>
    <script src="admin/assets/js/jquery.metisMenu.js"></script>
    <script src="admin/assets/js/morris/raphael-2.1.0.min.js"></script>
    <script src="admin/assets/js/morris/morris.js"></script>
    <script src="admin/assets/js/custom.js"></script>
</body>
</html>
