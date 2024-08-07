<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsData extends Model
{
    protected $fillable = [
        'text',
        'MSISDN',

    ];
}
