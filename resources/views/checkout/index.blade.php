{{-- @extends('layouts.app')
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
                                        <strong>{{ $seatData }}</strong>:<br>
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
                        <h4>Detail Transportasi:</h4>
                        <p>Nama: {{ $transportasi->name }}</p>
                        <p>Jenis: {{ $transportasi->category->name }}</p>
                        <!-- Tambahkan detail transportasi lainnya di sini -->
                        <h4>Detail Rute:</h4>
                        <p>Asal: {{ $rute->start }}</p>
                        <p>Tujuan: {{ $rute->tujuan }}</p>
                        <!-- Tambahkan detail rute lainnya di sini -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection --}}

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
                            @foreach ($selectedSeats as $seat => $value)
                            <li>
                                <strong>{{ $seat }}</strong>
                            </li>
                        @endforeach
                            @endif
                        </ul>
                        <h4>Detail Transportasi:</h4>
                        <p>Nama: {{ $transportasi->name }}</p>
                        <p>Jenis: {{ $transportasi->category->name }}</p>
                        <p>Class : {{ $transportasi->kelas->name }}</p>
                        <!-- Tambahkan detail transportasi lainnya di sini -->
                        <h4>Detail Rute:</h4>
                        <p>Asal: {{ $rute->start }}</p>
                        <p>Tujuan: {{ $rute->tujuan }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
