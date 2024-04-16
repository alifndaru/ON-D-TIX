<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rute extends Model
{
    use HasFactory;

    protected $fillable = [
        'tujuan',
        'start',
        'end',
        'harga',
        'jam',
        'tanggal_keberangkatan',
        'transportasi_id',
        'category_id'
    ];

    public function transportasi()
    {
        return $this->belongsTo('App\Models\Transportasi', 'transportasi_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }



    protected $table = 'rute';
}
