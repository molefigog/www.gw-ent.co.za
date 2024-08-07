<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SmsData;
use App\Models\WebhookData;

class DataController extends Controller
{
    public function index()
    {
        $smsData = SmsData::orderBy('created_at', 'desc')->paginate(10)->onEachSide(6);
        $webhookData = WebhookData::orderBy('created_at', 'desc')->paginate(10)->onEachSide(6);

        return view('data.index', compact('smsData', 'webhookData'));
    }
}
