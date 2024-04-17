<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $selectedSeats = json_decode($request->query('selectedSeats'), true);

        // dd($selectedSeats);
        return view('checkout.index', compact('selectedSeats'));
    }
}
