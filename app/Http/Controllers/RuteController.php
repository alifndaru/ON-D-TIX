<?php

namespace App\Http\Controllers;

use App\Models\Rute;
use App\Models\Category;
use App\Models\Terminal;
use App\Models\Transportasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;



class RuteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = Http::post('https://booking.kai.id/api/stations2');
        $stations = $response->json();
        $stations = collect($stations)->sortBy('name')->groupBy('cityname')->each(function ($cityStations) {
            return $cityStations->sortBy('name');
        })->sortKeys();
        $transportasi = Transportasi::orderBy('kode')->orderBy('name')->get();
        $rute = Rute::with('transportasi.category')->orderBy('created_at', 'desc')->get();
        $categories = Category::all();
        return view('server.rute.index', compact('rute', 'transportasi', 'stations', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category  = Category::all();
        $response = Http::post('https://booking.kai.id/api/stations2');
        $stations = $response->json();
        $stations = collect($stations)->sortBy('name')->groupBy('cityname')->each(function ($cityStations) {
            return $cityStations->sortBy('name');
        })->sortKeys();


        $busCategoryId = Category::where('name', 'BUS')->value('id');
        $keretaCategoryId = Category::where('name', 'KERETA')->value('id');

        $transportasiKereta = Transportasi::where('category_id', $keretaCategoryId)->orderBy('kode')->orderBy('name')->get();
        $transportasiBus = Transportasi::where('category_id', $busCategoryId)->orderBy('kode')->orderBy('name')->get();
        // dd($transportasiKereta);


        // $transportasi = Transportasi::orderBy('kode')->orderBy('name')->get();
        $rute = Rute::with('transportasi.category')->orderBy('created_at', 'desc')->get();
        $terminal = Terminal::all();
        return view('server.rute.create', compact('category', 'stations', 'rute', 'terminal', 'transportasiBus', 'transportasiKereta'));
    }


    public function store(Request $request)
    {
        $rules = [
            'tujuan' => 'required',
            'start' => 'required',
            'end' => 'required',
            'harga' => 'required',
            'tanggal_keberangkatan' => ['required', 'date_not_past'],
            'transportasi_id' => 'required',
            'category_id' => 'required'
        ];

        if ($request->tanggal_keberangkatan === now()->toDateString()) {
            $rules['jam'] = 'required|after:now';
        } else {
            $rules['jam'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Rute::create([
            'tujuan' => $request->tujuan,
            'start' => $request->start,
            'end' => $request->end,
            'harga' => $request->harga,
            'tanggal_keberangkatan' => $request->tanggal_keberangkatan,
            'jam' => $request->jam,
            'transportasi_id' => $request->transportasi_id,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('rute.index')->with('success', 'Success Add Rute!');
    }




    // public function show($id)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    // {
    //     $rute = Rute::find($id);
    //     $response = Http::post('https://booking.kai.id/api/stations2');
    //     $stations = $response->json();
    //     $stations = collect($stations)->sortBy('name')->groupBy('cityname')->each(function ($cityStations) {
    //         return $cityStations->sortBy('name');
    //     })->sortKeys();
    //     $transportasi = Transportasi::orderBy('kode')->orderBy('name')->get();
    //     $transportasi = Transportasi::orderBy('kode')->orderBy('name')->get();
    //     $categories = Category::all();
    //     return view('server.rute.edit', compact('rute', 'transportasi', 'stations', 'categories'));
    // }

    public function edit($id)
    {
        $rute = Rute::find($id);
        $categories = Category::all();
        $terminal = Terminal::all();
        $transportasi = Transportasi::orderBy('kode')->orderBy('name')->get();

        $categoryName = $rute->category->name;


        // Asumsi 'category' adalah kolom pada model Rute yang menentukan jenis transportasi
        if ($categoryName == 'KERETA') {
            $response = Http::post('https://booking.kai.id/api/stations2');
            $stations = collect($response->json())->sortBy('name')->groupBy('cityname')->each(function ($cityStations) {
                return $cityStations->sortBy('name');
            })->sortKeys();
        } else if ($categoryName == 'BUS') {

            $response = Terminal::all();
            $stations = $response->sortBy('name')->groupBy('cityname')->each(function ($cityStations) {
                return $cityStations->sortBy('name');
            })->sortKeys();
        } else {
            // Jika kategori tidak dikenali, set stations menjadi koleksi kosong
            $stations = collect([]);
        }

        return view('server.rute.edit', compact('rute', 'transportasi', 'stations', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'tujuan' => 'required',
            'start' => 'required',
            'end' => 'required',
            'harga' => 'required',
            'jam' => 'required',
            'transportasi_id' => 'required',
            'category_id' => 'required'
        ]);

        $rute = Rute::find($id);
        $rute->tujuan = $request->tujuan;
        $rute->category_id = $request->category_id;
        $rute->start = $request->start;
        $rute->end = $request->end;
        $rute->harga = $request->harga;
        $rute->jam = $request->jam;
        $rute->tanggal_keberangkatan = $request->tanggal_keberangkatan;
        $rute->transportasi_id = $request->transportasi_id;
        $rute->save();

        return redirect()->route('rute.index')->with('success', 'Success Update Rute!');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Rute::find($id)->delete();
        return redirect()->back()->with('success', 'Success Delete Rute!');
    }
}
