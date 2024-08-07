<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoSms extends Model
{
    protected $fillable = [
        'To',
        'ID',
        'Message',
        'Msisdn',
        'Received',
        'UserReference',
    ];

    // You can add more configurations or methods here if needed
}
