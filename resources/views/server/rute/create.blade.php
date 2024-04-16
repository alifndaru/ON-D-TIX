@extends('layouts.app')
@section('title', 'Rute')
@section('heading', 'Create Rute')
@section('content')

    <div class="container mt-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-light">
                <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                    @foreach ($category as $item)
                        <li class="nav-item">
                            <a class="nav-link{{ $loop->first ? ' active' : '' }}" id="{{ $item->slug }}-tab"
                                data-toggle="tab" href="#{{ $item->slug }}" role="tab"
                                aria-controls="{{ $item->slug }}"
                                aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $item->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="myTabContent">
                    @foreach ($category as $item)
                        <div class="tab-pane fade{{ $loop->first ? ' show active' : '' }}" id="{{ $item->slug }}"
                            role="tabpanel" aria-labelledby="{{ $item->slug }}-tab">
                            @if ($item->name == 'Bus' || $item->name == 'BUS')
                                <form id="{{ $item->slug }}Form" action="" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="tujuan">Tujuan</label>
                                        <select class="form-control" id="tujuan" name="tujuan" required>
                                            <option value="" disabled selected>-- Pilih Tujuan --</option>
                                        </select>
                                    </div>
                                    <!-- Tambahkan form input lainnya sesuai kebutuhan -->
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </form>
                            @elseif (
                                $item->name == 'KERETA-API' ||
                                    $item->name == 'kereta api' ||
                                    $item->name == 'Kereta' ||
                                    $item->name == 'KERETA' ||
                                    $item->name == 'KERETA API' ||
                                    $item->name == 'kereta api')
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tujuan">Tujuan</label>
                                        <select class="form-control" id="tujuan" name="tujuan" required>
                                            @foreach ($stations as $city => $cityStations)
                                                <optgroup label="{{ $city }}">
                                                    @foreach ($cityStations as $station)
                                                        <option value="{{ $station['city'] }}">{{ $station['city'] }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="start">Rute Awal</label>
                                        <select class="form-control" id="start" name="start" required>
                                            @foreach ($stations as $city => $cityStations)
                                                <optgroup label="{{ $city }}">
                                                    @foreach ($cityStations as $station)
                                                        <option value="{{ $station['name'] }}">
                                                            {{ $station['name'] }} - {{ $station['code'] }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="end">Rute Akhir</label>
                                        {{-- <input type="text" class="form-control" id="end" name="end"
                                        placeholder="Rute Akhir" required /> --}}
                                        <select class="form-control" id="end" name="end" required>
                                            @foreach ($stations as $city => $cityStations)
                                                <optgroup label="{{ $city }}">
                                                    @foreach ($cityStations as $station)
                                                        <option value="{{ $station['name'] }}">
                                                            {{ $station['name'] }} - {{ $station['code'] }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="harga">Harga</label>
                                        <input type="text" class="form-control" id="harga" name="harga"
                                            onkeypress="return inputNumber(event)" placeholder="Harga" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="tanggal_keberangkatan">Tanggal Keberangkatan</label>
                                        <input type="date" class="form-control" id="tanggal_keberangkatan"
                                            name="tanggal_keberangkatan" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="jam">Jam Berangkat</label>
                                        <input type="time" class="form-control" id="jam" name="jam" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="transportasi_id">Transportasi</label><br>
                                        <select class="select2 form-control" id="transportasi_id" name="transportasi_id"
                                            required style="width: 100%; color: #6e707e;">
                                            <option value="" disabled selected>-- Pilih Transportasi --</option>
                                            @foreach ($transportasi as $data)
                                                <option value="{{ $data->id }}">{{ $data->kode }} -
                                                    {{ $data->name }} -
                                                    {{ $data->kelas->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @elseif ($item->name == 'PESAWAT')
                                <!-- Form untuk kategori Pesawat -->
                                <div class="alert alert-warning" role="alert">
                                    Maaf, fitur untuk kategori Pesawat belum tersedia.
                                </div>
                            @endif
                        </div>
                    @endforeach
                    <!-- Tambahkan tab-pane lain sesuai kebutuhan -->
                </div>
            </div>
        </div>
    </div>

@endsection
