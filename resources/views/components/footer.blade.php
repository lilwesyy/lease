<!-- <div class="brand-logos" style="display: flex; justify-content: center; gap: 50px; margin-bottom: 2%;">
        <img src="{{ asset('images/bmw.png') }}" alt="Brand 1" style="height: 3%; width: 3%;">
        <img src="{{ asset('images/porsche.png') }}" alt="Brand 1" style="height: 3%; width: 3%;">
        <img src="{{ asset('images/volkswagen.png') }}" alt="Brand 1" style="height: 3%; width: 3%;">
    </div> -->

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
                        <ul style="list-style-type: disc;text-color:#adb5bd;">
                            <li><a href="/" id="footer-link">Home</a></li>
                            <li><a href="/" id="footer-link">About</a></li>
                            <li><a href="{{ route('vehicles.list') }}" id="footer-link">Cars</a></li>
                            <li><a href="/" id="footer-link">Contact</a></li>
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
