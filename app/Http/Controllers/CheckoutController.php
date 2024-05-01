<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rute;
use App\Models\Seat;
use App\Models\Transportasi;
use Illuminate\Support\Facades\Auth;

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

        // Pastikan data terenkripsi tersedia dan valid
        $encryptedSeats = $request->query('encryptedSeats');
        if (!$encryptedSeats) {
            return back()->withErrors(['message' => 'Data terenkripsi tidak tersedia.']);
        }

        try {
            // Dekripsi data terenkripsi menggunakan base64_decode
            $decryptedSeatsJson = base64_decode($encryptedSeats);
            $decryptedSeats = json_decode($decryptedSeatsJson, true);
        } catch (\Exception $e) {
            // Tangani kesalahan dekripsi
            return back()->withErrors(['message' => 'Gagal mendekripsi data.']);
        }

        $totalPrice = count($decryptedSeats) * $rute->harga;
        $formattedTotalPrice = number_format($totalPrice, 0, ',', '.');


        // Kirim data ke view
        return view('checkout.index', compact('user', 'transportasi', 'rute', 'decryptedSeats', 'formattedTotalPrice', 'totalPrice'));
    }
}
