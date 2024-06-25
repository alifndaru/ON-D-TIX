<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transportasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'kode',
        'jumlah',
        'category_id',
        'kelas_id',
    ];

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function kelas()
    {
        return $this->belongsTo('App\Models\Kelas', 'kelas_id');
    }

    public function kursi($id)
    {
        $data = json_decode($id, true);
        // dd($data);
        $kursi = PaymentSeat::whereHas('payment', function ($query) use ($data) {
            // $query->where('rute_id', $data['rute'])->where('jam', $data['jam'], 'tanggal_keberangkatan', $data['tanggal_keberangkatan']);
            $query->where('rute_id', $data['rute']);
        })->where('seat_id', $data['kursi'])->exists();

        // dd($kursi);


        // $kursi = Pemesanan::where('rute_id', $data['rute'])->where('waktu', $data['waktu'])->where('kursi', $data['kursi'])->exists();
        if (!$kursi) {
            return null;
        } else {
            return $id;
        }
    }
    public function transportasi()
    {
        return $this->hasMany('App\Models\Transportasi');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }



    protected $table = 'transportasi';
}
