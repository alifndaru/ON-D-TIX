<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentSeat;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index()
    {
        // $pemesanan = Pemesanan::with('rute', 'penumpang')->orderBy('created_at', 'desc')->get();
        $order = Order::with(['transportasi', 'rute', 'user'])->where('status', 'completed')->orderBy('created_at', 'desc')->get();

        // dd($order);

        $kursi = collect(); // Initialize an empty collection to hold the results

        foreach ($order as $item) {
            // For each order, get the associated PaymentSeats and add them to the collection
            $orderKursi = PaymentSeat::where('order_id', $item->id)->get();
            $kursi = $kursi->merge($orderKursi);
        }
        return view('server.laporan.index', compact('order', 'kursi'));
    }

    public function petugas()
    {
        return view('client.petugas');
    }

    public function kode(Request $request)
    {
        return redirect()->route('transaksi.show', $request->order_id);
    }

    public function show($order)
    {
        $data = Order::with(['transportasi', 'rute'])->where('order_id', $order)->first();

        $kursi = PaymentSeat::where('order_id', $order)->get();
        if ($data) {
            return view('server.laporan.show', compact('data', 'kursi'));
        } else {
            return redirect()->back()->with('error', 'order_id Transaksi Tidak Ditemukan!');
        }
    }

    public function pembayaran($id)
    {
        Pemesanan::find($id)->update([
            'status' => 'Sudah Bayar',
            'petugas_id' => Auth::user()->id
        ]);

        return redirect()->back()->with('success', 'Pembayaran Ticket Success!');
    }

    public function history()
    {
        $pemesanan = Pemesanan::with('rute.transportasi')->where('penumpang_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        return view('client.history', compact('pemesanan'));
    }
}
