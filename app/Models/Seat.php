<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'is_booked',
    ];

    public function payments()
    {
        return $this->belongsToMany(Payment::class);
    }
}
