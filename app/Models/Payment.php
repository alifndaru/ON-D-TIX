<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'external_id',
        'checkout_url',
        'status',
        'transportasi_id',
        'rute_id'
    ];


    public function seats()
    {
        return $this->belongsToMany(Seat::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function paymentSeat()
    {
        return $this->hasOne(PaymentSeat::class);
    }
}
