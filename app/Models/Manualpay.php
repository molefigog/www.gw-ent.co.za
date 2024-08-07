<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manualpay extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'MSISDN',
        'transact_id',
        'received_amount',
        'from_number',
        'otp',
        'used',
    ];
    public function markAsUsed()
    {
        $this->used = true;
        $this->save();
    }
}
