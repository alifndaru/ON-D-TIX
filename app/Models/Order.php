<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'order_id',
        'user_id',
        'rute_id',
        'transportasi_id',
        'total',
        'status'
    ];

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function paymentSeats()
    {
        return $this->hasMany(PaymentSeat::class, 'order_id');
    }


    public function markAsCompleted()
    {
        $this->status = self::STATUS_COMPLETED;
        $this->save();
    }


    public function transportasi()
    {
        return $this->belongsTo(Transportasi::class, 'transportasi_id');
    }

    public function rute()
    {
        return $this->belongsTo(Rute::class, 'rute_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
