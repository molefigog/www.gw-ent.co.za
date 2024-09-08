<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InboundEmail;
use Illuminate\Support\Facades\Log;
class InboundEmailController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Incoming email data:', $request->all());
        $from = $request->input('from');
        $to = $request->input('to');
        $subject = $request->input('subject');
        $text = $request->input('text');
        $html = $request->input('html');
        $attachments = $request->input('attachments');
        $email = new InboundEmail();
        $email->from = $from;
        $email->to = $to;
        $email->subject = $subject;
        $email->body = $text ?? $html;
        $email->save();
        InboundEmail::create([
            'from' => $from,
            'to' => $to,
            'subject' => $subject,
            'body' => $text ?? $html,
        ]);
        return response()->json(['status' => 'success'], 200);
    }
}
