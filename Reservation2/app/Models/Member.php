<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $table = 'members';

    protected $fillable = [
        'lastname',
        'firstname',
        'address',
        'phone',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}
