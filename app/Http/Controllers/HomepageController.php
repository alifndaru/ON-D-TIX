<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Http;
use App\Models\Rute;
use App\Models\Terminal;
use App\Models\Transportasi;
use App\Models\Seat;

class HomepageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::all();
        return view("client.index-beranda", compact("category"));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }
    public function show($slug)
    {
        $category = Category::where('slug', $slug)->first();

        if (!$category) {
            abort(404);
        }

        if ($category->name == 'KERETA') {
            $response = Http::post('https://booking.kai.id/api/stations2');
            $stations = $response->json();
            $stations = collect($stations)->sortBy('name')->groupBy('cityname')->each(function ($cityStations) {
                return $cityStations->sortBy('name');
            })->sortKeys();
            return view('client.search_kereta', ['stations' => $stations]);
        } else if ($category->name == 'BUS') {
            $terminalsGroupedByProvince = Terminal::all()->groupBy('province');
            return view('client.search_bus', compact('terminalsGroupedByProvince'));
        } else {
            echo 'tidak ada rute yang tersedia untuk kategori ini.';
        }
    }


    public function kursi($id)
    {
        $rute = Rute::find($id);
        $transportasi = Transportasi::find($rute->transportasi_id);
        $seats = Seat::where('transportasi_id', $rute->transportasi_id)->get();

        return view('client.kursi', ['rute' => $rute, 'transportasi' => $transportasi, 'seats' => $seats]);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
