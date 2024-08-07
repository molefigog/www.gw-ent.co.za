<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
class CpayController extends Controller
{

    private $cell;
    private $amount;
    public function makePayment(Request $request)
    {
        try {
            $url = 'http://3.87.142.72:5100/api/cpaypayments/payment?cardPayment=false';
            $Client_code = config('cpay.cpay_code');
            $api_key = config('cpay.cpay_apikey');
            $secret = config('cpay.cpay_secret');
            $this->cell = $request->cell;
            $this->amount = $request->amount;
            $transactionId = "GWENT" . rand();
            $Message = $transactionId . $Client_code . $this->amount . $this->cell;
            $checksum = hash_hmac('sha256', $Message, $secret, true);
            $base64 = base64_encode($checksum);
            $data = [
                "transactionRequest" => [
                    "extTransactionId" => $transactionId,
                    "clientCode" => $Client_code,
                    "msisdn" => $this->cell,
                    "otp" => "",
                    "amount" => $this->amount,
                    "checksum" => $base64,
                    "currency" => "maloti",
                    "otpMedium" => "sms",
                    "redirectUrl" => 'https://www.gw-ent.co.za/api/return',
                ]
            ];
            if (!$this->verifySignature($Message,  $base64)) {
                return response()->json(['error' => 'Signature verification failed'], 400);
            }
            $response = Http::withHeaders([
                'accept' => 'text/plain',
                'Authorization' => $api_key,
                'Content-Type' => 'application/json-patch+json',
            ])->post($url, $data);

            Log::info('Request URL: ' . $url);
            Log::info('Request Data: ' . json_encode($data));

            if ($response->status() === 200) {
                return redirect()->route('confirm', ['cell' => $this->cell, 'amount' => $this->amount]);
            } else {
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function confirmPayment(Request $request)
    {
        try {
            $url = 'http://3.87.142.72:5100/api/cpaypayments/confirm';
            $Client_code = config('cpay.cpay_code');
            $api_key = config('cpay.cpay_apikey');
            $secret = config('cpay.cpay_secret');
            $otp = $request->otp;
            $amount = $request->amount;
            $cell = $request->cell;
            $transactionId = "GWENT" . rand();
            $Message = $transactionId . $Client_code . $amount . $cell . $otp;
            $checksum = hash_hmac('sha256', $Message, $secret, true);
            $base64 = base64_encode($checksum);
            $data = [
                "transactionRequest" => [
                    "extTransactionId" => $transactionId,
                    "clientCode" => $Client_code,
                    "msisdn" => $cell,
                    "otp" => $otp,
                    "amount" => $amount,
                    "checksum" => $base64,
                    "currency" => "maloti",
                    "otpMedium" => "sms",
                    "redirectUrl" => 'https://www.gw-ent.co.za/api/return',
                ]
            ];
            Log::info('Request URL: ' . $url);
            Log::info('Request Data: ' . json_encode($data));

            if (!$this->verifySignature($Message,  $base64)) {
                return response()->json(['error' => 'Signature verification failed'], 400);
            }
            $response = Http::withHeaders([
                'accept' => 'text/plain',
                'Authorization' => $api_key,
                'Content-Type' => 'application/json-patch+json',
            ])->post($url, $data);

             if ($response->status() === 200) {
                $responseData = $response->json(); // Get JSON response data
                Log::info('CPay API Response: ' . json_encode($responseData)); // Log JSON response
            }

            return response()->json(['response' => $response->json()]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function verifySignature($Message, $receivedChecksum)
    {
        $secret = config('cpay.cpay_secret');
        $expectedChecksum = hash_hmac('sha256', $Message, $secret, true);
        $expectedBase64 = base64_encode($expectedChecksum);

        return $receivedChecksum === $expectedBase64;
    }
}
