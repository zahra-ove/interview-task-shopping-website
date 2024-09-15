<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $fillable = [
        'name',
        'price',
        'inventory'
    ];



    public function getTotalAvailableAttribute($query)
    {
        return array_sum(array_column($this->inventory, 'count'));
    }
}
