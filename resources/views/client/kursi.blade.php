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
                             @for ($i = 1; $i <= $transportasi->jumlah; $i++)
                                 @php
                                     $array = ['kursi' => 'K' . $i, 'rute' => $rute->id, 'waktu' => $rute->waktu];
                                     $cekData = json_encode($array);
                                     $disabled = $transportasi->kursi($cekData) != null ? 'disabled' : '';
                                     $selected = isset($selectedSeats['K' . $i]) ? 'bg-primary' : '';
                                 @endphp
                                 <div class="col-md-4 mb-3">
                                     <div class="kursi {{ $disabled ? 'bg-light' : $selected }} text-center p-3 rounded cursor-pointer"
                                         onclick="toggleSeatSelection('K{{ $i }}', '{{ $cekData }}')"
                                         data-seat="K{{ $i }}">
                                         <h4 class="font-weight-bold text-{{ $disabled ? 'dark' : 'white' }} mb-0">
                                             K{{ $i }}</h4>
                                         @if ($selected)
                                             <i class="fas fa-check text-success mt-2"></i>
                                         @endif
                                     </div>
                                 </div>
                             @endfor
                         </div>
                         <div class="text-center mt-4">
                             <button class="btn btn-success" onclick="goToCheckout()">Go to Checkout</button>

                         </div>
                         <div id="selected-seats" class="text-center mt-3"></div>
                     </div>
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
         }

         .kursi:hover {
             box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
         }

         .kursi.bg-primary {
             color: #fff;
         }

         .kursi i {
             opacity: 0;
             transition: opacity 0.3s;
         }

         .kursi:hover i {
             opacity: 1;
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

         function toggleSeatSelection(seatNumber, seatData) {
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
