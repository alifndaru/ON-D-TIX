@extends('layouts.app')
@section('title', 'Home')
@section('styles')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;700&display=swap" rel="stylesheet">

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
@endsection
@section('scripts')
    <script>
        // Your custom scripts if needed
    </script>
@endsection
