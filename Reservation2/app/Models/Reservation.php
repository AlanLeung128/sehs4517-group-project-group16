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
        'games',
        'coffees',
        'customer_id',
        'total_amount',
    ];

    protected $casts = [
        'games'   => 'array',
        'coffees' => 'array',
    ];
}