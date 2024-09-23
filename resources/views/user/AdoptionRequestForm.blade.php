<!DOCTYPE html>
<html lang="en">

<head>

    @include('user.headlinks')

    <style>
    /* Ensuring navbar is on top */
    .navbar {
      z-index: 1000; /* High z-index to avoid overlap */
    }
  </style>

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
                <a class="nav-link" href="{{ url('message') }}">Message</a>
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
    <div class="latest-products" style="margin-top: 15px;">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-heading">
                        <h2>Adoption Form for {{ $animalprofile->name }}</h2>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="container mt-4">
                    <div class="card">
                        <div class="card-header">
                            <h2>Adoption Form</h2>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('adoption.submit', $animalprofile->id) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="first_name">First Name</label>
                                    <input type="text" name="first_name" id="first_name" class="form-control" value="{{ old('first_name') }}" required>
                                    @error('first_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" name="last_name" id="last_name" class="form-control" value="{{ old('last_name') }}" required>
                                    @error('last_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="gender">Gender</label>
                                    <select name="gender" id="gender" class="form-control" required>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="phone_number">Phone Number</label>
                                    <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number') }}" required>
                                    @error('phone_number')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" name="address" id="address" class="form-control" value="{{ old('address') }}" required>
                                    @error('address')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="salary">Salary per Month</label>
                                    <input type="number" name="salary" id="salary" class="form-control" value="{{ old('salary') }}" required>
                                    @error('salary')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="question1">Why do you want to adopt this animal?</label>
                                    <textarea name="question1" id="question1" class="form-control" required>{{ old('question1') }}</textarea>
                                    @error('question1')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="question2">How will you care for this animal?</label>
                                    <textarea name="question2" id="question2" class="form-control" required>{{ old('question2') }}</textarea>
                                    @error('question2')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="question3">What is your experience with pets?</label>
                                    <textarea name="question3" id="question3" class="form-control" required>{{ old('question3') }}</textarea>
                                    @error('question3')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="valid_id">Upload Valid ID</label>
                                    <input type="file" name="valid_id" id="valid_id" class="form-control" required>
                                    @error('valid_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="valid_id_with_owner">Upload Valid ID with Owner</label>
                                    <input type="file" name="valid_id_with_owner" id="valid_id_with_owner" class="form-control" required>
                                    @error('valid_id_with_owner')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary">Submit Adoption Request</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/user/assets/js/custom.js"></script>
    <script src="/user/assets/js/owl.js"></script>
    <script src="/user/assets/js/slick.js"></script>
    <script src="/user/assets/js/isotope.js"></script>
    <script src="/user/assets/js/accordions.js"></script>
</body>
</html>
