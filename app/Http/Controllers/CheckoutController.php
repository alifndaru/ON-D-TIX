<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Illuminate\Http\Request;
use App\Models\Rute;
use App\Models\Transportasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;


class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $transportasiId = $request->transportasi_id;
        $ruteId = $request->rute_id;
        $transportasi = Transportasi::find($transportasiId);
        $rute = Rute::find($ruteId);
        if (!$transportasi || !$rute) {
            return back()->withErrors(['message' => 'Transportasi atau rute tidak ditemukan.']);
        }

        $selectedSeats = json_decode($request->query('selectedSeats'), true);
        // Kirim data ke view
        return view('checkout.index', compact('user', 'transportasi', 'rute', 'selectedSeats'));
    }

}
