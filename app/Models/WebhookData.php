<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\Searchable;

class WebhookData extends Model
{
    protected $fillable = [
        'text',
        'MSISDN',
        'transact_id',
        'received_amount',
        'from_number',
        'used',
    ];
    public function markAsUsed()
    {
        $this->used = true;
        $this->save();
    }
}
