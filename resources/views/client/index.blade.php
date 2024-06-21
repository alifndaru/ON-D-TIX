{{-- @extends('layouts.app')
@section('title', 'Home')
@section('styles')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />

    <style>
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px 0 rgba(0, 0, 0, 0.1);
            transition: 0.3s;
            margin-top: 30px;
        }

        .card-body {
            text-align: center;
        }

        .col-md-4 {
            margin-bottom: 20px;
        }

        .link-card,
        .link-card:hover,
        .link-card:active {
            text-decoration: none;
            color: black;
            font-family: 'Quicksand', sans-serif;
        }
    </style>

@endsection
@section('content')

    <!--about-->
    <section class="about">
        <div class="container">
            <div class="box-about">
                <div class="box">
                    <h1>About</h1>
                    <p>
                        makan
                    </p>
                </div>
                <div class="box">
                    <img src="{{ asset('img/logo.png') }}" alt="" />
                </div>
            </div>
        </div>
    </section>
    <!--about-->

    <div class="row justify-content-center">
        @foreach ($category as $item)
            <div class="col-md-4">
                <a href="{{ route('category.show', ['slug' => $item->slug]) }}" class="link-card">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->name }}</h5>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>




    <section class="destination">
        <div class="judul-destinasi">
            <h1>Temukan Tujuan Stasiun dan Terminal Anda</h1>
        </div>
        <div class="card-container">
            <a href="#">
                <div class="card-destinasi">
                    <img src="{{ asset('img/bandung.jpg') }}" alt="" />
                    <h2>Jawa Barat</h2>
                    <p>99 Stasiun</p>
                    <p>99 Terminal</p>
                </div>
            </a>
            <a href="#">
                <div class="card-destinasi">
                    <img src="{{ asset('img/semarang.jpg') }}" alt="" />
                    <h2>Jawa Tengah</h2>
                    <p>99 Stasiun</p>
                    <p>99 Terminal</p>
                </div>
            </a>
            <a href="#">
                <div class="card-destinasi">
                    <img src="{{ asset('img/surabaya.jpg') }}" alt="" />
                    <h2>Jawa Timur</h2>
                    <p>99 Stasiun</p>
                    <p>99 Terminal</p>
                </div>
            </a>
        </div>
    </section>

@endsection
@section('scripts')
    <script>
        // Your custom scripts if needed
    </script>
@endsection --}}
