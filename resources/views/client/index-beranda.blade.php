<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ON-D TIX</title>

    <!-- Fonts and Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">

    <!-- CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/toastr/toastr.min.css') }}" rel="stylesheet">
</head>

<body>
    <header>
        <!-- Navigation -->
        <nav class="navigation">
            <div class="container">
                <div class="nav-content">
                    <a href="#" class="logo"><img src="{{ asset('img/logo.png') }}" alt="logo"></a>
                    <ul class="menu-navigation">
                        <li><a href="#"><i class="ri-home-4-fill"></i> Beranda</a></li>
                        <li><a href="#about"><i class="ri-information-2-fill"></i> About</a></li>
                        <li><a href="#choose-transport"><i class="ri-ticket-fill"></i> Booking</a></li>
                        @if (Auth::check())
                            <li>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="ri-logout-box-fill"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf</form>
                            </li>
                        @else
                            <li><a href="{{ route('login') }}"><i class="ri-login-box-fill"></i> Login</a></li>
                        @endif
                    </ul>
                    <div class="menu-bar"><i class="ri-menu-3-line"></i></div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero">
            <div class="container">
                <div class="title-hero">
                    <h1>Jelajahi Destinasi Anda dengan Mudah: Pesan Tiket Kereta dan Bus Tanpa Ribet!</h1>
                </div>
            </div>
        </section>
    </header>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="container">
            <div class="about-content">
                <div class="text-box">
                    <h1>About</h1>
                    <p>Pemesanan tiket transportasi publik antar kota dan antar provinsi secara online.</p>
                </div>
                <div class="image-box">
                    <img src="{{ asset('img/logo.png') }}" alt="logo">
                </div>
            </div>
        </div>
    </section>

    <!-- Card Menu Section -->
    <section class="card-menu" id="choose-transport">
        <div class="container">
            <h3>Choose Your Transport</h3>
            <div class="row">
                @foreach ($category as $item)
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <a href="{{ route('category.show', ['slug' => $item->slug]) }}" class="card-link">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $item->name }}</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Destination Section -->
    <section class="destination">
        <div class="container">
            <h1>Temukan Tujuan Stasiun dan Terminal Anda</h1>
            <div class="card-container">
                <a href="#" class="card-destination">
                    <div class="card-destinasi">
                        <img src="{{ asset('img/bandung.jpg') }}" alt="Jawa Barat">
                        <h2>Jawa Barat</h2>
                        <p>99 Stasiun</p>
                        <p>99 Terminal</p>
                    </div>
                </a>
                <a href="#" class="card-destination">
                    <div class="card-destinasi">
                        <img src="{{ asset('img/semarang.jpg') }}" alt="Jawa Tengah">
                        <h2>Jawa Tengah</h2>
                        <p>99 Stasiun</p>
                        <p>99 Terminal</p>
                    </div>
                </a>
                <a href="#" class="card-destination">
                    <div class="card-destinasi">
                        <img src="{{ asset('img/surabaya.jpg') }}" alt="Jawa Timur">
                        <h2>Jawa Timur</h2>
                        <p>99 Stasiun</p>
                        <p>99 Terminal</p>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- How to Order Section -->
    <section class="how-to-order py-5">
        <div class="container">
            <h1 class="text-center mb-5">How to Order</h1>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow rounded-3">
                        <div class="card-body">
                            <h4 class="card-title"><i class="fa fa-bus me-2"></i>Select Your Transportation</h4>
                            <p class="card-text">Choose the type of transportation that suits your needs.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow rounded-3">
                        <div class="card-body">
                            <h4 class="card-title"><i class="fa fa-map-marker me-2"></i>Select Your Destination and
                                Departure Date</h4>
                            <p class="card-text">Choose your destination and the date of your departure.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow rounded-3">
                        <div class="card-body">
                            <h4 class="card-title"><i class="fa fa-search me-2"></i>Select Available Transportation
                            </h4>
                            <p class="card-text">Choose from the available transportation options.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow rounded-3">
                        <div class="card-body">
                            <h4 class="card-title"><i class="fa fa-credit-card me-2"></i>Payment</h4>
                            <p class="card-text">Complete your payment using the available payment methods.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mx-auto">
                    <div class="card border-0 shadow rounded-3">
                        <div class="card-body">
                            <h4 class="card-title"><i class="fa fa-check me-2"></i>Confirmation</h4>
                            <p class="card-text">After your payment is confirmed, you will receive a confirmation of
                                your order.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2023 ON-D TIX. All rights reserved.</p>
        </div>
    </footer>

    <script>
        $(document).ready(function() {
            $('a[href^="#"]').on('click', function(event) {
                var target = $(this.getAttribute('href'));
                if (target.length) {
                    event.preventDefault();
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top
                    }, 1000);
                }
            });
        });
    </script>
    <!-- Scripts -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-beta1/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>

</body>

</html>
