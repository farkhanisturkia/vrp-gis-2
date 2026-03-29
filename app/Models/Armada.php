<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Armada extends Model
{
    protected $fillable = [
        'name',
        'capacity',
        'no_plat'
    ];

    public function ordersArmada()
    {
        return $this->hasMany(Order::class, 'armada_id');
    }
}
