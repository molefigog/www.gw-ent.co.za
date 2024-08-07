<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mpesa extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'ref',
        'gross_income',
        'net_income',
        'pay_lesotho'
    ];
}
