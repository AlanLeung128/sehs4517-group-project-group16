<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'time_slot',
        'space_name',
        'game',
        'games',
        'coffees',
        'phone',
        'customer_id',
        'total_amount',
        'status',
    ];

    protected $casts = [
        'date'         => 'date',
        'games'        => 'array',
        'coffees'      => 'array',
        'total_amount' => 'decimal:2',
    ];

    public function scopeToday($query)
    {
        return $query->whereDate('date', now()->format('Y-m-d'));
    }
}