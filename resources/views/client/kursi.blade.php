@extends('layouts.app')
@section('title', 'Cari Kursi')
@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Pilih Kursi Anda</h2>
                        <div class="row">
                            @php
                                $i = 1;
                            @endphp
                            @while ($i <= $transportasi->jumlah)
                                @php
                                    $array = [
                                        'kursi' => $i,
                                        'rute' => $rute->id,
                                        'jam' => $rute->jam,
                                        'tanggal_keberangkatan' => $rute->tanggal_keberangkatan,
                                    ];
                                    $cekData = json_encode($array);
                                    $disabled = $transportasi->kursi($cekData) == null ? false : true;
                                    $selected = isset($selectedSeats[$i]) ? 'bg-primary' : '';
                                @endphp
                                <div class="col-md-4 mb-3 kursi border {{ $disabled ? 'bg-light' : 'disabled' }} text-center p-3 rounded cursor-pointer"
                                    onclick="toggleSeatSelection('{{ $i }}', '{{ $cekData }}', '{{ $disabled }}')"
                                    data-seat="{{ $i }}">
                                    <h4 class="font-weight-bold text-dark mb-0">
                                        {{ $i }} = {{ (int) $disabled }}
                                    </h4>
                                    @if ($selected)
                                        <i class="fas fa-check text-success mt-2"></i>
                                    @endif
                                </div>
                                @php
                                    $i++;
                                @endphp
                            @endwhile
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            <button class="btn btn-success" onclick="goToCheckout()">Go to Checkout</button>
                        </div>
                        <div id="selected-seats" class="text-center mt-3"></div>
                    </div>
                    <a href="{{ route('home') }}" class="btn btn-link mt-3"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
            </div>
        </div>
    @endsection

    @section('style')
        <style>
            .kursi {
                transition: background-color 0.3s, box-shadow 0.3s;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
                min-height: 120px;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }

            .kursi:hover {
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            }

            .kursi.bg-primary {
                color: #fff;
            }

            .kursi.bg-light {
                background-color: #f8d7da;
            }

            .kursi i {
                opacity: 0;
                transition: opacity 0.3s;
            }

            .kursi:hover i {
                opacity: 1;
            }

            .kursi:not(.disabled):hover {
                background-color: #007bff;
                /* Tambahkan efek hover pada kursi yang dapat dipilih */
            }

            .cursor-pointer {
                cursor: pointer;
            }
        </style>
    @endsection




    @section('script')

        <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.min.js"></script>
        <script>
            var selectedSeats = {};

            function toggleSeatSelection(seatNumber, seatData, disabled) {
                if (disabled) {
                    console.log('Kursi ini sudah dipesan');
                    // Jika kursi sudah dipesan, jangan lakukan apa-apa
                    return;
                }

                if (selectedSeats[seatNumber]) {
                    delete selectedSeats[seatNumber];
                    document.querySelector('.kursi[data-seat="' + seatNumber + '"]').classList.remove('bg-primary');
                    document.querySelector('.kursi[data-seat="' + seatNumber + '"] i').remove();
                } else {
                    selectedSeats[seatNumber] = seatData;
                    document.querySelector('.kursi[data-seat="' + seatNumber + '"]').classList.add('bg-primary');
                    var checkIcon = document.createElement('i');
                    checkIcon.className = 'fas fa-check text-success';
                    checkIcon.style.position = 'absolute';
                    checkIcon.style.top = '5px';
                    checkIcon.style.right = '5px';
                    document.querySelector('.kursi[data-seat="' + seatNumber + '"]').appendChild(checkIcon);
                }
                updateSelectedSeatsDisplay();
            }

            function updateSelectedSeatsDisplay() {
                var selectedSeatsList = Object.keys(selectedSeats);
                var selectedSeatsDisplay = 'Kursi yang dipilih: ' + (selectedSeatsList.length > 0 ? selectedSeatsList.join(
                    ', ') : 'Tidak ada kursi yang dipilih');
                document.getElementById('selected-seats').innerText = selectedSeatsDisplay;
            }

            function bookSelectedSeats() {
                console.log(selectedSeats);
            }


            // Function to go to checkout page
            function goToCheckout() {
                if (Object.keys(selectedSeats).length === 0) {
                    alert('Silakan pilih setidaknya satu kursi sebelum melanjutkan ke checkout.');
                    return;
                }
                var encryptionKey =
                    "abcbakbaxiah98kwdnkjbbcjasakhwew8inckswiu*OOIWUhiqwuqwbhbh&@#*QBChba"; // Replace with your encryption key
                var encryptedSeats = encryptData(selectedSeats, encryptionKey);

                //  console.log(encryptedSeats);
                var transportasiId = "{{ $transportasi->id }}";
                var ruteId = "{{ $rute->id }}";

                // Construct the URL with the encryptedSeats and other parameters
                var url = '{{ route('checkout') }}' + '?encryptedSeats=' + encodeURIComponent(encryptedSeats) +
                    '&transportasi_id=' + encodeURIComponent(transportasiId) +
                    '&rute_id=' + encodeURIComponent(ruteId);

                // Redirect the user to the checkout page
                window.location.href = url;
            }

            function encryptData(data) {
                var encryptedData = btoa(JSON.stringify(data)); // Menggunakan fungsi btoa() untuk enkripsi Base64
                return encryptedData;
            }
        </script>
    @endsection
