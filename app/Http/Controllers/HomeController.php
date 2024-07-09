<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Pemesanan;
use App\Models\Rute;
use App\Models\Transportasi;
use App\Models\User;
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

        return view('server.home', compact('rute', 'pendapatan', 'transportasi', 'user', 'pendapatanPerBulan'));
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
