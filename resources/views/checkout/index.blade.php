@extends('layouts.app')

@section('title', 'Checkout Detail')
<style>
    /* Custom CSS for Checkout Detail page */
    .card {
        margin-bottom: 20px;
    }

    .card-title {
        color: #333;
        font-weight: bold;
    }

    .card-body {
        padding: 20px;
    }

    .list-group-item {
        background-color: #f8f9fa;
        border: none;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }
</style>
@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <form action="{{ route('payment.create') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <a href="{{ url()->previous() }}" class="btn btn-secondary mb-3">Back</a>
                            @if (session()->has('error'))
                                <div class="alert alert-error text-center">
                                    <strong>
                                        {{ session()->get('error') }}
                                    </strong>
                                </div>
                            @endif
                            <h2 class="text-center mb-4">Detail Penumpang</h2>
                            <div class="card mb-4">
                                <input type="text" name="user_id" id="user_id" value="{{ auth()->user()->id }}" hidden>
                                <label for="payer_email">Email</label>
                                <input type="email" name="payer_email" id="payer_email"
                                    value="{{ auth()->user()->email }}">
                            </div>
                            <h2 class="text-center mb-4">Checkout Detail</h2>
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h4 class="card-title">Kursi yang Dipilih:</h4>
                                    <ul class="list-group">
                                        @foreach ($decryptedSeats as $seat => $value)
                                            <li class="list-group-item">
                                                <input type="hidden" name="seat[]" id="seat"
                                                    value="{{ $seat }}">
                                                <i class="fas fa-chair"></i> {{ $seat }}
                                            </li>
                                        @endforeach
                                    </ul>


                                </div>
                            </div>
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h4 class="card-title">Detail Transportasi:</h4>
                                    <input type="hidden" name="transportasi_id" id="transportasi_id"
                                        value="{{ $transportasi->id }}">
                                    <p><strong>Nama:</strong> {{ $transportasi->name }}</p>
                                    <p><strong>Jenis:</strong> {{ $transportasi->category->name }}</p>
                                    <p><strong>Class:</strong> {{ $transportasi->kelas->name }}</p>
                                </div>
                            </div>
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h4 class="card-title">Detail Rute:</h4>
                                    <input type="hidden" name="rute_id" id="rute_id" value="{{ $rute->id }}">
                                    <p><strong>Asal:</strong> {{ $rute->start }}</p>
                                    <p><strong>Tujuan:</strong> {{ $rute->tujuan }}</p>
                                    <p><strong>Harga:</strong> {{ $rute->harga }}</p>
                                </div>
                            </div>
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h4 class="card-title">Total Harga:</h4>
                                    <p class="text-success font-weight-bold">
                                        Rp {{ $formattedTotalPrice }}
                                        <input type="hidden" name="amount" id="amount" value="{{ $totalPrice }}">
                                    </p>
                                </div>
                            </div>

                            <button class="btn btn-primary btn-block">
                                Pesan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
