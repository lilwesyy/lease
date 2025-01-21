<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Lease</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <!-- Favicon -->
        <link rel="icon" href="{{ asset('favicons/' . $favicon) }}" type="image/x-icon">
        <!-- TailwindCSS -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
            rel="stylesheet"
        />
    </head>
    <body data-bs-theme="{{ $theme }}">

    <style>
    #view-detail-button:hover {
        color: white !important;
        background-color: {{ $color }};
    }
    #call-btn{
        background-color: {{ $color }};
    }
    #footer-link:hover{
        color: {{ $color }};
    }
    </style>

    <nav id="navbar" class="navbar navbar-expand-lg bg-body-tertiary fixed-top" data-bs-theme="{{ $theme }}">
        <div class="container">
            <a id="navbar-logo" class="navbar-brand" href="/">
                @if($name)
                    <span style="color: {{ $color }};">{{ substr($name, 0, 1) }}</span>{{ substr($name, 1) }}
                @else
                    {{ $name }}
                @endif
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav ms-auto">
                    <a class="nav-link active" aria-current="page" href="/">Home</a>
                    <a class="nav-link" href="#">About</a>
                    <a class="nav-link" href="#">Cars</a>
                    <a class="nav-link" href="#">Contact</a>
                    <button type="button" id="call-btn" class="btn">
                        <i class="fa fa-phone"></i> Need more help?
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <div id="carouselExample" class="carousel slide">
        <div class="carousel-inner">
        @foreach($vehicles as $index => $vehicle)
            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                @php
                    $imagePath = asset('images/' . $vehicle->image);
                @endphp
                <div class="image-container position-relative">
                    <img src="{{ $imagePath }}" class="d-block w-100" alt="{{ $vehicle->name }}">
                    <div class="overlay"></div>
                    <div class="carousel-caption d-none d-md-block {{ $theme == 'dark' ? 'text-white' : '' }} position-absolute top-50 start-50 translate-middle">
                        <p style="color:{{ $color }};"><span style="color:white;">Rental Car - </span>{{ $vehicle->type }}</p>
                        <h1 id="rental-text">{{ $vehicle->make }} {{ $vehicle->model }} </h1>
                        <p><span style="color:{{ $color }};">{{ number_format($vehicle->price, 0, ',', '.') }}€</span> / Day</p>                        
                        <a id="view-detail-button" style="color:{{ $color }};border-color:{{ $color }};" class="btn btn-outline-primary btn-lg" href="#" role="button">View Details</a>
                        <a id="rent-now-btn" style="background-color:{{ $color }};" class="btn btn-primary btn-lg" href="#" role="button">Rent Now!</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <div class="container-fluid" id="cars-container" style="margin-top:5%;margin-bottom:5%;">
        <h1 id="rental-process-txt">Our <span style="color:{{ $color }};">Cars</span></h1>

        <div class="owl-carousel owl-theme">
            <div class="item-2">
                <img src="{{ asset('images/bmw_m3.jpg') }}" class="d-block w-100 car-image" alt="...">
                <div class="caption">BMW M3</div>
            </div>
            <div class="item-2">
                <img src="{{ asset('images/lamborghini_aventador.jpg') }}" class="d-block w-100 car-image" alt="...">
                <div class="caption">Lamborghini Aventador</div>
            </div>
            <div class="item-2">
                <img src="{{ asset('images/porsche_gt3rs.jpg') }}" class="d-block w-100 car-image" alt="...">
                <div class="caption">Porsche GT3 RS</div>
            </div>
        </div>
    </div>
        
    <div class="container" style="margin-top:5%;margin-bottom:5%;">
        <h1 id="rental-process-txt">Car Rental <span style="color:{{ $color }};">Process</span></h1>
        <div class="row">
            <div class="col-sm">

        <div class="item">
            <div class="text">
            <h5 class="header-card-text">Choose A Car</h5>
            <p>View our range of cars, find your perfect car for the coming days.</p>
            </div>
            <div class="numb">
                <div class="numb-curv">
                    <div class="number">.01</div>
                    <div class="shap-left-top">
                        <svg viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-11 h-11">
                            <path d="M11 1.54972e-06L0 0L2.38419e-07 11C1.65973e-07 4.92487 4.92487 1.62217e-06 11 1.54972e-06Z" fill="#1b1b1b"></path>
                        </svg>
                    </div>
                    <div class="shap-right-bottom">
                        <svg viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-11 h-11">
                            <path d="M11 1.54972e-06L0 0L2.38419e-07 11C1.65973e-07 4.92487 4.92487 1.62217e-06 11 1.54972e-06Z" fill="#1b1b1b"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
            </div>
            <div class="col-sm">

        <div class="item">
            <div class="text">
                <h5 class="header-card-text">Come In Contact</h5>
                <p>Our advisor team is ready to help you with the booking process or any questions.</p>
            </div>
            <div class="numb">
                <div class="numb-curv">
                    <div class="number">.02</div>
                    <div class="shap-left-top">
                        <svg viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-11 h-11">
                            <path d="M11 1.54972e-06L0 0L2.38419e-07 11C1.65973e-07 4.92487 4.92487 1.62217e-06 11 1.54972e-06Z" fill="#1b1b1b"></path>
                        </svg>
                    </div>
                    <div class="shap-right-bottom">
                        <svg viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-11 h-11">
                            <path d="M11 1.54972e-06L0 0L2.38419e-07 11C1.65973e-07 4.92487 4.92487 1.62217e-06 11 1.54972e-06Z" fill="#1b1b1b"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
            </div>
            <div class="col-sm">

        <div class="item">
            <div class="text">
            <h5 class="header-card-text">Enjoy Driving</h5>
            <p>Receive the key and enjoy your car. We treat all our cars with respect.</p>
            </div>
            <div class="numb">
                <div class="numb-curv">
                    <div class="number">.03</div>
                    <div class="shap-left-top">
                        <svg viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-11 h-11">
                            <path d="M11 1.54972e-06L0 0L2.38419e-07 11C1.65973e-07 4.92487 4.92487 1.62217e-06 11 1.54972e-06Z" fill="#1b1b1b"></path>
                        </svg>
                    </div>
                    <div class="shap-right-bottom">
                        <svg viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-11 h-11">
                            <path d="M11 1.54972e-06L0 0L2.38419e-07 11C1.65973e-07 4.92487 4.92487 1.62217e-06 11 1.54972e-06Z" fill="#1b1b1b"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
            </div>
            <p id="below-card-text">
                <span class="info-icon">
                    <i class="fas fa-info-circle"></i>
                </span>
                If you’ve never rented a car before, we’ll guide you throught the process.
            </p>

        </div>
        </div>

        <div class="container-fluid parallax-container" style="position: relative; background-image: url('{{ asset('images/lamborghini_aventador.jpg') }}');">
            <div class="overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5);"></div>
            <div id="hero-banner" class="row" style="position: relative; z-index: 1;">
                <div class="col-sm">
                    <p style="color:{{ $color }};">RENT YOUR CAR</p>
                    <h1 id="hero-head-txt">Interested in Renting?</h1>
                    <p>Don't hesitate and send us a message.</p>
                    <a id="rent-now-btn" style="background-color:{{ $color }};" class="btn btn-primary btn-lg" href="#" role="button">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                    <a id="view-detail-button" style="color:{{ $color }};border-color:{{ $color }};" class="btn btn-outline-primary btn-lg" href="#" role="button">Rent Now -></a>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                    <div class="col-sm">
                        <a id="navbar-logo" class="navbar-brand" href="/">
                            @if($name)
                                <span style="color: {{ $color }};">{{ substr($name, 0, 1) }}</span>{{ substr($name, 1) }}
                            @else
                                {{ $name }}
                            @endif
                        </a>
                        <p>Rent a car imperdiet sapien porttito the bibenum ellentesue the commodo erat nesuen.</p>
                        <div class="social-icons">
                            <a href="https://www.facebook.com" class="social-icon" style="background-color:{{ $color }};">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://www.instagram.com" class="social-icon" style="background-color:{{ $color }};">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="https://www.whatsapp.com" class="social-icon" style="background-color:{{ $color }};">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-sm">
                        <h3 class="footer-head-txt">Quick Links</h3>
                        <ul style="list-style-type: disc;">
                            <li><a href="/home" id="footer-link">Home</a></li>
                            <li><a href="/about" id="footer-link">About Us</a></li>
                            <li><a href="/services" id="footer-link">Services</a></li>
                            <li><a href="/contact" id="footer-link">Contact</a></li>
                        </ul>
                    </div>
                <div class="col-sm">
                <div class="col-sm">
                    <h3 class="footer-head-txt">Subscribe</h3>
                    <p>Want to be notified about our services. Just sign up and we'll send you a notification by email.</p>
                    <form action="/subscribe" method="POST" class="form-inline">
                        @csrf
                        <div class="input-group">
                            <input type="email" name="email" class="form-control rounded-input" placeholder="Enter your email" required>
                            <div class="input-group-append">
                                <button style="background-color: {{$color}}; border:none;" class="btn btn-primary rounded-button" type="submit">
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <p class="copyright">©2025 {{ $name }}. All rights reserved.</p>
        </div>

        <script>
            window.addEventListener('scroll', function() {
                var navbar = document.getElementById('navbar');
                if (window.scrollY > 10) {
                    navbar.style.setProperty('background-color', '#272727', 'important');
                } else {
                    navbar.style.setProperty('background-color', 'transparent', 'important');
                }
            });
        </script>
        <script>
            $(document).ready(function(){
                $('.owl-carousel').owlCarousel({
                    loop:true,
                    margin:0, // Reduce margin between items
                    nav:true,
                    items:3, // Show multiple items
                    center: true, // Center the active item
                    onTranslated: function(event) {
                        var visibleItems = event.page.size;
                        var middleIndex = Math.floor(visibleItems / 2);
                        $('.caption').hide();
                        $('.owl-item').eq(event.item.index + middleIndex).find('.caption').show();
                    }
                });
                $('.caption').hide();
                var initialVisibleItems = $('.owl-carousel').find('.owl-item.active').length;
                var initialMiddleIndex = Math.floor(initialVisibleItems / 2);
                $('.owl-carousel').find('.owl-item.active').eq(initialMiddleIndex).find('.caption').show();
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
</html>
