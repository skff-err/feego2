<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardian extends User
{
    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone_number', 'address',
    ];
}

