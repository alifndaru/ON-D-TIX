<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Kelas;
use App\Models\Transportasi;
use Illuminate\Http\Request;
use App\Models\Seat;

class TransportasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::orderBy('name')->get();
        $kelas = Kelas::orderBy('name')->get();
        $transportasi = Transportasi::with('category')->with('kelas')->orderBy('kode')->orderBy('name')->get();
        return view('server.transportasi.index', compact('category', 'transportasi', 'kelas'));
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     $this->validate($request, [
    //         'name' => 'required',
    //         'kode' => 'required',
    //         'jumlah' => 'required',
    //         'category_id' => 'required',
    //         'kelas_id' => 'required'
    //     ]);

    //     Transportasi::updateOrCreate(
    //         [
    //             'id' => $request->id
    //         ],
    //         [
    //             'name' => $request->name,
    //             'kode' => strtoupper($request->kode),
    //             'jumlah' => $request->jumlah,
    //             'category_id' => $request->category_id,
    //             'kelas_id' => $request->kelas_id,
    //         ]
    //     );

    //     if ($request->id) {
    //         return redirect()->route('transportasi.index')->with('success', 'Success Update Transportasi!');
    //     } else {
    //         return redirect()->back()->with('success', 'Success Add Transportasi!');
    //     }
    // }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'kode' => 'required',
            'jumlah' => 'required',
            'category_id' => 'required',
            'kelas_id' => 'required'
        ]);

        $transportasi = Transportasi::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'name' => $request->name,
                'kode' => strtoupper($request->kode),
                'jumlah' => $request->jumlah,
                'category_id' => $request->category_id,
                'kelas_id' => $request->kelas_id,
            ]
        );

        // If a new transportasi was created, add seats
        if (!$request->id) {
            for ($i = 1; $i <= $request->jumlah; $i++) {
                $seat = new Seat;
                $seat->seat_id = $i;
                $seat->transportasi_id = $transportasi->id;
                $seat->save();
            }
        }

        if ($request->id) {
            return redirect()->route('transportasi.index')->with('success', 'Success Update Transportasi!');
        } else {
            return redirect()->back()->with('success', 'Success Add Transportasi!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
        $category = Category::orderBy('name')->get();
        $kelas = Kelas::orderBy('name')->get();
        $transportasi = Transportasi::find($id);
        return view('server.transportasi.edit', compact('category', 'transportasi', 'kelas'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Transportasi::find($id)->delete();
        return redirect()->back()->with('success', 'Success Delete Transportasi!');
    }
}
