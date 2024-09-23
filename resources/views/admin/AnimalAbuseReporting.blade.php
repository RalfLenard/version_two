<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - Animal Abuse Reports</title>
    
    <!-- BOOTSTRAP STYLES-->
    <link href="/admin/assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="/admin/assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
    <link href="/admin/assets/css/custom.css" rel="stylesheet" />
    <!-- DATA TABLE STYLES -->
    <link href="/admin/assets/css/dataTables.bootstrap.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

    @include('admin.ScriptAnimalAbuseRequested')
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
                        <img src="/admin/assets/img/find_user.png" class="user-image img-responsive" />
                    </li>
                    <li><a href="{{ url('home') }}">Dashboard</a></li>
                    <li><a href="{{ url('users') }}">Users List</a></li>
                    <li><a href="{{ url('animal-profiles') }}">Animal List</a></li>
                    <li><a href="{{ url('adoption-requests') }}">Adoption Requests</a></li>
                    <li><a class="active-menu" href="{{ url('reports') }}">Animal Reports</a></li>
                    <li><a href="{{ url('approved-requests') }}">Set Meetings</a></li>
                    <li><a href="{{ url('admin-messenger') }}">Messages</a></li>
                    <li><a href="{{ url('appointments') }}">Scheduled Meetings</a></li>
                    <li><a href="{{ url('rejected-Form') }}">Rejected Adoption Forms</a></li>
                    <li><a href="{{ url('rejected') }}">Rejected Reports</a></li>
                </ul>
            </div>
        </nav>

        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">Animal Abuse Reports</div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>Description</th>
                                                <th>Photos</th>
                                                <th>Videos</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($abuses as $abuse)
                                            <tr class="odd gradeX">
                                                <td>{{ $abuse->description }}</td>
                                                <td>
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($abuse->{'photos' . $i})
                                                            <img width="40px" height="40px" src="{{ Storage::url($abuse->{'photos' . $i}) }}" class="card-img-top" alt="Photo {{ $i }}">
                                                        @else
                                                            <p>No image available.</p>
                                                            @break
                                                        @endif
                                                    @endfor
                                                </td>
                                                <td>
                                                    @for ($i = 1; $i <= 3; $i++)
                                                        @if ($abuse->{'videos' . $i})
                                                            <video width="100" height="80" controls>
                                                                <source src="{{ Storage::url($abuse->{'videos' . $i}) }}" type="video/mp4">
                                                                Your browser does not support the video tag.
                                                            </video>
                                                        @else
                                                            <p>No video available.</p>
                                                            @break
                                                        @endif
                                                    @endfor
                                                </td>
                                                <td>
                                                    @if ($abuse->status == 'pending')
                                                        <form action="{{ route('admin.abuses.verify', $abuse->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-warning">Verify</button>
                                                        </form>
                                                    @endif
                                                    @if ($abuse->status == 'Verifying')
                                                        <form action="{{ route('admin.abuses.approve', $abuse->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success">Approve</button>
                                                        </form>
                                                        <button type="button" class="btn btn-danger" onclick="showRejectModal({{ $abuse->id }})">Reject</button>
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
                <hr />
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectAdoptionModal" tabindex="-1" aria-labelledby="rejectAdoptionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="rejectForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectAdoptionModalLabel">Reject Report</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="reason">Reason for Rejection:</label>
                            <textarea class="form-control" id="reason" name="reason" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="/admin/assets/js/jquery-1.10.2.js"></script>
    <script src="/admin/assets/js/bootstrap.min.js"></script>
    <script src="/admin/assets/js/jquery.metisMenu.js"></script>
    <script src="/admin/assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="/admin/assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script src="/admin/assets/js/custom.js"></script>
    
    <script>
        $(document).ready(function () {
            $('#dataTables-example').dataTable();
        });

        function showRejectModal(id) {
            $('#rejectForm').attr('action', '/admin/reject/' + id);
            $('#rejectAdoptionModal').modal('show');
        }
    </script>
</body>
</html>
