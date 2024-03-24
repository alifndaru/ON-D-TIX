<?php

namespace App\Http\Controllers;

use App\Models\Rute;
use App\Models\Transportasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


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
        return view('server.rute.index', compact('rute', 'transportasi', 'stations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'tujuan' => 'required',
            'start' => 'required',
            'end' => 'required',
            'harga' => 'required',
            'jam' => 'required',
            'transportasi_id' => 'required'
        ]);

        Rute::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'tujuan' => $request->tujuan,
                'start' => $request->start,
                'end' => $request->end,
                'harga' => $request->harga,
                'jam' => $request->jam,
                'transportasi_id' => $request->transportasi_id,
            ]
        );

        if ($request->id) {
            return redirect()->route('rute.index')->with('success', 'Success Update Rute!');
        } else {
            return redirect()->back()->with('success', 'Success Add Rute!');
        }
    }
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rute = Rute::find($id);
        $response = Http::post('https://booking.kai.id/api/stations2');
        $stations = $response->json();
        $stations = collect($stations)->sortBy('name')->groupBy('cityname')->each(function ($cityStations) {
            return $cityStations->sortBy('name');
        })->sortKeys();
        $transportasi = Transportasi::orderBy('kode')->orderBy('name')->get();
        $transportasi = Transportasi::orderBy('kode')->orderBy('name')->get();
        return view('server.rute.edit', compact('rute', 'transportasi', 'stations'));
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
            'transportasi_id' => 'required'
        ]);

        $rute = Rute::find($id);
        $rute->tujuan = $request->tujuan;
        $rute->start = $request->start;
        $rute->end = $request->end;
        $rute->harga = $request->harga;
        $rute->jam = $request->jam;
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
