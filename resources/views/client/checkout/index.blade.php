@extends('layouts.app')
@section('title', 'Checkout Detail')
@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Checkout Detail</h2>
                        <h4>Kursi yang Dipilih:</h4>
                        <ul>
                            @foreach ($selectedSeats as $seat => $seatData)
                                <li>{{ $seat }}</li>
                            @endforeach
                        </ul>
                        <p>Total Harga: ${{ $totalPrice }}</p>
                        <p>Jenis Transportasi: {{ $transportasi->jenis }}</p>
                        <!-- Tambahkan informasi lain yang diperlukan untuk checkout di sini -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
