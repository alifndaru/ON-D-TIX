@extends('layouts.app')
@section('title', 'Hasil Pencarian')


@section('styles')
    <style>
        .card-rute {
            text-decoration: none;
            color: black;
        }

        .card-rute:hover {
            text-decoration: none;
        }

        /* .kursi {
                        box-sizing: border-box;
                        border: 2px solid #858796;
                        width: 100%;
                        height: 120px;
                        display: flex;
                    } */
    </style>


@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 mb-3">
                <a href="{{ URL::previous() }}" class="text-white btn"><i class="fas fa-arrow-left mr-2"></i> Kembali</a>
            </div>
            @foreach ($rutes as $rute)
                <a href="{{ route('pilih-kursi', ['id' => $rute->id]) }}" class="card-rute">
                    <div class="col-md-4 mb-3">
                        <div class="card o-hidden border-0 shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="font-weight-bold text-gray-800 text-uppercase mb-1">{{ $rute->tujuan }}
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-primary mb-1">{{ $rute->start }} -
                                            {{ $rute->end }}</div>
                                        <small class="text-muted">{{ $rute->transportasi->nama }}
                                            ({{ $rute->transportasi->kode }})
                                        </small>
                                    </div>
                                    <div class="col-auto text-right">
                                        <div class="h5 mb-0 font-weight-bold text-primary">Rp.
                                            {{ number_format($rute->harga, 0, ',', '.') }}</div>
                                        <small class="text-muted">/Orang</small>
                                        @if ($rute->kursi < 50)
                                            <p class="text-primary" style="margin: 0;"><small>{{ $rute->kursi }} Kursi
                                                    Tersedia</small></p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                </a>
        </div>
        @endforeach
    </div>
    </div>
@endsection
