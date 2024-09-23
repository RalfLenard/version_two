<!DOCTYPE html>
<html lang="en">
<head>
    
    @include('user.headlinks')
    @include('user.ScriptHome')
    
</head>
<body>
    
    <!-- Preloader -->
    <div id="preloader">
        <div class="jumper">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>  

    <!-- Header -->
    <header style="position: relative; top: 0;">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="{{ url('home') }}">
                    <h2>Noah's <em>Ark</em></h2>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ url('home') }}">Home <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/report/abuse') }}">Report Animal Abuse</a>
                        </li>

                         <li class="nav-item">
                            <a class="nav-link" href="{{ url('user-messenger') }}">Message</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('User-Video-call') }}">Video Call</a>
                        </li>

                        <!-- Notification Dropdown -->
                        @include('user.Notification')

                        <!-- User Dropdown -->
                        @if(Route::has('login'))
                        @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('profile.show') }}">Profile</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                                <a class="dropdown-item" href="{{ route('logout') }} "
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                   Logout
                                </a>
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

    <div class="latest-products" id="latest-products" style="margin-top: 15px;">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-heading" style="margin-top:15px; margin-bottom: 15px;">
                        <h2 class="d-inline">OUR PETS</h2>
                        
                        <!-- Search Form -->
                        <form action="{{ route('animals.search') }}" method="GET" class="form-inline float-right" style="margin-top: -7px;">
                            <input class="form-control mr-sm-2" type="search" name="query" placeholder="Search for pets..." aria-label="Search" value="{{ request()->input('query') }}">
                            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                        </form>
                    </div>
                </div>

                @if($animalProfiles->count())
                @foreach($animalProfiles as $animal)
                <div class="col-md-4" id="animal-{{ $animal->id }}">
                    <div class="product-item">
                        <a href="{{ route('animals.show', $animal->id) }}">
                            <img src="{{ Storage::url($animal->profile_picture) }}" class="card-img-top" alt="{{ $animal->name }}">
                        </a>
                        <div class="down-content">
                            <a href="#">
                                <h4>{{ $animal->name }}</h4>
                            </a>
                            <h6>{{ $animal->age }}</h6>
                            <p class="description" data-description="{{ $animal->description }}"></p>
                            <span><a href="{{ route('animals.show', $animal->id) }}">View Profile</a></span>
                        </div>
                    </div>
                </div>
                @endforeach    

                <!-- Pagination Links -->
                <div class="col-md-12">
                    {{ $animalProfiles->links() }}
                </div>

                @else
                <div class="col-md-12">
                    <p>No pets found.</p>
                </div>
                @endif

            </div>
        </div>
    </div>

    <!-- Additional Scripts -->
    <script src="/user/assets/js/custom.js"></script>
    <script src="/user/assets/js/owl.js"></script>
    <script src="/user/assets/js/slick.js"></script>
    <script src="/user/assets/js/isotope.js"></script>
    <script src="/user/assets/js/accordions.js"></script>
   

    <script>
        document.addEventListener("DOMContentLoaded", function(){
            const descriptions = document.querySelectorAll('.description');

            descriptions.forEach(desc => {
                const fullText = desc.getAttribute('data-description');
                const wordLimit = 8;
                const words = fullText.split(' ');

                if(words.length > wordLimit){
                    const truncatedText = words.slice(0, wordLimit).join(' ') + '...';
                    desc.textContent = truncatedText;
                }else{
                    desc.textContent = fullText;
                }
            });
        });
    </script>
</body>
</html>
