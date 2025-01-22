<style>
    #call-btn{
        background-color: {{ $color }};
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
                    <a class="nav-link" aria-current="page" href="/">Home</a>
                    <a class="nav-link" href="#">About</a>
                    <a class="nav-link" href="{{ route('vehicles.list') }}">Cars</a>
                    <a class="nav-link" href="#">Contact</a>
                    <button type="button" id="call-btn" class="btn">
                        <i class="fa fa-phone"></i> Need more help?
                    </button>
                </div>
            </div>
        </div>
    </nav>

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
