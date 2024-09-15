<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $fillable = [
        'total_count',
        'total_price',
        'order_items',
        'rahgiri_code',
        'user_id'
    ];


    /***************************************************************************
     *                                                                         *
     *                             Model Relationships                         *
     *                                                                         *
     ***************************************************************************/

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
