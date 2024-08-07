<?php

namespace App\Http\Controllers;

use App\Models\SmsData;
use Illuminate\Http\Request;
use App\Models\WebhookData;
use App\Models\Manualpay;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Carbon\Carbon;


class WebhookController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming payload
        $validatedData = $request->validate([
            'text' => 'required',
            'MSISDN' => 'required',
        ]);

        // Extract relevant information from SMS content
        $smsContent = $validatedData['text'];

        preg_match('/Transact ID ([A-Z0-9]+)/', $smsContent, $transactIdMatches);
        $transactId = $transactIdMatches[1] ?? null;

        preg_match('/received M(\d+\.\d+)/i', $smsContent, $receivedMatches);
        $receivedAmount = $receivedMatches[1] ?? null;

        // Convert the received amount to a decimal without the "M" symbol
        $receivedAmountDecimal = floatval($receivedAmount);

        preg_match('/from (\d+)/i', $smsContent, $fromMatches);
        $fromNumber = $fromMatches[1] ?? null;



        if ($validatedData['MSISDN'] !== 'MPESA') {
            // Store in sms_data table
            $smsData = SmsData::create([
                'text' => $validatedData['text'],
                'MSISDN' => $validatedData['MSISDN'],
            ]);
        } else {
            // Store in webhook_data table
            $webhookData = WebhookData::create([
                'text' => $validatedData['text'],
                'MSISDN' => $validatedData['MSISDN'],
                'transact_id' => $transactId,
                'received_amount' => $receivedAmountDecimal,
                'from_number' => $fromNumber,
            ]);

            // Check if the fromNumber matches any user's mobile number
            $user = User::where('mobile_number', $fromNumber)->first();

            if ($user) {
                // Update user's balance
                $user->balance += $receivedAmountDecimal;
                $user->save();

                // Update used flag in webhook_data
                $webhookData->used = 1;
                $webhookData->save();

                // Message for users with an account 
                $message = "You have successfully paid $receivedAmountDecimal to GW-ENT. Visit https://www.gw-ent.co.za";
            } else {
                // Message for numbers that don't match a user or users without an account
                $message = "You have successfully paid $receivedAmountDecimal to GW-ENT.  $transactId .  https://www.gw-ent.co.za/top-up";
            }

            // Send SMS reply to the contact number from webhook data using sendSMS2 function
            $this->sendSMS2([
                'message' => $message,
                'to' => $webhookData->from_number,
            ]);
        }

        return response()->json(['message' => 'Data stored successfully']);
    }

   
    public function manualPay(Request $request)
    {
        $validatedData = $request->validate([
            'text' => 'required',
            'MSISDN' => 'required',
        ]);

        $otp = chr(rand(65, 90)) . rand(1000, 9999);
        $smsContent = $validatedData['text'];

        preg_match('/Transact ID ([A-Z0-9]+)/', $smsContent, $transactIdMatches);
        $transactId = $transactIdMatches[1] ?? null;
        preg_match('/received M(\d+\.\d+)/i', $smsContent, $receivedMatches);
        $receivedAmount = $receivedMatches[1] ?? null;
        $receivedAmountDecimal = floatval($receivedAmount);
        preg_match('/from (\d+)/i', $smsContent, $fromMatches);
        $fromNumber = $fromMatches[1] ?? null;

        $manualPay = null;

        if ($validatedData['MSISDN'] == 'MPESA') {
            $manualPay = ManualPay::create([
                'text' => $validatedData['text'],
                'MSISDN' => $validatedData['MSISDN'],
                'transact_id' => $transactId,
                'received_amount' => $receivedAmountDecimal,
                'from_number' => $fromNumber,
                'otp' => $otp,
            ]);

            $manualPay->save();
            // $finalOtp = $manualPay->id . $otp;

            $message = "GW ENT. Enter this $otp to Complete a transaction. https://www.gw-ent.co.za";
            $this->sendSMS2([
                'message' => $message,
                'to' => $manualPay->from_number,
            ]);
        }

        return response()->json(['message' => 'Data stored successfully']);
    }
   
    
    
    public function sendSMS2($params)
    {
        $apiKey = config('sms.api_key');
        $apiSecret = config('sms.api_secret');
        $accountApiCredentials = $apiKey . ':' . $apiSecret;

        $base64Credentials = base64_encode($accountApiCredentials);
        $authHeader = 'Authorization: Basic ' . $base64Credentials;

        $message = $params['message'];
        $to = $params['to'];

        $sendData = json_encode([
            'messages' => [
                [
                    'content' => $message,
                    'destination' => $to
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
            // SMS sending was successful
            return response()->json(['message' => 'SMS sent successfully']);
        } else {
            // SMS sending failed
            return response()->json(['message' => 'SMS sending failed'], 500);
        }
    }
}
