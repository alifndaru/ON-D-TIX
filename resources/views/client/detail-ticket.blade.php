@extends('layouts.app')

@section('title', 'History')

@section('styles')
    <style>
        .card {
            margin-top: 20px;
            height: 100%;
        }

        .card-title {
            font-weight: 600;
            color: black;
            text-align: center;
        }

        .rute {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .rute hr {
            flex-grow: 1;
            border: none;
            border-top: 1px solid black;
            margin: 0 10px;
        }

        .rute h4 {
            font-weight: 400;
            color: black;
        }

        .informasi-pemesanan hr {
            margin-top: 30px;
        }
    </style>
@endsection

@section('content')

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">DETAIL PEMESANAN TIKET</h5>
            <hr>
            {{-- @dd($order) --}}
            <div class="rute">
                <h4>{{ $order->rute->start }}</h4>
                <hr>
                <h4>{{ $order->rute->end }}</h4>
            </div>

            <div class="informasi-pemesanan">
                <hr>
                <h4>Informasi Keberangkatan : </h4>

                <p>Kode Pemesanan : {{ $order->order_id }}</p>
                <p>Jadwal Berangkat : {{ $order->rute->tanggal_keberangkatan }} <br> Jam : {{ $order->rute->jam }} WIB</p>
                <hr>
            </div>

            <div class="informasi-transportasi">
                <h4>Informasi Transportasi : </h4>
                <p>Transportasi : {{ $order->transportasi->category->name }}</p>
                <p>Nama Kendaraan : {{ $order->transportasi->name }}</p>
                <p>Kelas : {{ $order->transportasi->kelas->name }}</p>
                @foreach ($kursi as $item)
                    <p>No Kursi :{{ $item->seat_id }}</p>
                @endforeach
                <p>harga : {{ $order->total }}</p>
                <hr>
            </div>

            <div class="barcode">
                <h3>
                    ini barcode
                </h3>
                <h5 class="card-title">{!! DNS1D::getBarcodeHTML($order->order_id, 'C128', 2, 30) !!}</h5>
            </div>
            <a href="#" class="btn btn-primary">Cetak</a>
        </div>
    </div>
@endsection
