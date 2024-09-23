<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <title>Sixteen Clothing HTML Template</title>

    @include('user.headlinks')

      @include('user.ScriptHome')

</head>

<body>

    <!-- ***** Preloader Start ***** -->
    <div id="preloader">
        <div class="jumper">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <!-- ***** Preloader End ***** -->

    <!-- Header -->
    <header class="" style="position: relative; top: 0;">
    <nav class="navbar navbar-expand-lg">
      <div class="container">
        <a class="navbar-brand" href="{{url('home')}}">
          <h2>Noah's <em>Ark</em></h2>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
              <a class="nav-link" href="{{url('home')}}">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{url('report/abuse')}}">Report Animal Abuse</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('user-messenger') }}">Message</a>
            </li>
            <li class="nav-item">
                            <a class="nav-link" href="{{ url('User-Video-call') }}">Video Call</a>
                        </li>
            <ul class="navbar-nav ml-auto">
                            @include('user.Notification')
                        </ul>

            @if(Route::has('login'))
            @auth
            <!-- Display user profile and logout links -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{ Auth::user()->name }}
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ route('profile.show') }}">Profile</a>
                <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
              </div>
            </li>
            @else
            <li class="nav-item">
              <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Login</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('register') }}" class="btn btn-success btn-sm">Register</a>
            </li>
            @endauth
            @endif
          </ul>
        </div>
      </div>
    </nav>
    </header>

    <!-- Page Content -->
    <!-- Banner Starts Here -->
    <!-- Banner Ends Here -->

    <div class="latest-products" style="margin-top: 15px;">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-heading">
                        <h2>OUR PETS</h2>
                    </div>
                </div>

                <div class="container mt-4">
                    <div class="card">
                        <div class="card-header">
                            <h2>{{ $animal->name }}'s Profile</h2>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="{{ Storage::url($animal->profile_picture) }}" class="card-img-top" alt="{{ $animal->name }}">
                                </div>
                                <div class="col-md-8">
                                    <h4>Description</h4>
                                    <p>{{ $animal->description }}</p>
                                    <br><br>
                                    <h4>Details</h4>
                                    <ul class="list-unstyled">
                                        <li><strong>Name:</strong> {{ $animal->name }}</li>
                                        <li><strong>Species:</strong> {{ $animal->species }}</li>
                                        <li><strong>Age:</strong> {{ $animal->age }}</li>
                                        <li><strong>Medical Records:</strong></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <a href="{{ route('adopt.show', $animal->id) }}" class="btn btn-primary">Adopt {{ $animal->name }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript -->
  <script src="/user/vendor/jquery/jquery.min.js"></script>
  <script src="/user/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Additional Scripts -->
  <script src="/user/assets/js/custom.js"></script>
  <script src="/user/assets/js/owl.js"></script>
  <script src="/user/assets/js/slick.js"></script>
  <script src="/user/assets/js/isotope.js"></script>
  <script src="/user/assets/js/accordions.js"></script>

    <script language="text/Javascript">
        cleared[0] = cleared[1] = cleared[2] = 0; //set a cleared flag for each field
        function clearField(t) { //declaring the array outside of the
            if (!cleared[t.id]) { // function makes it static and global
                cleared[t.id] = 1;  // you could use true and false, but that's more typing
                t.value = '';         // with more chance of typos
                t.style.color = '#fff';
            }
        }
    </script>

</body>

</html>
