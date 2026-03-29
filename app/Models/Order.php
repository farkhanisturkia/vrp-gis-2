<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'date',
        'status',
        'from_id',
        'to_id',
        'user_id',
        'armada_id'
    ];

    protected $casts = [
        'date'       => 'datetime',
    ];

    public function scopeForCurrentUser($query)
    {
        $user = Auth::user();

        if ($user && $user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        return $query;
    }

    public function from()
    {
        return $this->belongsTo(Coordinate::class, 'from_id');
    }

    public function to()
    {
        return $this->belongsTo(Coordinate::class, 'to_id');
    }

    public function mandatories()
    {
        return $this->belongsToMany(
            Coordinate::class,
            'order_mandatories',
            'order_id',
            'coordinate_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function armada()
    {
        return $this->belongsTo(Armada::class, 'armada_id');
    }
}
