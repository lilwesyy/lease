<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('components.head')

<body data-bs-spy="scroll" data-bs-target="#navbar" data-bs-offset="0" tabindex="0" data-bs-theme="{{ $theme }}">
    <style>
        #view-detail-button:hover {
            color: white !important;
            background-color: {{ $color }};
        }

        #footer-link:hover {
            color: {{ $color }};
        }
    </style>

    @include('components.navbar')
    @include('components.totop')

    <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach ($vehicles as $index => $vehicle)
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                    @php
                        $imagePath = asset('images/' . $vehicle->image);
                    @endphp
                    <div class="image-container position-relative">
                        <img src="{{ $imagePath }}" class="d-block w-100" alt="{{ $vehicle->name }}">
                        <div class="overlay"></div>
                        <div class="carousel-caption">
                            <p style="color:{{ $color }};"><span style="color:white;">Rental Car -
                                </span>{{ $vehicle->type }}</p>
                            <h1 id="rental-text">{{ $vehicle->make }} {{ $vehicle->model }} </h1>
                            <p><span
                                    style="color:{{ $color }};">{{ number_format($vehicle->price, 0, ',', '.') }}€</span>
                                / Day</p>
                            <a id="view-detail-button"
                                style="color:{{ $color }};border-color:{{ $color }};margin-top:2%;"
                                class="btn btn-outline-primary btn-lg"
                                href="{{ route('vehicles.show', $vehicle->id) }}" role="button">View Details</a>
                            <a id="rent-now-btn" style="background-color:{{ $color }};margin-top:2%;"
                                class="btn btn-primary btn-lg" href="#" role="button">Rent Now!</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
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

    <div id="aboutus" class="container" style="margin-top:5%;margin-bottom:5%;">
        <h1 id="rental-process-txt-about">{!! trans('translations.aboutUs', ['color' => $color]) !!}</span></h1>
        <div class="row">
            <div class="col">
                <div class="card about-card" style="height: 100%;">
                    <div class="card-body about-card-body">
                        <h5 class="card-title header-card-text">{{ __('translations.whoWeAre') }}</h5>
                        <p class="card-text" id="about-text">{{ __('translations.aboutText') }}</p>
                    </div>
                </div>
            </div>
            <div class="col" style="display: flex; align-items: center;">
                <img class="about-image" src="{{ asset('images/about.png') }}" alt=""
                    style="height: 100%; width: auto;">
            </div>
        </div>
    </div>

    <div class="container" style="margin-top:5%;margin-bottom:5%;">
        <h1 id="rental-process-txt">{{ __('translations.carRentalProcess') }} <span style="color:{{ $color }};">Process</span></h1>
        <div class="row">
            <div class="col-sm">
                <div class="item">
                    <div class="text">
                        <h5 class="header-card-text">{{ __('translations.chooseCar') }}</h5>
                        <p>{{ __('translations.chooseCarText') }}</p>
                    </div>
                    <div class="numb">
                        <div class="numb-curv">
                            <div class="number">.01</div>
                            <div class="shap-left-top">
                                <svg viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg"
                                    class="w-11 h-11">
                                    <path
                                        d="M11 1.54972e-06L0 0L2.38419e-07 11C1.65973e-07 4.92487 4.92487 1.62217e-06 11 1.54972e-06Z"
                                        fill="#1b1b1b"></path>
                                </svg>
                            </div>
                            <div class="shap-right-bottom">
                                <svg viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg"
                                    class="w-11 h-11">
                                    <path
                                        d="M11 1.54972e-06L0 0L2.38419e-07 11C1.65973e-07 4.92487 4.92487 1.62217e-06 11 1.54972e-06Z"
                                        fill="#1b1b1b"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm">
                <div class="item">
                    <div class="text">
                        <h5 class="header-card-text">{{ __('translations.comeInContact') }}</h5>
                        <p>{{ __('translations.comeInContactText') }}</p>
                    </div>
                    <div class="numb">
                        <div class="numb-curv">
                            <div class="number">.02</div>
                            <div class="shap-left-top">
                                <svg viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg"
                                    class="w-11 h-11">
                                    <path
                                        d="M11 1.54972e-06L0 0L2.38419e-07 11C1.65973e-07 4.92487 4.92487 1.62217e-06 11 1.54972e-06Z"
                                        fill="#1b1b1b"></path>
                                </svg>
                            </div>
                            <div class="shap-right-bottom">
                                <svg viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg"
                                    class="w-11 h-11">
                                    <path
                                        d="M11 1.54972e-06L0 0L2.38419e-07 11C1.65973e-07 4.92487 4.92487 1.62217e-06 11 1.54972e-06Z"
                                        fill="#1b1b1b"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm">
                <div class="item">
                    <div class="text">
                        <h5 class="header-card-text">{{ __('translations.enjoyDriving') }}</h5>
                        <p>{{ __('translations.enjoyDrivingText') }}</p>
                    </div>
                    <div class="numb">
                        <div class="numb-curv">
                            <div class="number">.03</div>
                            <div class="shap-left-top">
                                <svg viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg"
                                    class="w-11 h-11">
                                    <path
                                        d="M11 1.54972e-06L0 0L2.38419e-07 11C1.65973e-07 4.92487 4.92487 1.62217e-06 11 1.54972e-06Z"
                                        fill="#1b1b1b"></path>
                                </svg>
                            </div>
                            <div class="shap-right-bottom">
                                <svg viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg"
                                    class="w-11 h-11">
                                    <path
                                        d="M11 1.54972e-06L0 0L2.38419e-07 11C1.65973e-07 4.92487 4.92487 1.62217e-06 11 1.54972e-06Z"
                                        fill="#1b1b1b"></path>
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
                {{ __('translations.belowCardText') }}
            </p>

        </div>
    </div>

    <div class="container-fluid parallax-container"
        style="position: relative; background-image: url('{{ asset('images/lamborghini_aventador.jpg') }}');">
        <div class="overlay"
            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5);">
        </div>
        <div id="hero-banner" class="row" style="position: relative; z-index: 1;">
            <div class="col-sm">
                <p style="color:{{ $color }};">RENT YOUR CAR</p>
                <h1 id="hero-head-txt">{{ __('translations.interestedInRenting') }}</h1>
                <p>{{ __('translations.sendMessage') }}</p>
                <a id="rent-now-btn" style="background-color:{{ $color }};" class="btn btn-primary btn-lg"
                    href="#" role="button">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </a>
                <a id="view-detail-button" style="color:{{ $color }};border-color:{{ $color }};"
                    class="btn btn-outline-primary btn-lg" href="#" role="button">Rent Now -></a>
            </div>
        </div>
    </div>

    @include('components.footer')
    <script>
        const translations = @json(__('translations'));

        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("rental-process-txt").innerHTML = translations.carRentalProcess;
            document.querySelector(".header-card-text").innerHTML = translations.whoWeAre;
            document.getElementById("about-text").innerHTML = translations.aboutText;
            document.querySelectorAll(".header-card-text")[1].innerHTML = translations.chooseCar;
            document.querySelectorAll(".header-card-text")[2].innerHTML = translations.comeInContact;
            document.querySelectorAll(".header-card-text")[3].innerHTML = translations.enjoyDriving;
            document.getElementById("below-card-text").innerHTML = translations.belowCardText;
            document.getElementById("hero-head-txt").innerHTML = translations.interestedInRenting;
            document.querySelector("#hero-banner p").innerHTML = translations.sendMessage;
        });
    </script>
    @include('components.scripts')
</body>

</html>
