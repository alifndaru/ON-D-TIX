<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Pemesanan;
use App\Models\Rute;
use App\Models\Transportasi;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $rute = Rute::count();
        $pendapatan = Order::where('status', 'completed')->sum('total');
        $transportasi = Transportasi::count();
        $user = User::count();


        $pendapatanPerBulan = Order::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total) as total')
            ->where('status', 'completed')
            ->groupBy('year', 'month')
            ->orderByRaw('year ASC, month ASC')
            ->get();

        $categories = DB::table('category')->get();


        // $ruteData = DB::table('payments')
        //     ->join('rute', 'payments.rute_id', '=', 'rute.id')
        //     ->where('payments.status', 'settled')
        //     ->select('rute.tujuan', DB::raw('count(payments.rute_id) as total'))
        //     ->groupBy('rute.tujuan')
        //     ->orderBy('total', 'desc')
        //     ->get();

        $ruteData = DB::table('payments')
            ->join('rute', 'payments.rute_id', '=', 'rute.id')
            ->join('category', 'rute.category_id', '=', 'category.id') // Menggabungkan tabel kategori
            ->where('payments.status', 'settled')
            ->select('rute.tujuan', 'category.name as category', DB::raw('count(payments.rute_id) as total')) // Memilih kolom kategori
            ->groupBy('rute.tujuan', 'category.name') // Grouping juga berdasarkan nama kategori
            ->orderBy('total', 'desc')
            ->get();



        return view('server.home', compact('rute', 'pendapatan', 'transportasi', 'user', 'pendapatanPerBulan', 'ruteData', 'categories'));
    }

    public function getPendapatanData()
    {
        $pendapatanPerBulan = Order::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total) as total')
            ->where('status', 'completed')
            ->groupBy('year', 'month')
            ->orderByRaw('year ASC, month ASC')
            ->get();

        return response()->json($pendapatanPerBulan);
    }
}
