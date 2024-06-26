<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug'
    ];

    protected $table = 'category';

    public function routes()
    {
        return $this->hasMany(Rute::class, 'category_id', 'id');
    }
}
