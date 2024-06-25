<?php

namespace App\Http\Controllers;

use App\Models\Rute;
use App\Models\Terminal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;


class SearchController extends Controller
{

    public function index()
    {
        $response = Http::post('https://booking.kai.id/api/stations2');
        $stations = $response->json();
        $stations = collect($stations)->sortBy('name')->groupBy('cityname')->each(function ($cityStations) {
            return $cityStations->sortBy('name');
        })->sortKeys();

        $terminal = Terminal::all();
        dd($terminal);

        return view('client.index', compact('stations'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'tujuan' => 'required|string',
            'tanggal_keberangkatan' => 'required|date'
        ]);

        $tujuan = $request->tujuan;
        $tanggal_keberangkatan = $request->tanggal_keberangkatan;

        $checkTujuan = Rute::where('tujuan', $tujuan)->exists();
        if (!$checkTujuan) {
            return back()->with('error', 'Tidak ada tiket untuk tujuan yang dipilih.');
        }

        $rutes = Rute::where('tujuan', $tujuan)
            ->whereDate('tanggal_keberangkatan', '=', $tanggal_keberangkatan)
            ->get();

        if ($rutes->isEmpty()) {
            return back()->with('error', 'Tidak ada rute untuk tanggal yang dipilih.');
        }

        return view('client.search_result', ['rutes' => $rutes]);
    }


    public function search_v1(Request $request)
    {
        $request->validate([
            'tujuan' => 'required|string'
        ]);

        $tujuan = $request->tujuan;

        $checkTujuan = Rute::where('tujuan', $tujuan)->exists();
        if (!$checkTujuan) {
            return back()->with('error', 'Tidak ada tiket untuk tujuan yang dipilih.');
        }
        $rutes = Rute::where('tujuan', $tujuan)->get();

        return view('client.search_result', ['rutes' => $rutes]);
    }
}
