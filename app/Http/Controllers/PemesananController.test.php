<?php

namespace App\Http\Controllers;

use App\Models\Rute;
use App\Models\Category;
use App\Models\Pemesanan;
use App\Models\Transportasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class PemesananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ruteAwal = Rute::orderBy('start')->get()->groupBy('start');
        if (count($ruteAwal) > 0) {
            foreach ($ruteAwal as $key => $value) {
                $data['start'][] = $key;
            }
        } else {
            $data['start'] = [];
        }
        $ruteAkhir = Rute::orderBy('end')->get()->groupBy('end');
        if (count($ruteAkhir) > 0) {
            foreach ($ruteAkhir as $key => $value) {
                $data['end'][] = $key;
            }
        } else {
            $data['end'] = [];
        }
        $category = Category::orderBy('name')->get();
        return view('client.index', compact('category', 'data'));
    }

    // public function search(Request $request)
    // {
    //     $categoryId = $request->category;

    //     // Mengambil data kategori transportasi berdasarkan kategori tertentu
    //     $transportasi = Transportasi::where('category_id', $categoryId)->get();

    //     // Mengumpulkan id kategori transportasi yang sesuai
    //     $transportasiIds = $transportasi->pluck('id');

    //     // Mengambil data rute berdasarkan kategori transportasi yang sesuai
    //     $rute = Rute::whereIn('transportasi_id', $transportasiIds)->get();

    //     return view('client.search_result', compact('rute'));
    // }


    public function getRoutesByCategory($categoryId)
    {
        try {
            // Ambil kategori berdasarkan ID
            $category = Category::findOrFail($categoryId);

            // Ambil rute yang terkait dengan kategori
            $routes = $category->routes;

            if ($routes->isEmpty()) {
                return response()->json(['start' => [], 'end' => []]);
            }

            // Ubah data rute menjadi array yang bisa digunakan oleh select2
            $start = $routes->pluck('start', 'id');
            $end = $routes->pluck('end', 'id');

            // Kembalikan data dalam format JSON
            return response()->json([
                'start' => $start,
                'end' => $end,
                'message' => 'Routes found for this category'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



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
    public function store(Request $request)
    {
        if ($request->category) {
            $category = Category::find($request->category);
            $data = [
                'start' => $request->start,
                'end' => $request->end,
                'category' => $category->id,
                'waktu' => $request->waktu,
            ];
            $data = Crypt::encrypt($data);
            return redirect()->route('show', ['id' => $category->slug, 'data' => $data]);
        } else {
            $this->validate($request, [
                'rute_id' => 'required',
                'waktu' => 'required',
            ]);

            $huruf = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
            $kodePemesanan = strtoupper(substr(str_shuffle($huruf), 0, 7));

            $rute = Rute::with('transportasi.category')->find($request->rute_id);
            // $jumlah_kursi = $rute->transportasi->jumlah + 2;
            // $kursi = (int) floor($jumlah_kursi / 5);
            // $kode = "ABCDE";
            // $kodeKursi = strtoupper(substr(str_shuffle($kode), 0, 1) . rand(1, $kursi));

            $waktu = $request->waktu . " " . $rute->jam;

            Pemesanan::Create([
                'kode' => $kodePemesanan,
                // 'kursi' => $request,
                'waktu' => $waktu,
                'total' => $rute->harga,
                'rute_id' => $rute->id,
                'penumpang_id' => Auth::user()->id
            ]);

            return redirect()->back()->with('success', 'Pemesanan Tiket ' . $rute->transportasi->category->name . ' Success!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $data)
    {
        $data = Crypt::decrypt($data);
        $category = Category::findorfail($data['category']);
        $rute = Rute::with('transportasi')->where('start', $data['start'])->where('end', $data['end'])->get();
        $dataRute = [];
        if ($rute->count() > 0) {
            foreach ($rute as $val) {

                $pemesanan = Pemesanan::where('rute_id', $val->id)->where('waktu')->count();
                if ($val->transportasi) {
                    // dd($val->transportasi->category_id);
                    $kursi = Transportasi::find($val->transportasi_id)->jumlah - $pemesanan;
                    if ($val->transportasi->category_id == $category->id) {
                        // dd($val->transportasi->category_id == $category->id);
                        $dataRute[] = [
                            'harga' => $val->harga,
                            'start' => $val->start,
                            'end' => $val->end,
                            'tujuan' => $val->tujuan,
                            'transportasi' => $val->transportasi->name,
                            'kode' => $val->transportasi->kode,
                            'kursi' => $kursi,
                            'waktu' => $data['waktu'],
                            'id' => $val->id,
                        ];
                    }
                }
            }
            if (!empty($dataRute)) {
                sort($dataRute);
            }
            // sort($dataRute);
        } else {
            $dataRute = [];
        }
        $id = $category->name;
        return view('client.show', compact('id', 'dataRute'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Crypt::decrypt($id);
        $rute = Rute::find($data['id']);
        $transportasi = Transportasi::find($rute->transportasi_id);
        return view('client.kursi', compact('data', 'transportasi'));
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
        //
    }

    public function pesan($kursi, $data)
    {
        $d = Crypt::decrypt($data);
        $huruf = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $kodePemesanan = strtoupper(substr(str_shuffle($huruf), 0, 7));

        $rute = Rute::with('transportasi.category')->find($d['id']);

        $waktu = $d['waktu'] . " " . $rute->jam;

        Pemesanan::Create([
            'kode' => $kodePemesanan,
            'kursi' => $kursi,
            'waktu' => $waktu,
            'total' => $rute->harga,
            'rute_id' => $rute->id,
            'penumpang_id' => Auth::user()->id
        ]);

        return redirect('/')->with('success', 'Pemesanan Tiket ' . $rute->transportasi->category->name . ' Success!');
    }
}
