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
    <!-- CUSTOM STYLES-->
    <link href="admin/assets/css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <!-- TOASTIFY NOTIFICATIONS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.11.1/toastify.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.11.1/toastify.min.css">
    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- PUSHER -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    <style>
        /* Improved custom styles for better layout and UI */
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #343a40;
        }
        .navbar-brand {
            color: white;
        }
        .navbar-right {
            margin-right: 10px;
        }
        .navbar-right a {
            color: white;
            margin-left: 15px;
        }
        .sidebar-collapse ul li a {
            font-size: 14px;
            padding: 10px 20px;
            display: block;
            color: #fff;
        }
        .sidebar-collapse ul li a:hover {
            background-color: #6c757d;
            text-decoration: none;
        }
        .panel {
            border-radius: 0;
            margin-bottom: 30px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
        }
        .panel-body {
            padding: 20px;
        }
        .table thead {
            background-color: #343a40;
            color: white;
        }
        .table tbody tr:hover {
            background-color: #f1f1f1;
        }
        .btn {
            border-radius: 0;
        }
        .btn-primary, .btn-danger {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <!-- Navigation Bar -->
        <nav class="navbar navbar-default navbar-cls-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">Admin Panel</a>
            </div>
            <div class="navbar-right" style="padding: 15px 50px 5px;">
                @if(Route::has('login'))
                    @auth
                        <x-app-layout></x-app-layout>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-success">Register</a>
                    @endauth
                @endif
            </div>
        </nav>

        <!-- Sidebar -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="text-center">
                        <img src="admin/assets/img/find_user.png" class="user-image img-responsive" />
                    </li>
                    <li><a href="{{ url('home') }}"> Dashboard</a></li>
                    <li><a class="active-menu" href="{{ url('users') }}">Users List</a></li>
                    <li><a href="{{ url('animal-profiles') }}">Animal List</a></li>
                    <li><a href="{{ url('adoption-requests') }}">Adoption Request</a></li>
                    <li><a href="{{ url('reports') }}">Animal Report</a></li>
                    <li><a href="{{ url('approved-requests') }}">Set Meeting</a></li>
                    <li><a href="{{ url('admin-messenger') }}">Messages</a></li>
                    <li><a href="{{ url('appointments') }}">Meeting Scheduled</a></li>
                    <li><a href="{{ url('rejected-Form') }}">Rejected Adoption Form</a></li>
                    <li><a href="{{ url('rejected') }}">Rejected Report Form</a></li>
                </ul>
            </div>
        </nav>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3>User Management</h3>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>User Type</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($users as $user)
                                                <tr>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ $user->usertype }}</td>
                                                    <td>
                                                        @if($user->usertype !== 'admin')
                                                            <form action="{{ route('users.makeAdmin', $user->id) }}" method="POST" style="display:inline;">
                                                                @csrf
                                                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-user"></i> Make Admin</button>
                                                            </form>
                                                        @endif
                                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /. ROW -->
                <hr />
            </div>
            <!-- /. PAGE INNER -->
        </div>
        <!-- /. PAGE WRAPPER -->
    </div>

    <!-- SCRIPTS AT THE BOTTOM TO REDUCE LOAD TIME -->
    <script src="admin/assets/js/jquery-1.10.2.js"></script>
    <script src="admin/assets/js/bootstrap.min.js"></script>
    <script src="admin/assets/js/jquery.metisMenu.js"></script>
    <script src="admin/assets/js/morris/raphael-2.1.0.min.js"></script>
    <script src="admin/assets/js/morris/morris.js"></script>
    <script src="admin/assets/js/custom.js"></script>
</body>
</html>
