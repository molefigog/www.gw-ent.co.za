<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class SMSController extends Controller
{
    public function sendSMSForm()
    {
        return view('sms-form');
    }

    public function sendSMSForm2()
    {
        return view('sms-form2');
    }

    public function sendSMS(Request $request)
    {
        $apiKey = config('httpsms.api_key');

        $payload = [
            'content' => $request->input('content'),
            'from' => $request->input('from'),
            'to' => $request->input('to')
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'x-api-key' => $apiKey,
        ])->post('https://api.httpsms.com/v1/messages/send', $payload);

        return view('sms-result', ['response' => $response->body()]);
    }

    public function sendSMS2(Request $request)
    {
        $apiKey = config('sms.api_key');
        $apiSecret = config('sms.api_secret');
        
        $accountApiCredentials = $apiKey . ':' . $apiSecret;

        $base64Credentials = base64_encode($accountApiCredentials);
        $authHeader = 'Authorization: Basic ' . $base64Credentials;

        $bulkNumbers = preg_split('/\r\n|\r|\n|,/', $request->input('to'));
        $message = $request->input('message');

        $successCount = 0;
        $errorCount = 0;

        foreach ($bulkNumbers as $numTo) {
            $numTo = trim($numTo);

            $sendData = json_encode([
                'messages' => [
                    [
                        'content' => $message,
                        'destination' => $numTo
                    ]
                ]
            ]);

            $options = [
                'http' => [
                    'header' => ["Content-Type: application/json", $authHeader],
                    'method' => 'POST',
                    'content' => $sendData,
                    'ignore_errors' => true
                ]
            ];

            $sendResult = file_get_contents('https://rest.mymobileapi.com/bulkmessages', false, stream_context_create($options));

            $status_line = $http_response_header[0];
            preg_match('{HTTP\/\S*\s(\d{3})}', $status_line, $match);
            $status = $match[1];

            if ($status === '200') {
                $successCount++;
            } else {
                $errorCount++;
            }
        }

        return view('sms_result', [
            'successCount' => $successCount,
            'errorCount' => $errorCount
        ]);
    }
}
