<?php

namespace App\Livewire;

use Livewire\Component;
use GuzzleHttp\Client;
use App\Models\Inquiry;
use Illuminate\Support\Facades\Log;

class InquiryForm extends Component
{
    public $price;
    public $genre;
    public $paymentModal = false; // For showing the confirmation modal
    public $userPhone;
    public $paid = false;
    public $paidAmount;

    // Select price and genre from the table
    public function selectPrice($price, $genre)
    {
        $this->price = $price;
        $this->genre = $genre;
        $this->paymentModal = true; // Show confirmation modal
    }
    // Open payment process after confirmation
    public function processPayment()
    {
        $this->validate([
            'userPhone' => 'required|string|min:8|max:15',
        ]);

        $this->pay($this->price);
    }

    public function pay($amount)
    {
        try {
            $baseUrl = 'https://api.paylesotho.co.ls';
            $merchantid = config('payments.mpesa_sc');
            $merchantname = config('payments.merchant_name');
            $token = config('payments.token');

            $client_reference = uniqid();

            $client = new Client();
            $paymentApiUrl = $baseUrl . '/api/v1/vcl/payment';
            $paymentApiData = [
                'merchantid' => $merchantid,
                'amount' => $amount,
                'mobileNumber' => $this->userPhone,
                'merchantname' => $merchantname,
                'client_reference' => $client_reference,
            ];

            Log::info('Payment API Request: ' . json_encode($paymentApiData));

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

                if ($verificationData['status_code'] === 'INS-0') {
                    return $this->storeAndSendInquiry($amount);
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

    public function storeAndSendInquiry($amount)
    {

        Inquiry::create([
            'genre' => $this->genre,
            'price' => $this->price,
            'phone' => $this->userPhone,
            'paid' => true,
            'paid_amount' => $amount,
        ]);
        // Prepare WhatsApp message
        $message = "Genre: {$this->genre}\nPrice: {$this->price}\nPaid: {$amount}";
        $whatsappUrl = "https://api.whatsapp.com/send?phone=26659073443&text=" . urlencode($message);
        // Reset fields
        $this->reset(['genre', 'price', 'userPhone', 'paid', 'paidAmount']);
        $this->paymentModal = false;

        return redirect()->to($whatsappUrl);
    }

    public function render()
    {
        return view('livewire.inquiry-form');
    }
}
