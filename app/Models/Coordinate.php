<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coordinate extends Model
{
    protected $fillable = [
        'area',
        'long',
        'lat'
    ];

    public function ordersFrom()
    {
        return $this->hasMany(Order::class, 'from_id');
    }

    public function ordersTo()
    {
        return $this->hasMany(Order::class, 'to_id');
    }

    public function mandatoryOrders()
    {
        return $this->belongsToMany(
            Order::class,
            'order_mandatories',
            'coordinate_id',
            'order_id'
        );
    }
}
