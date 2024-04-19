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
                            @foreach ($decryptedSeats as $seat => $value)
                                <li><strong>{{ $seat }}</strong></li>
                            @endforeach
                        </ul>
                        <h4>Detail Transportasi:</h4>
                        <p>Nama: {{ $transportasi->name }}</p>
                        <p>Jenis: {{ $transportasi->category->name }}</p>
                        <p>Class: {{ $transportasi->kelas->name }}</p>
                        <!-- Tambahkan detail transportasi lainnya di sini -->
                        <h4>Detail Rute:</h4>
                        <p>Asal: {{ $rute->start }}</p>
                        <p>Tujuan: {{ $rute->tujuan }}</p>
                        <p>Harga: {{ $rute->harga }}</p>
                        <h4>Total Harga:</h4>
                        <p>Rp {{ $formattedTotalPrice }}</p>

                        <button class="btn btn-primary">
                            pesan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
