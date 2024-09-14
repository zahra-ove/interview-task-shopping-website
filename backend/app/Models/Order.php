<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;


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
