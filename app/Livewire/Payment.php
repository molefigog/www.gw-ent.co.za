<?php

namespace App\Livewire;

use Livewire\Component;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Music;
use App\Models\Downloads;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class Payment extends Component
{
    public $mobileNumber;
    public $amount;
    public $client_reference;
    public $musicId;

    public function mount($music)
    {
        $this->amount = $music->amount;
        $this->client_reference = $music->id . ' ' . ($music->title ?? '-');
        $this->musicId = $music->id;
    }

    public function pay()
    {
        try {

            $baseUrl = 'https://api.paylesotho.co.ls';
            $merchantid = config('payments.mpesa_sc');
            $merchantname = config('payments.merchant_name');
            $token = config('payments.token');

            $client = new Client();
            $paymentApiUrl = $baseUrl . '/api/v1/vcl/payment';
            $paymentApiData = [
                'merchantid' => $merchantid,
                'amount' => $this->amount,
                'mobileNumber' => $this->mobileNumber,
                'merchantname' => $merchantname,
                'client_reference' => $this->client_reference,
            ];

            Log::info('Payment API Request: ' . json_encode([
                'url' => $paymentApiUrl,
                'data' => $paymentApiData,
            ]));

            $response = $client->post($paymentApiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ],
                'json' => $paymentApiData,
            ]);

            $responseData = json_decode($response->getBody(), true);
            Log::info('Payment API Response: ' . json_encode($responseData));

            if (isset($responseData['status_code']) && $responseData['status_code'] === 'INS-0') {
                $payRef = $responseData['reference'];
                $verificationUrl = $baseUrl . '/api/v1/vcl/verify/' . $payRef . '/62915';

                $verificationResponse = $client->get($verificationUrl, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ],
                ]);

                $verificationData = json_decode($verificationResponse->getBody(), true);
                Log::info('Confirmation API Response: ' . json_encode($verificationData));

                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::error('Error decoding verification response: ' . json_last_error_msg());
                    session()->flash('error', 'Failed to verify the transaction');
                    return redirect()->back();
                }

                if ($verificationData['status_code'] === 'INS-0') {
                    $this->updateUserBalance($this->musicId, $this->amount);
                    $this->userMusic();
                    $this->dispatch('success2');
                    return $this->downloadSong($this->musicId);
                } else {
                    session()->flash('error', 'Transaction verification failed');
                    return redirect()->back();
                }
            } else {
                session()->flash('error', $responseData['message'] ?? 'Payment API request failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Log::error('Guzzle Request Error: ' . $e->getMessage());
            session()->flash('error', 'Failed to make the API request');
            return redirect()->back();
        }
    }
    public function userMusic()
    {
        $music = Music::find($this->musicId);
        if ($music) {
            $otp = 'GW' . rand(1000, 9999);
            $url = config('app.url');
            $fullurl = $url . '/getdownloads';
            $downloads = new Downloads([
                'artist' => $music->artist,
                'title' => $music->title,
                'mobile' => $this->mobileNumber,
                'file' => $music->file,
                'otp' => $otp,
            ]);
            $downloads->save();
            $message = 'use :' . $otp . ' on ' . $fullurl . ' if download did not start';
            $this->sendSMS2($message, $this->mobileNumber);
        } else {
            Log::error('Music track not found for ID: ' . $this->musicId);
        }
    }

    private function updateUserBalance($musicId, $amount)
    {
        $music = Music::find($musicId);
        if (!$music) {
            Log::error('Music track not found for ID: ' . $musicId);
            return;
        }
        $pivot = $music->users()->wherePivot('music_id', $musicId)->first()->pivot;
        if (!$pivot) {
            Log::error('Pivot record not found for music ID: ' . $musicId);
            return;
        }
        $uploaderId = $pivot->user_id;
        $user = User::find($uploaderId);
        if (!$user) {
            Log::error('Uploader user not found for ID: ' . $uploaderId);
            return;
        }
        $user->balance += $amount;
        $user->save();
        Log::info('User balance updated successfully for User ID: ' . $user->id);
    }


    public function render()
    {
        return view('livewire.payment');
    }


    public function sendSMS2($message, $mobileNumber)
    {
        $apiKey = config('sms.api_key');
        $apiSecret = config('sms.api_secret');
        $to = '+266' . $mobileNumber;
        $accountApiCredentials = $apiKey . ':' . $apiSecret;
        $base64Credentials = base64_encode($accountApiCredentials);
        $authHeader = 'Authorization: Basic ' . $base64Credentials;
        $sendData = json_encode([
            'messages' => [
                [
                    'content' => $message,
                    'destination' => $to,
                ],
            ],
        ]);
        Log::info('SMS Sending Data: ' . $sendData);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://rest.mymobileapi.com/bulkmessages');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sendData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            $authHeader
        ]);

        $result = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status === 200) {
            Log::info('SMS sent successfully');
            return response()->json(['message' => 'SMS sent successfully']);
        } else {
            Log::error('SMS sending failed. Status: ' . $status);
            return response()->json(['message' => 'SMS sending failed'], 500);
        }
    }
    public function downloadSong($musicId)
    {
        $music = Music::find($musicId);

        if (!$music) {
            session()->flash('error', 'Music track not found.');
            return redirect()->back();
        }

        $musicFilePath = $music->file;
        $music->md++;
        $music->downloads++;
        $music->save();

        if (!Storage::exists($musicFilePath)) {
            session()->flash('error', 'Music file not found.');
            return redirect()->back();
        }

        return Storage::download($musicFilePath, $music->artist . '-' . $music->title . '.mp3');
    }

}
