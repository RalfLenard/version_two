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

     <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
     <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

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
        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="text-center">
                        <img src="/admin/assets/img/find_user.png" class="user-image img-responsive"/>
                    </li>
                    <li>
                        <a href="{{url('home')}}"> Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url('users') }}">Users List</a>
                    </li>
                    <li>
                        <a href="{{ url('animal-profiles') }}"> Animal List</a>
                    </li>
                    <li>
                        <a href="{{ url('adoption-requests') }}"> Adoption Request</a>
                    </li>
                    <li>
                        <a href="{{ url('reports') }}"> Animal Report</a>
                    </li>
                    <li>
                        <a href="{{ url('approved-requests') }}"> Set Meeting</a>
                    </li>
                    <li>
                        <a href="{{ url('admin-messenger') }}">Messages</a>
                    </li>
                    <li>
                        <a href="{{ url('appointments') }}"> Meeting Scheduled</a>
                    </li>
                    <li>
                        <a href="{{ url('rejected-Form') }}">Rejected Adoption Form</a>
                    </li>

                    <li>
                        <a class="active-menu" href="{{ url('rejected') }}">Rejected Report Form</a>
                    </li>
                </ul>
            </div>
        </nav>  
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">Rejected Form</div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>Description</th>
                                                <th>Photo 1</th>
                                                <th>Photo 2</th>
                                                <th>Photo 3</th>
                                                <th>Photo 4</th>
                                                <th>Photo 5</th>
                                                <th>Video 1</th>
                                                <th>Video 2</th>
                                                <th>Video 3</th>
                                                <th>Reason</th>
                                                <th>Admin</th>
                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($rejectedRequests as $abuse)
                                                <tr class="odd gradeX">
                                                <td>{{ $abuse->description }}</td>
                                            <td>
                                                @if($abuse->photos1)
                                                    <img width="40px" height="40px" src="{{ Storage::url($abuse->photos1) }}" class="card-img-top">
                                                @else
                                                    <p>No image available.</p>
                                                @endif
                                            </td>
                                            <td>
                                                @if($abuse->photos2)
                                                    <img width="40px" height="40px" src="{{ Storage::url($abuse->photos2) }}" class="card-img-top">
                                                @else
                                                    <p>No image available.</p>
                                                @endif
                                            </td>
                                            <td>
                                                @if($abuse->photos3)
                                                    <img width="40px" height="40px" src="{{ Storage::url($abuse->photos3) }}" class="card-img-top">
                                                @else
                                                    <p>No image available.</p>
                                                @endif
                                            </td>
                                            <td>
                                                @if($abuse->photos4)
                                                    <img width="40px" height="40px" src="{{ Storage::url($abuse->photos4) }}" class="card-img-top">
                                                @else
                                                    <p>No image available.</p>
                                                @endif
                                            </td>
                                            <td>
                                                @if($abuse->photos5)
                                                    <img width="40px" height="40px" src="{{ Storage::url($abuse->photos5) }}" class="card-img-top">
                                                @else
                                                    <p>No image available.</p>
                                                @endif
                                            </td>
                                            <td>
                                                @if($abuse->videos1)
                                                    <video width="320" height="240" controls>
                                                        <source src="{{ Storage::url($abuse->videos1) }}" type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                @else
                                                    <p>No video available.</p>
                                                @endif
                                            </td>
                                            <td>
                                                @if($abuse->videos2)
                                                    <video width="320" height="240" controls>
                                                        <source src="{{ Storage::url($abuse->videos2) }}" type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                @else
                                                    <p>No video available.</p>
                                                @endif
                                            </td>
                                            <td>
                                                @if($abuse->videos3)
                                                    <video width="320" height="240" controls>
                                                        <source src="{{ Storage::url($abuse->videos3) }}" type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                @else
                                                    <p>No video available.</p>
                                                @endif
                                            </td>

                                            <td>{{ $abuse->reason }}</td>
                                                    <td>@if($abuse->admin)
                                                            {{ $abuse->admin->name }}
                                                        @else
                                                            N/A
                                                        @endif
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
                <!-- /. ROW  -->
                <hr />
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <!-- /. WRAPPER  -->
    <!-- SCRIPTS - AT THE BOTTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="/admin/assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="/admin/assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="/admin/assets/js/jquery.metisMenu.js"></script>
    <!-- DATA TABLE SCRIPTS -->
    <script src="/admin/assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="/admin/assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTables-example').dataTable();
        });
    </script>
    <!-- CUSTOM SCRIPTS -->
    <script src="/admin/assets/js/custom.js"></script>
    
</body>
</html>
