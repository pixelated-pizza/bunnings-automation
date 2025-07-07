<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $timestamps = false;

    protected $table = "bunnings_orders";

    protected $fillable = [
        'order_id',
        'status',
        'sales_channel',
        'date_placed',
        'ship_address',
        'order_lines',
    ];

    protected $casts = [
        'ship_address' => 'array',
        'order_lines' => 'array',
    ];
}
