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
                            @if (is_array($selectedSeats) || is_object($selectedSeats))
                                @foreach ($selectedSeats as $seat => $seatData)
                                    <li>
                                        <strong>{{ $seat }}</strong>:<br>
                                        <ul>
                                            @if (is_array($seatData) || is_object($seatData))
                                                @foreach ($seatData as $key => $value)
                                                    <li>{{ $key }}: {{ $value }}</li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                        {{-- <p>Total Harga: ${{ $totalPrice }}</p> --}}
                        {{-- <p>Jenis Transportasi: {{ $seatData->tujuan }}</p> --}}
                        <!-- Tambahkan informasi lain yang diperlukan untuk checkout di sini -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
