<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'external_id',
        'checkout_url',
        'status',
        'seat',
        'transportasi_id',
        'rute_id'
    ];


    public function seats()
    {
        return $this->belongsToMany(Seat::class);
    }
}
