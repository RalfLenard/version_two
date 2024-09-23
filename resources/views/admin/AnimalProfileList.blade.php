<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Panel - Animal Profiles</title>
    
    <!-- BOOTSTRAP STYLES-->
    <link href="/admin/assets/css/bootstrap.css" rel="stylesheet" />
    
    <!-- FONTAWESOME STYLES-->
    <link href="/admin/assets/css/font-awesome.css" rel="stylesheet" />
    
    <!-- CUSTOM STYLES-->
    <link href="/admin/assets/css/custom.css" rel="stylesheet" />
    
    <!-- GOOGLE FONTS-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    
    <!-- Additional Style for Enhancements -->
    <style>
        .navbar-cls-top {
            background-color: #2a2a2a;
            color: #fff;
        }
        .sidebar-collapse ul {
            background-color: #2c3e50;
        }
        .sidebar-collapse ul li a {
            color: #fff;
        }
        .panel-heading button {
            margin-bottom: 10px;
        }
        .panel {
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        }
        .table thead th {
            background-color: #34495e;
            color: white;
        }
        .table tbody tr:hover {
            background-color: #f1f1f1;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .modal-header {
            background-color: #34495e;
            color: white;
        }
        .modal-footer .btn-danger {
            background-color: #e74c3c;
        }
        .btn-sm {
            margin-right: 5px;
        }
        .card-img-top {
            border-radius: 5px;
        }
        .btn-primary, .btn-warning, .btn-danger {
            transition: background-color 0.3s ease-in-out;
        }
        .btn-primary:hover {
            background-color: #3498db;
        }
        .btn-warning:hover {
            background-color: #f39c12;
        }
        .btn-danger:hover {
            background-color: #c0392b;
        }
    </style>

    <!-- Scripts for jQuery, Pusher, and Toastify -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>
<body>
    <div id="wrapper">
        <!-- TOP NAVBAR -->
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">Admin Panel</a>
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
        
        <!-- SIDEBAR NAVIGATION -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="text-center">
                        <img src="/admin/assets/img/find_user.png" class="user-image img-responsive"/>
                    </li>
                    <li><a href="{{url('home')}}">Dashboard</a></li>
                    <li><a href="{{ url('users') }}">Users List</a></li>
                    <li><a class="active-menu" href="{{ url('animal-profiles') }}">Animal List</a></li>
                    <li><a href="{{ url('adoption-requests') }}">Adoption Requests</a></li>
                    <li><a href="{{ url('reports') }}">Animal Report</a></li>
                    <li><a href="{{ url('approved-requests') }}">Set Meeting</a></li>
                    <li><a href="{{ url('admin-messenger') }}">Messages</a></li>
                    <li><a href="{{ url('appointments') }}">Meeting Scheduled</a></li>
                    <li><a href="{{ url('rejected-Form') }}">Rejected Adoption Forms</a></li>
                    <li><a href="{{ url('rejected') }}">Rejected Report Forms</a></li>
                </ul>
            </div>
        </nav>

        <!-- MAIN PAGE CONTENT -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">Animal Profiles</div>
                            <div class="panel-body">
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#uploadModal">Add Animal</button>
                                
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Species</th>
                                                <th>Description</th>
                                                <th>Age</th>
                                                <th>Medical History</th>
                                                <th>Profile Picture</th>
                                                <th>Update</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($animalProfiles as $animal)
                                                <tr class="odd gradeX">
                                                    <td>{{ $animal->name }}</td>
                                                    <td>{{ $animal->species }}</td>
                                                    <td>{{ $animal->description }}</td>
                                                    <td>{{ $animal->age }}</td>
                                                    <td>{{ $animal->medical_records }}</td>
                                                    <td>
                                                        <img height="80px" width="100px" src="{{ Storage::url($animal->profile_picture) }}" class="card-img-top" alt="{{ $animal->name }}">
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#updateModal{{ $animal->id }}">Update</button>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal{{ $animal->id }}">Delete</button>
                                                    </td>
                                                </tr>

                                                <!-- Update Modal -->
                                                <div class="modal fade" id="updateModal{{ $animal->id }}" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                                <h4 class="modal-title">Update Animal Profile</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="{{ url('update-animal', $animal->id) }}" method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <label for="name">Name</label>
                                                                        <input type="text" class="form-control" name="name" value="{{ $animal->name }}" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="species">Species</label>
                                                                        <input type="text" class="form-control" name="species" value="{{ $animal->species }}" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="description">Description</label>
                                                                        <textarea class="form-control" name="description" required>{{ $animal->description }}</textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="age">Age</label>
                                                                        <input type="text" class="form-control" name="age" value="{{ $animal->age }}" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="medical_records">Medical Records</label>
                                                                        <textarea class="form-control" name="medical_records" required>{{ $animal->medical_records }}</textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="profile_picture">Profile Picture</label>
                                                                        <input type="file" class="form-control" name="profile_picture">
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit" class="btn btn-success">Save Changes</button>
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteModal{{ $animal->id }}" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                                <h4 class="modal-title">Delete Animal</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Are you sure you want to delete this animal?</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form action="{{ url('delete-animal', $animal->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('POST')
                                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

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

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BOOTSTRAP SCRIPTS -->
    <script src="/admin/assets/js/bootstrap.min.js"></script>
</body>
</html>
