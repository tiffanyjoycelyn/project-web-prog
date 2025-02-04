<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CompostProducerFarmer extends Pivot
{
    protected $table = 'compost_producer_farmer';

    protected $fillable = [
        'CompostProducerID',
        'FarmerID'
    ];
}
