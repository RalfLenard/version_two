<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin</title>
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
                
                    
                    <li>
                        <a href="{{url('home')}}"> Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url('users') }}">Users List</a>
                    </li>

                    <li>
                        <a href="{{ url('animal-profiles') }}">Animal List</a>
                    </li>   

                     <li>
                        <a href="{{ url('adoption-requests') }}"> Adoption Request
                        </a>
                    </li>   

                    <li>
                        <a href="{{ url('reports') }}">Animal Report</a>
                    </li> 

                    <li>
                        <a href="{{ url('approved-requests') }}">Set Meeting</a>
                    </li>

                    <li>
                        <a class="active-menu" href="{{ url('admin-messenger') }}">Messages</a>
                    </li>

                    <li>
                        <a href="{{ url('appointments') }}">Meeting Scheduled</a>
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
            
                <div style="height: 90vh;">
                    @include('Chatify::pages.app')
                </div>
         
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


    
   
</body>
</html>
